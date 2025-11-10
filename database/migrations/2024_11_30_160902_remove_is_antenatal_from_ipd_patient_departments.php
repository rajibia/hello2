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
        if (Schema::hasTable('ipd_patient_departments')) {
            Schema::table('ipd_patient_departments', function (Blueprint $table) {
                if (Schema::hasColumn('ipd_patient_departments', 'is_antenatal')) {
                    $table->dropColumn('is_antenatal');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_patient_departments', function (Blueprint $table) {
            //
        });
    }
};
