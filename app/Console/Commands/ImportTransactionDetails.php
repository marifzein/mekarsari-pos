<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTransactionDetails extends Command
{
    protected $signature = 'import:transaction-details';
    protected $description = 'Import detail penjualan dari database Mekarsari (Optimized Batch)';

    public function handle()
    {
        $this->info('STARTING OPTIMIZED IMPORT...');

        // 1. Matikan sementara Foreign Key Checks & Auto Commit bawaan session untuk speedup & kestabilan
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('SET UNIQUE_CHECKS=0;');

        $insertCount = 0;

        // 2. Gunakan Chunking besar (misal 5.000)
        DB::connection('mekarsari')
            ->table('pj_penjualan_detail')
            ->orderBy('id_penjualan_d')
            ->chunk(5000, function ($rows) use (&$insertCount) {
                
                $batchData = [];

                // Load map produk ke memory sekaligus untuk menghindari query N+1 di loop
                $productIds = $rows->pluck('id_barang')->unique()->toArray();
                $products = DB::table('products')
                    ->whereIn('id', $productIds)
                    ->get()
                    ->keyBy('id');

                foreach ($rows as $row) {
                    $product = $products->get($row->id_barang);

                    // Skip jika produk tidak ditemukan
                    if (!$product) {
                        continue; 
                    }

                    $batchData[] = [
                        'id'             => $row->id_penjualan_d,
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
                }

                // 3. Lakukan BULK INSERT / UPSERT dalam 1 Transaction Block
                if (!empty($batchData)) {
                    DB::transaction(function () use ($batchData) {
                        // Gunakan upsert: Insert jika belum ada, Update jika ID sudah ada
                        DB::table('transaction_details')->upsert(
                            $batchData,
                            ['id'], // Unique key constraint
                            ['transaction_id', 'product_id', 'harga_beli', 'kode_barang', 'nama_barang', 'harga', 'qty', 'subtotal', 'updated_at']
                        );
                    });

                    $insertCount += count($batchData);
                    $this->info("Processed batch... Total: {$insertCount} records.");
                }
            });

        // Nyalakan kembali check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::statement('SET UNIQUE_CHECKS=1;');

        $this->info("SELESAI! Total {$insertCount} records berhasil di-import/update tanpa stress I/O.");

        return Command::SUCCESS;
    }
}