<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Induk Retur
        Schema::create('retur_barang', function (Blueprint $table) {
            $table->id();
            $table->string('no_retur')->unique(); // Format: RT-YYYYMMDD-0001
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->date('tanggal_retur');
            $table->string('catatan')->nullable();
            $table->integer('total_item');
            $table->foreignId('user_id')->constrained('users'); // Petugas
            $table->timestamps();
        });

        // 2. Tabel Rincian Item Retur
        Schema::create('retur_barang_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retur_barang_id')->constrained('retur_barang')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('qty_retur');
            $table->decimal('harga_beli', 15, 2); // Harga saat barang diretur
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur_barang_items');
        Schema::dropIfExists('retur_barang');
    }
};