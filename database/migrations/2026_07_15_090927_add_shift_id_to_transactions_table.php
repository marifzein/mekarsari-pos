<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // 💡 Menambahkan shift_id tepat setelah kolom user_id
            $table->foreignId('shift_id')
                  ->nullable() // Ditulis nullable biar transaksi lama yang gak punya shift gak error
                  ->after('user_id')
                  ->constrained('shifts')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
        });
    }
};