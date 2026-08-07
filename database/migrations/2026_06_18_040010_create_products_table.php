<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('nama_barang');
            $table->decimal('harga', 15, 0);
            $table->integer('stok')->default(0);
            // $table->integer('min_stok')->default(5);
            // $table->string('satuan')->default('pcs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};