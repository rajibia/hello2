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
        Schema::table('services', function (Blueprint $table) {
            $table->string('non_insured_amount')->nullable()->default(0);
            $table->string('topup')->nullable()->default(0);
            $table->string('charge_status')->nullable();
            $table->string('icd_code')->nullable();
            $table->string('age')->nullable();
            $table->string('insurance_id')->nullable();
            $table->string('insurance_name')->nullable();
            $table->string('speciality_code')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            //
        });
    }
};
