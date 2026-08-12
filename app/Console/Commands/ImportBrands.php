<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportBrands extends Command
{
    /**
     * php artisan import:brands
     */
    protected $signature = 'import:brands';

    protected $description = 'Import data brand dari database Mekarsari';

    public function handle()
    {
        $rows = DB::connection('mekarsari')
            ->table('pj_merk_barang')
            ->orderBy('id_merk_barang')
            ->get();

        $insert = 0;

        foreach ($rows as $row) {

            DB::table('brands')->insert([
                'id'          => $row->id_merk_barang,
                'name'        => trim($row->merk),
                'description' => null,
                'is_active'   => ($row->dihapus == 'tidak'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $insert++;
        }

        $this->newLine();
        $this->info('IMPORT DATA BRAND');
        $this->table(
            ['Insert'],
            [
                [$insert]
            ]
        );

        $this->info('Selesai.');
    }
}