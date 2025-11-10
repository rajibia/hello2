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
        if (!Schema::hasTable('opd_previous_obstetric_histories')) {
            Schema::create('opd_previous_obstetric_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('patient_id'); 
            $table->string('place_of_delivery'); 
            $table->integer('duration_of_pregnancy'); 
            $table->text('complication_in_pregnancy_or_puerperium')->nullable(); 
            $table->float('birth_weight')->nullable(); 
            $table->enum('gender', ['Male', 'Female', 'Other']); 
            $table->string('infant_feeding')->nullable(); 
            $table->enum('birth_status', ['Alive', 'Dead'])->default('Alive'); 
            $table->boolean('alive')->default(true);
            $table->date('alive_or_dead_date')->nullable(); 
            $table->text('previous_medical_history')->nullable(); 
            $table->text('special_instruction')->nullable(); 
            $table->timestamps();

                // Foreign key constraint
                $table->foreign('patient_id')
                      ->references('id')
                      ->on('patients')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_previous_obstetric_histories');
    }
};
