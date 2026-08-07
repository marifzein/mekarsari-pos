<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->foreignId('category_id')
                ->nullable()
                ->after('nama_barang')
                ->constrained('categories')
                ->nullOnDelete();

            $table->decimal('harga_beli',15,0)
                ->nullable()
                ->after('harga');

            $table->integer('min_stok')
                ->default(5)
                ->after('stok');

            $table->string('satuan')
                ->default('pcs')
                ->after('min_stok');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropForeign(['category_id']);

            $table->dropColumn([
                'category_id',
                'harga_beli',
                'min_stok',
                'satuan'
            ]);
        });
    }
};