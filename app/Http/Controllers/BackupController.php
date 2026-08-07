<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    public function index()
    {
        $path = storage_path('app/backups');

        if(!File::exists($path))
        {
            File::makeDirectory(
                $path,
                0755,
                true
            );
        }

        $files =
            collect(File::files($path))

            ->sortByDesc(
                fn($file)=>$file->getMTime()
            )

            ->map(function($file){

                return [

                    'name'=>$file->getFilename(),

                    'size'=>$file->getSize(),

                    'date'=>$file->getMTime(),

                ];

            });

        return view(
            'backup.index',
            compact('files')
        );
    }

    // hapus
    public function destroy($file)
    {
        $path = storage_path(
            'app/backups/'.$file
        );

        if(File::exists($path))
        {
            File::delete($path);
        }

        return back()->with(
            'success',
            'Backup berhasil dihapus.'
        );
    }

    // backup grok
    public function backup()
    {
        $mysqldump = config('database.mysqldump_path');
        if (!$mysqldump) {
            $mysqldump = env('MYSQLDUMP_PATH', 'mysqldump');
        }

        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        $filename = 'backup_' . now()->format('Y-m-d_His') . ($isWindows ? '.sql' : '.sql.gz');
        $filepath = storage_path('app/backups/' . $filename);

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host     = config('database.connections.mysql.host') ?: '127.0.0.1';

        // Pastikan direktori backup ada
        $backupDir = dirname($filepath);
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        if ($isWindows) {
            // Windows - tanpa gzip (lebih stabil)
            $command = sprintf(
                '"%s" --host=%s --user=%s --password="%s" --routines --triggers --events %s > "%s"',
                $mysqldump,
                $host,
                $username,
                $password,
                $database,
                $filepath
            );
        } else {
            // Linux
            $command = sprintf(
                '"%s" --host=%s --user=%s --password="%s" --routines --triggers --events %s | gzip > "%s"',
                $mysqldump,
                $host,
                $username,
                $password,
                $database,
                $filepath
            );
        }

        exec($command, $output, $returnCode);
        

        // Jika $returnCode TIDAK SAMA DENGAN 0, artinya perintah GAGAL
        if ($returnCode !== 0) {
            // Gabungkan array output menjadi string untuk log error
            $error = implode("\n", $output) ?: "Unknown error dengan return code: " . $returnCode;
            
            \Log::error("Backup Gagal: " . $error);
            
            return back()->with(
                'error',
                'Backup gagal: ' . $error
            );
        }

        return back()->with(
            'success',
            'Backup berhasil : ' . $filename
        );
    }
    // backup gmn
    
    // Method Baru untuk Membuat Skema Bersih Tanpa Data Transaksi
    // Method Baru untuk Membuat Skema Bersih (Hanya data User, Kategori, & Sistem Laravel yang diisi)
    public function backupSkemaOnly()
    {
        $mysqldump = config('database.mysqldump_path') ?: env('MYSQLDUMP_PATH', 'mysqldump');
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        // File sementara untuk nampung full backup mentah sebelum disaring
        $rawFilename = 'raw_backup_' . now()->format('Y-m-d_His') . '.sql';
        $rawFilepath = storage_path('app/backups/' . $rawFilename);

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host     = config('database.connections.mysql.host') ?: '127.0.0.1';

        // 1. Dump data mentah utuh dari database
        $command = sprintf(
            '"%s" --host=%s --user=%s --password="%s" --routines --triggers --events %s > "%s"',
            $mysqldump, $host, $username, $password, $database, $rawFilepath
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || !File::exists($rawFilepath)) {
            $error = implode("\n", $output) ?: "Gagal dump data mentah.";
            return back()->with('error', 'Gagal inisiasi skema: ' . $error);
        }

        // 2. Tentukan nama file hasil akhir release skema bersih
        $finalFilename = 'release_skema_' . now()->format('Y-m-d_His') . '.sql';
        $finalFilepath = storage_path('app/backups/' . $finalFilename);

        // 💡 SISTEM WHITELIST: Hanya tabel di daftar ini yang AMAN / Boleh ada datanya
        $allowedDataTables = [
            'users', 
            'categories', 
            'migrations' // Penting agar riwayat migrasi laravel tidak eror saat dibaca
        ];

        // 3. Proses Streaming pembacaan file mentah baris demi baris
        $in = fopen($rawFilepath, 'r');
        $out = fopen($finalFilepath, 'w');

        if (!$in || !$out) {
            return back()->with('error', 'Gagal memproses streaming penyaringan tabel.');
        }

        $skip = false;

        while (($line = fgets($in)) !== false) {
            // Jika dalam mode skip (melewati baris data tabel yang tidak diizinkan)
            if ($skip) {
                if (str_contains(trim($line), ';')) {
                    $skip = false; // Berhenti skip kalau ketemu akhir perintah query ;
                }
                continue;
            }

            // Deteksi jika baris berisi perintah INSERT INTO `nama_tabel`
            if (preg_match('/^INSERT INTO `(.*?)`/', $line, $m)) {
                $table = $m[1];

                // Jika nama tabel TIDAK ADA di daftar aman, buang/skip datanya!
                if (!in_array($table, $allowedDataTables)) {
                    $skip = true;
                    if (str_contains(trim($line), ';')) {
                        $skip = false;
                    }
                    continue; 
                }

                // 🔥 MODIFIKASI KHUSUS TABEL USERS: Paksa hanya isi 1 Admin Utama
                if ($table === 'users') {
                    // Password di bawah ini adalah hasil hash aman dari: 87654321
                    $adminPasswordHash = '$2y$12$fGaEEiO6Hlcu7qm7C.XhoeD7Ck2Sm1eyZxaOrHu5zLl1/hCvkh5c2';
                    $now = now()->format('Y-m-d H:i:s');
                    
                    // Kita timpa isi baris $line menjadi hanya 1 record Admin
                    $line = "INSERT INTO `users` VALUES (1,'Admin','admin@gmail.com','Admin',1,NULL,'{$adminPasswordHash}',NULL,'{$now}','{$now}');\n";
                }
            }

            // Tulis baris yang aman (struktur tabel, atau data dari tabel users, categories, migrations)
            fwrite($out, $line);
        }

        fclose($in);
        fclose($out);

        // Hapus file mentah sementara agar storage tidak penuh
        File::delete($rawFilepath);

        return back()->with('success', 'Skema Cabang Baru Berhasil Dibuat: ' . $finalFilename);
    }

    public function download($file)
    {
        return response()->download(

            storage_path(
                'app/backups/' .
                $file
            )

        );
    }

    
}