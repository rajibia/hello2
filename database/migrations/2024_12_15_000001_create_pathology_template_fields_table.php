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
        Schema::create('pathology_template_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('template_id');
            $table->string('field_name');
            $table->string('field_label');
            $table->string('field_type'); // text, number, dropdown, textarea, date, etc.
            $table->json('field_options')->nullable(); // For dropdowns, radio buttons, etc.
            $table->string('field_placeholder')->nullable();
            $table->string('field_validation')->nullable(); // Validation rules
            $table->boolean('is_required')->default(false);
            $table->integer('field_order')->default(0);
            $table->string('field_group')->nullable(); // For grouping fields (e.g., "Blood Group Test", "Hepatitis B Test")
            $table->string('field_unit')->nullable(); // For numerical fields
            $table->string('reference_range')->nullable(); // Normal range for the field
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('pathology_test_templates')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_template_fields');
    }
};
