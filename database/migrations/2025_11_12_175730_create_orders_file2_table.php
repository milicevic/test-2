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
        Schema::create('orders_file2', function (Blueprint $table) {
            $table->id();
            $table->date('order_date')->nullable();
            $table->string('channel')->nullable();
            $table->string('item_description')->nullable();
            $table->string('origin')->nullable();
            $table->string('office')->nullable();
            $table->double('cost', 8, 2)->nullable();
            $table->double('shipping_cost', 8, 2)->nullable();
            $table->double('total_price', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_file2');
    }
};
