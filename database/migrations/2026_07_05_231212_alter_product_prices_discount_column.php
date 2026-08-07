<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_prices', function (Blueprint $table) {

            $table->renameColumn(
                'harga',
                'potongan'
            );

        });
    }

    public function down(): void
    {
        Schema::table('product_prices', function (Blueprint $table) {

            $table->renameColumn(
                'potongan',
                'harga'
            );

        });
    }
};
