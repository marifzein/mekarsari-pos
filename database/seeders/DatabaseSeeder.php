<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([

            CategorySeeder::class,
            ChartOfAccountSeeder::class,
            ClientModuleSeeder::class

        ]);

        // Jalankan seeder user admin pertama di sini
        User::firstOrCreate(
            ['email' => 'super@gmail.com'],
            [
                'name' => 'Developer',
                'password' => Hash::make('87654321'),
                'role' => 'Admin', // Pastikan valuenya sesuai dengan pengecekan middleware lu
                'is_active' => 1
            ]
        );
    }

    
}