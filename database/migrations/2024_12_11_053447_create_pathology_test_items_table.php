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
        if (!Schema::hasTable('pathology_test_items')) {
            Schema::create('pathology_test_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('pathology_id');
                $table->date('report_date'); // Routine questions
                $table->unsignedInteger('test_name');
                $table->timestamps();

                $table->foreign('pathology_id')->references('id')->on('pathology_tests')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('test_name')->references('id')->on('pathology_test_templates')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_test_items');
    }
};
