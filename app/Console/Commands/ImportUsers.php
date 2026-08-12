<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportUsers extends Command
{
    protected $signature = 'import:users';

    protected $description = 'Import data user dari database Mekarsari';

    public function handle()
    {
        $this->info('IMPORT DATA USER');
        $this->newLine();

        $rows = DB::connection('mekarsari')
            ->table('pj_user')
            ->orderBy('id_user')
            ->get();

        $insert = 0;

        foreach ($rows as $row) {

            $role = match ((int) $row->id_akses) {
                1 => 'Admin',
                2 => 'Kasir',
                4 => 'Admin',
                default => 'Kasir',
            };

            $isActive = (
                $row->status === 'Aktif'
                && $row->dihapus === 'tidak'
            );

            $email = strtolower(trim($row->username)) . '@mekarsari.local';

            $exists = DB::table('users')
                ->where('email', $email)
                ->where('id', '!=', $row->id_user)
                ->exists();

            if ($exists) {
                $email = strtolower(trim($row->username))
                    . $row->id_user
                    . '@mekarsari.local';
            }


            DB::table('users')->insert([
                'id'                => $row->id_user,
                'name'              => trim($row->nama),
                'email'             => $email,
                'role'              => $role,
                'is_active'         => $isActive,
                'email_verified_at' => now(),

                // Password sementara.
                // User dapat diubah kemudian melalui aplikasi.
                'password'          => Hash::make('87654321!'),

                'remember_token'    => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            $insert++;
        }

        $this->table(
            ['Insert'],
            [
                [$insert]
            ]
        );

        $this->newLine();
        $this->info('Selesai.');
    }
}