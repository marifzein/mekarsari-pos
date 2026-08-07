<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('footer_settings', function (Blueprint $schema) {
            $schema->id();
            $schema->string('section_left')->nullable();
            $schema->string('section_center')->nullable();
            $schema->string('section_right')->nullable();
            $schema->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
