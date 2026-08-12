<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCustomers extends Command
{
    protected $signature = 'import:customers';

    protected $description = 'Import data pelanggan dari database Mekarsari';

    public function handle()
    {
        $this->info('IMPORT DATA PELANGGAN');

        $rows = DB::connection('mekarsari')
            ->table('pj_pelanggan')
            ->orderBy('id_pelanggan')
            ->get();

        // Cari kode_unik yang digunakan lebih dari satu pelanggan
        $duplicateCodes = DB::connection('mekarsari')
            ->table('pj_pelanggan')
            ->select('kode_unik')
            ->groupBy('kode_unik')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('kode_unik')
            ->toArray();

        $insert = 0;
        $update = 0;

        foreach ($rows as $row) {

            $kodePelanggan = trim($row->kode_unik);

            // Kalau kode_unik kembar, tambahkan ID pelanggan
            if (in_array($row->kode_unik, $duplicateCodes)) {
                $kodePelanggan .= '-' . $row->id_pelanggan;
            }

            $data = [
                'kode_pelanggan' => $kodePelanggan,
                'nama'           => trim($row->nama),
                'telepon'        => $row->telp ? trim($row->telp) : null,
                'alamat'         => $row->alamat ?: null,
                'email'          => null,
                'catatan'        => $row->info_tambahan ?: null,
                'is_member'      => 0,
                'status'         => $row->dihapus === 'tidak' ? 1 : 0,
                'created_at'     => $row->waktu_input,
                'updated_at'     => now(),
            ];

            $customer = DB::table('customers')
                ->where('id', $row->id_pelanggan)
                ->first();

            if ($customer) {

                DB::table('customers')
                    ->where('id', $row->id_pelanggan)
                    ->update($data);

                $update++;

            } else {

                DB::table('customers')->insert([
                    'id' => $row->id_pelanggan,
                    ...$data,
                ]);

                $insert++;
            }
        }

        $this->table(
            ['Insert', 'Update'],
            [
                [$insert, $update]
            ]
        );

        $this->info('Selesai.');
    }
}