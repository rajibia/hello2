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
        Schema::table('insurances', function (Blueprint $table) {
            $table->string('other_identification')->nullable();
            $table->string('claim_check_code')->nullable();
            $table->string('claim_code_count')->nullable();
            $table->string('membership_no_count')->nullable();
            $table->string('non_insurance_medication')->nullable();
            $table->string('card_serial_no_count')->nullable();
            $table->string('visit_per_month')->nullable();
            $table->string('card_type')->nullable();
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurances', function (Blueprint $table) {
            //
            $table->dropColumn(['other_identification', 'claim_check_code', 'claim_code_count', 'membership_no_count', 'non_insurance_medication', 'card_serial_no_count', 'visit_per_month', 'card_type', 'image']);
        });
    }
};
