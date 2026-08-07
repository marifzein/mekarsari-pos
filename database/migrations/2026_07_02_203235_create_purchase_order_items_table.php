<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('purchase_order_id')
                ->constrained()
                ->cascadeOnDelete();
    
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('qty');

            $table->decimal('price',15,0);

            $table->decimal('subtotal',15,0);

            $table->timestamps();

        });
    }
};
