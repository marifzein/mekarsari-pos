<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTransactionDetails extends Command
{
    /**
     * php artisan import:transaction-details
     */
    protected $signature = 'import:transaction-details';

    protected $description = 'Import detail penjualan dari database Mekarsari';

    public function handle()
    {
        $this->info('IMPORT DATA DETAIL TRANSAKSI');

        $insert = 0;
        $update = 0;

        DB::connection('mekarsari')
            ->table('pj_penjualan_detail')
            ->orderBy('id_penjualan_d')
            ->chunk(1000, function ($rows) use (&$insert, &$update) {

                foreach ($rows as $row) {

                    // Pastikan transaksi master sudah ada
                    $transactionExists = DB::table('transactions')
                        ->where('id', $row->id_penjualan_m)
                        ->exists();

                    if (!$transactionExists) {
                        $this->error(
                            "Transaksi tidak ditemukan: " .
                            "id_penjualan_m {$row->id_penjualan_m}"
                        );

                        throw new \RuntimeException(
                            "Transaction master tidak ditemukan."
                        );
                    }

                    // Pastikan produk sudah ada
                    $product = DB::table('products')
                        ->where('id', $row->id_barang)
                        ->first();

                    if (!$product) {
                        $this->error(
                            "Produk tidak ditemukan: " .
                            "id_barang {$row->id_barang}"
                        );

                        throw new \RuntimeException(
                            "Product tidak ditemukan."
                        );
                    }

                    $data = [
                        'transaction_id' => $row->id_penjualan_m,
                        'product_id'     => $row->id_barang,
                        'harga_beli'     => $row->hargabeli,
                        'kode_barang'    => $product->kode_barang,
                        'nama_barang'    => $product->nama_barang,
                        'harga'          => $row->harga_satuan,
                        'qty'            => $row->jumlah_beli,
                        'subtotal'       => $row->total,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ];

                    // ID legacy dipertahankan
                    $exists = DB::table('transaction_details')
                        ->where('id', $row->id_penjualan_d)
                        ->exists();

                    if ($exists) {

                        DB::table('transaction_details')
                            ->where('id', $row->id_penjualan_d)
                            ->update($data);

                        $update++;

                    } else {

                        DB::table('transaction_details')->insert([
                            'id' => $row->id_penjualan_d,
                            ...$data,
                        ]);

                        $insert++;
                    }
                }
            });

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