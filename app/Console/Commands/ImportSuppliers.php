<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSuppliers extends Command
{
    protected $signature = 'import:suppliers';
    protected $description = 'Import Supplier (pj_merk_barang) dari Mekarsari';

    public function handle()
    {
        $rows = DB::connection('mekarsari')
            ->table('pj_merk_barang')
            ->orderBy('id_merk_barang')
            ->get();

        $count = 0;

        foreach ($rows as $row) {
            DB::table('suppliers')->insert([
                'id'         => $row->id_merk_barang,
                'kode'       => 'SUP-' . str_pad($row->id_merk_barang, 4, '0', STR_PAD_LEFT), // Hasil: SUP-0001, dst
                'nama'       => trim($row->merk),
                'pic'        => null,
                'telepon'    => null,
                'email'      => null,
                'alamat'     => null,
                'is_active'  => $row->dihapus === 'tidak',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $count++;
        }

        $this->info("Berhasil mengimpor {$count} data supplier.");
    }
}