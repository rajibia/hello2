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
        Schema::create('labs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('insurance_id')->nullable();
            // $table->foreignId('insurance_id')->constrained(); 
            // $table->foreign('insurance_id')->references('id')->on('insurances')->onUpdate('cascade')->onDelete('cascade');
            $table->string('insurance_name')->nullable();
            $table->string('tariff')->nullable(); 
            $table->string('non_insured_amount')->nullable()->default(0);
            $table->string('topup')->nullable()->default(0);
            $table->string('gdrg_code')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labs');
    }
};
