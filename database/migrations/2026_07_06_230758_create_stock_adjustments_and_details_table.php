<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sa')->unique();
            $table->date('tgl_sa');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->enum('status', ['draft', 'closed'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamp('tgl_jam_selesai')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Detail
        Schema::create('stock_adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_adjustment_id')->constrained('stock_adjustments')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->integer('stock_system');
            $table->integer('qty'); // jumlah stok yang dibuang/dikurangi
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_details');
        Schema::dropIfExists('stock_stock_adjustments');
    }
};