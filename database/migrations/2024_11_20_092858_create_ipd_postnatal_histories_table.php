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
        if (!Schema::hasTable('ipd_postnatal_history')) {
            Schema::create('ipd_postnatal_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('patient_id'); 
            $table->time('labour_time')->nullable(); 
            $table->time('delivery_time')->nullable();
            $table->text('routine_question')->nullable();
            $table->text('general_remark')->nullable(); 
            $table->timestamps(); 

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
        Schema::dropIfExists('ipd_postnatal_history');
    }
};
