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
        Schema::create('validation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('import_type');
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('row_number')->nullable();
            $table->string('table_name');
            $table->string('column_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->string('message')->nullable();
            $table->string('import_log_id')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_audits');
    }
};
