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
        Schema::create('pathology_test_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pathology_test_id');
            $table->unsignedBigInteger('template_field_id');
            $table->text('field_value')->nullable();
            $table->string('field_status')->default('normal'); // normal, abnormal, critical
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('pathology_test_id')->references('id')->on('pathology_tests')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('template_field_id')->references('id')->on('pathology_template_fields')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_test_results');
    }
};
