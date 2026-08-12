<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('pelanggan')
                ->nullable()
                ->change();

            $table->index('pelanggan', 'transactions_pelanggan_index');

            $table->foreign('pelanggan', 'transactions_pelanggan_foreign')
                ->references('id')
                ->on('customers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_pelanggan_foreign');
            $table->dropIndex('transactions_pelanggan_index');

            $table->string('pelanggan')
                ->nullable()
                ->change();
        });
    }
};