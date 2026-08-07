<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('purchase_orders', function (Blueprint $table) {

        $table->id();

        $table->string('po_number')->unique();

        $table->foreignId('supplier_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->date('po_date');

        $table->enum('status',[

            'DRAFT',
            'ORDERED',
            'RECEIVED',
            'CANCELLED'

        ])->default('DRAFT');

        $table->decimal('total',15,0)->default(0);

        $table->text('notes')->nullable();

        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->timestamps();

    });
}
};
