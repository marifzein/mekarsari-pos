<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('min_qty');

            $table->decimal('harga',15,0);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};