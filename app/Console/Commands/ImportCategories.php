<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCategories extends Command
{
    /**
     * php artisan import:categories
     */
    protected $signature = 'import:categories';

    protected $description = 'Import kategori dari database Mekarsari ke POS';

    public function handle()
    {
        $this->info('========================================');
        $this->info('IMPORT DATA KATEGORI');
        $this->info('========================================');

        $rows = DB::connection('mekarsari')
            ->table('pj_kategori_barang')
            ->orderBy('id_kategori_barang')
            ->get();

        if ($rows->isEmpty()) {
            $this->warn('Data kategori tidak ditemukan.');
            return Command::SUCCESS;
        }

        $insert = 0;
        $update = 0;
        $skip   = 0;

        foreach ($rows as $row) {

            $exists = DB::table('categories')
                ->where('name', $row->kategori)
                ->first();

            if ($exists) {

                DB::table('categories')
                    ->where('id', $exists->id)
                    ->update([
                        'description' => null,
                        'is_active'  => $row->dihapus == 'tidak' ? 1 : 0,
                        'updated_at' => now(),
                    ]);

                $update++;

            } else {

                DB::table('categories')->insert([
                    'name'        => $row->kategori,
                    'description' => null,
                    'is_active'   => $row->dihapus == 'tidak' ? 1 : 0,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                $insert++;
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
    }
}