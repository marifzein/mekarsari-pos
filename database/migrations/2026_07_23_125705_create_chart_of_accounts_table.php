<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code', 10)->unique(); // Kode akun unik (misal: 10100)
            $table->string('account_name', 100);          // Nama akun (misal: Bank BCA)
            $table->string('account_type', 50);          // HARTA, UTANG, MODAL, PENDAPATAN, BEBAN
            $table->enum('report_type', ['NERACA', 'LABA_RUGI']); // Flag pembagi laporan utama
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};