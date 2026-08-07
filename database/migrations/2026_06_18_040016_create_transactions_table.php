<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('pelanggan')->nullable();
            $table->string('telp')->nullable();
            $table->decimal('subtotal', 15, 0)->default(0);
            $table->decimal('diskon', 15, 0)->default(0);
            $table->decimal('grand_total', 15, 0)->default(0);
            $table->decimal('cash', 15, 0)->default(0);
            $table->decimal('voucher', 15, 0)->default(0);
            $table->decimal('card', 15, 0)->default(0);
            $table->decimal('kembalian', 15, 0)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};