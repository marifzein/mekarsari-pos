<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerimaan_barang_items', function (Blueprint $table) {
            $table->id();
            // Hubungkan ke tabel induk penerimaan_barang
            $table->foreignId('penerimaan_barang_id')
                  ->constrained('penerimaan_barang')
                  ->onDelete('cascade'); // Kalau induk dihapus, item ikut kehapus
                  
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->integer('qty_po')->default(0); // Qty rencana awal di PO
            $table->integer('qty_terima'); // Qty riil yang datang
            $table->decimal('harga_beli', 15, 0); // Harga beli riil saat nota datang
            $table->decimal('subtotal', 15, 0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan_barang_items');
    }
};