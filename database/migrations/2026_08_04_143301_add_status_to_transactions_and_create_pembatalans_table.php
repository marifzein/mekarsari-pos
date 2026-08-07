<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Alter tabel transactions -> Tambah kolom status (varchar/string)
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')->default('selesai')->after('catatan');
        });

        // 2. Buat tabel baru pembatalan_penjualans
        Schema::create('pembatalan_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('alasan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembatalan_penjualans');

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};