<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportProducts extends Command
{
    /**
     * php artisan import:products
     */
    protected $signature = 'import:products';

    protected $description = 'Import Produk dari Mekarsari';

    public function handle()
    {
        $rows = DB::connection('mekarsari')
            ->table('pj_barang')
            ->orderBy('id_barang')
            ->get();

        $insert = 0;
        $opening = 0;

        foreach ($rows as $row) {

            DB::transaction(function () use ($row, &$insert, &$opening) {

                DB::table('products')->insert([
                    'id'           => $row->id_barang,
                    'kode_barang'  => trim($row->kode_barang),
                    'barcode'      => null,
                    'nama_barang'  => trim($row->nama_barang),

                    'category_id'  => $row->id_kategori_barang,
                    'supplier_id'  => $row->id_merk_barang,
                    'brand_id'     => null,

                    'catatan'      => $row->keterangan,

                    'harga'        => $row->harga,
                    'harga_beli'   => $row->hargabeli,
                    'harga_diskon' => null,

                    'stok'         => $row->total_stok,
                    'min_stok'     => 5,
                    'satuan'       => 'pcs',

                    'is_active'    => $row->dihapus == 'tidak',

                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                $insert++;

                // ==========================================
                // SIMPAN SALDO AWAL KE KARTU STOK
                // ==========================================

                DB::table('stock_movements')->insert([
                    'product_id'   => $row->id_barang,
                    'type'         => 'OPENING',
                    'qty'          => $row->total_stok,
                    'stock_before' => 0,
                    'stock_after'  => $row->total_stok,
                    'reference_no' => 'IMPORT-MEKARSARI',
                    'notes'        => 'Saldo awal import produk dari Mekarsari',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                $opening++;
            });
        }

        $this->newLine();
        $this->info('IMPORT DATA PRODUK');

        $this->table(
            ['Insert', 'Opening'],
            [
                [$insert, $opening]
            ]
        );

        $this->info('Selesai.');
    }
}