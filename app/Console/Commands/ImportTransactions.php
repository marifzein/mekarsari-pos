<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTransactions extends Command
{
    protected $signature = 'import:transactions';

    protected $description = 'Import data penjualan master dari database Mekarsari';

    public function handle()
    {
        $this->info('MULAI IMPORT MASTER TRANSACTIONS...');

        // 1. Matikan sementara Foreign Key Checks agar proses insert berjalan sangat cepat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('SET UNIQUE_CHECKS=0;');

        $insertCount = 0;

        // 2. Gunakan Chunking 5.000 record per batch
        DB::connection('mekarsari')
            ->table('pj_penjualan_master')
            ->orderBy('id_penjualan_m')
            ->chunk(5000, function ($rows) use (&$insertCount) {
                
                $batchData = [];

                foreach ($rows as $row) {
                    // Kalkulasi Matematika Nilai Transaksi
                    $grandTotal = (float) ($row->grand_total ?? 0);
                    $diskon     = (float) ($row->potongan ?? 0);
                    $subtotal   = $grandTotal + $diskon;

                    $cash    = (float) ($row->bayar ?? 0);
                    $card    = (float) ($row->card ?? 0);
                    $voucher = (float) ($row->voucher ?? 0);

                    $totalBayar = $cash + $card + $voucher;
                    $kembalian  = max(0, $totalBayar - $grandTotal);

                    // Konversi Waktu
                    $createdAt = $row->tanggal ?? now();

                    $batchData[] = [
                        'id'          => $row->id_penjualan_m,
                        'no_nota'     => $row->nomor_nota,
                        'user_id'     => $row->id_user ?? 1, // Fallback user default jika null
                        'shift_id'    => null,
                        'pelanggan'   => $row->id_pelanggan ?: null,
                        'telp'        => null,
                        'subtotal'    => $subtotal,
                        'diskon'      => $diskon,
                        'grand_total' => $grandTotal,
                        'cash'        => $cash,
                        'voucher'     => $voucher,
                        'card'        => $card,
                        'kembalian'   => $kembalian,
                        'catatan'     => $row->keterangan_lain,
                        'status'      => 'SOLD',
                        'created_at'  => $createdAt,
                        'updated_at'  => $createdAt,
                    ];
                }

                // 3. Bulk Insert / Upsert massal dalam 1 Transaction Block
                if (!empty($batchData)) {
                    DB::transaction(function () use ($batchData) {
                        DB::table('transactions')->upsert(
                            $batchData,
                            ['id'], // Primary Key constraint
                            [
                                'no_nota', 
                                'user_id', 
                                'pelanggan', 
                                'subtotal', 
                                'diskon', 
                                'grand_total', 
                                'cash', 
                                'voucher', 
                                'card', 
                                'kembalian', 
                                'catatan', 
                                'updated_at'
                            ]
                        );
                    });

                    $insertCount += count($batchData);
                    $this->info("Berhasil memproses batch master transactions... Total: {$insertCount} records.");
                }
            });

        // Nyalakan kembali check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::statement('SET UNIQUE_CHECKS=1;');

        $this->info("SELESAI! Total {$insertCount} data master transaksi berhasil di-import/update.");

        return Command::SUCCESS;
    }
}