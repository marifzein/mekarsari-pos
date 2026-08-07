<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->foreignId('supplier_id')
                ->nullable()
                ->after('category_id')
                ->constrained('suppliers')
                ->nullOnDelete();

            $table->string('brand')
                ->nullable()
                ->after('supplier_id');

            $table->text('catatan')
                ->nullable()
                ->after('brand');
            // menyelipkan hrgadiskon disini
            $table->decimal('harga_diskon', 15, 0)
            ->nullable()
            ->after('harga_beli');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropForeign(['supplier_id']);

            $table->dropColumn([
                'supplier_id',
                'brand',
                'catatan'
            ]);

        });
    }
};
