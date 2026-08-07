<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function run(): void
    {
        // Dikosongkan karena kolom barcode sudah ada di file induk products
    }

    public function down(): void
    {
        //
    }
};