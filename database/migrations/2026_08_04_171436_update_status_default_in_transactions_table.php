<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah default value kolom 'status' menjadi 'SOLD'
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')->default('SOLD')->change();
        });

        // 2. Perbarui data lama yang statusnya masih 'selesai' menjadi 'SOLD'
        DB::table('transactions')
            ->where('status', 'selesai')
            ->update(['status' => 'SOLD']);
    }

    public function down(): void
    {
        // Kembalikan default value ke 'selesai' jika di-rollback
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')->default('selesai')->change();
        });

        // Kembalikan data 'SOLD' ke 'selesai'
        DB::table('transactions')
            ->where('status', 'SOLD')
            ->update(['status' => 'selesai']);
    }
};