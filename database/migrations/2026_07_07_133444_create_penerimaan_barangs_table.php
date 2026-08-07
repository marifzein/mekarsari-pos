<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerimaan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('no_penerimaan')->unique(); // Contoh: GR-20260707-0001
            $table->string('no_po')->nullable(); // String nomor PO, opsional jika kulakan langsung
            $table->string('no_dokumen_supplier')->nullable(); // Nota / Surat jalan grosir
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
            $table->date('tanggal_terima');
            $table->text('catatan')->nullable();
            $table->integer('total_item')->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // Siapa yang input
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan_barang');
    }
};