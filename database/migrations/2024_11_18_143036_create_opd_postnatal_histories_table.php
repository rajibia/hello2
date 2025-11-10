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
        if (!Schema::hasTable('opd_postnatal_history')) {
            Schema::create('opd_postnatal_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('patient_id'); // Foreign key to the patients table
            $table->time('labour_time')->nullable(); // Time of labour
            $table->time('delivery_time')->nullable(); // Time of delivery
            $table->text('routine_question')->nullable(); // Routine questions
            $table->text('general_remark')->nullable(); // General remarks
            $table->timestamps(); // Created_at and updated_at

                // Define foreign key relationship
                $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_postnatal_history');
    }
};
