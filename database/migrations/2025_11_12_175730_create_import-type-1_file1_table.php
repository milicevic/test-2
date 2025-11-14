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
        Schema::create('import-type-1_file1', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->string('sku')->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->string('category')->nullable();
            $table->double('weight_kg', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import-type-1_file1');
    }
};
