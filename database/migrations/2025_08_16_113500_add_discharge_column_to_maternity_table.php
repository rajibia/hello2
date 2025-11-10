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
        Schema::table('maternity', function (Blueprint $table) {
            $table->boolean('discharge')->default(0)->after('served');
            $table->string('discharge_status')->nullable()->after('discharge');
            $table->text('discharge_notes')->nullable()->after('discharge_status');
            $table->dateTime('discharge_date')->nullable()->after('discharge_notes');
            $table->boolean('doctor_discharge')->default(0)->after('discharge_date');
            $table->unsignedBigInteger('doctor_incharge')->nullable()->after('doctor_discharge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maternity', function (Blueprint $table) {
            $table->dropColumn('discharge');
            $table->dropColumn('discharge_status');
            $table->dropColumn('discharge_notes');
            $table->dropColumn('discharge_date');
            $table->dropColumn('doctor_discharge');
            $table->dropColumn('doctor_incharge');
        });
    }
};
