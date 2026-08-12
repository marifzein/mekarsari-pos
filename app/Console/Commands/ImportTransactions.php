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
        $this->info('IMPORT DATA TRANSAKSI');

        $rows = DB::connection('mekarsari')
            ->table('pj_penjualan_master')
            ->orderBy('id_penjualan_m')
            ->get();

        $insert = 0;
        $update = 0;

        foreach ($rows as $row) {

            // Pastikan user Mekarsari sudah ada di POS
            $userId = DB::table('users')
                ->where('id', $row->id_user)
                ->value('id');

            if (!$userId) {
                $this->error(
                    "User tidak ditemukan: id_user {$row->id_user}, " .
                    "nota {$row->nomor_nota}"
                );

                return Command::FAILURE;
            }

            // Mapping pelanggan.
            // Boleh NULL karena transaksi tanpa pelanggan tetap valid.
            $customerId = null;

            if ($row->id_pelanggan !== null) {
                $customerId = DB::table('customers')
                    ->where('id', $row->id_pelanggan)
                    ->value('id');

                if (!$customerId) {
                    $this->error(
                        "Pelanggan tidak ditemukan: id_pelanggan {$row->id_pelanggan}, " .
                        "nota {$row->nomor_nota}"
                    );

                    return Command::FAILURE;
                }
            }

            // Cek berdasarkan ID legacy.
            $existing = DB::table('transactions')
                ->where('id', $row->id_penjualan_m)
                ->first();

            $data = [
                'no_nota'     => $row->nomor_nota,
                'user_id'     => $userId,
                'shift_id'    => null,
                'pelanggan'   => $customerId,
                'telp'        => null,
                'subtotal'    => $row->grand_total,
                'diskon'      => $row->potongan,
                'grand_total' => $row->grand_total,
                'cash'        => $row->bayar,
                'voucher'     => $row->voucher,
                'card'        => $row->card,
                'kembalian'   => 0,
                'catatan'     => $row->keterangan_lain,
                'status'      => 'SOLD',
                'created_at'  => $row->tanggal,
                'updated_at'  => $row->tanggal,
            ];

            if ($existing) {

                DB::table('transactions')
                    ->where('id', $row->id_penjualan_m)
                    ->update($data);

                $update++;

            } else {

                DB::table('transactions')->insert([
                    'id'          => $row->id_penjualan_m,
                    ...$data,
                ]);

                $insert++;
            }
        }

        $this->newLine();

        $this->table(
            ['Insert', 'Update'],
            [
                [$insert, $update]
            ]
        );

        $this->info('Selesai.');

        return Command::SUCCESS;
    }
}