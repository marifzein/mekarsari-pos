<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportProductPrices extends Command
{
    protected $signature = 'import:product-prices';

    protected $description = 'Import harga grosir / potongan produk dari database Mekarsari';

    public function handle()
    {
        $this->info('IMPORT HARGA GROSIR PRODUK');

        $rows = DB::connection('mekarsari')
            ->table('pj_barang')
            ->orderBy('id_barang')
            ->get();

        $insert = 0;
        $update = 0;
        $skip = 0;

        foreach ($rows as $row) {

            // Cari product berdasarkan ID legacy
            $productId = DB::table('products')
                ->where('id', $row->id_barang)
                ->value('id');

            if (!$productId) {
                $this->error(
                    "Produk tidak ditemukan: id_barang {$row->id_barang}, " .
                    "kode {$row->kode_barang}"
                );

                return Command::FAILURE;
            }

            // Ambil maksimal 3 level harga grosir
            $levels = [
                [
                    'min_qty'  => $row->min_beli_1,
                    'potongan' => $row->pot_beli_1,
                ],
                [
                    'min_qty'  => $row->min_beli_2,
                    'potongan' => $row->pot_beli_2,
                ],
                [
                    'min_qty'  => $row->min_beli_3,
                    'potongan' => $row->pot_beli_3,
                ],
            ];

            foreach ($levels as $level) {

                $minQty = (int) $level['min_qty'];
                $potonganRaw = trim((string) $level['potongan']);

                // Tidak ada rule grosir
                if ($minQty <= 0 || $potonganRaw === '') {
                    $skip++;
                    continue;
                }

                $potongan = (int) $potonganRaw;

                // Cek berdasarkan product + min_qty
                $existing = DB::table('product_prices')
                    ->where('product_id', $productId)
                    ->where('min_qty', $minQty)
                    ->first();

                $data = [
                    'product_id' => $productId,
                    'min_qty'    => $minQty,
                    'potongan'   => $potongan,
                    'updated_at' => now(),
                ];

                if ($existing) {

                    DB::table('product_prices')
                        ->where('id', $existing->id)
                        ->update($data);

                    $update++;

                } else {

                    DB::table('product_prices')->insert([
                        ...$data,
                        'created_at' => now(),
                    ]);

                    $insert++;
                }
            }
        }

        $this->newLine();

        $this->table(
            ['Insert', 'Update', 'Skip'],
            [
                [$insert, $update, $skip]
            ]
        );

        $this->info('Selesai.');

        return Command::SUCCESS;
    }
}