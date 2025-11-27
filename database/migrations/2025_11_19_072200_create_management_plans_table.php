<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('management_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('opd_id')->constrained('opd_patient_departments')->onDelete('cascade');
            
            $table->foreignId('ipd_id')->nullable()->constrained('ipd_patient_departments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('The doctor/user who created the plan.');
            $table->longText('management_plan'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_plans');
    }
};