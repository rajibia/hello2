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
        Schema::table('pathology_tests', function (Blueprint $table) {
            $table->unsignedBigInteger('lab_technician_id')->nullable()->after('performed_by');
            $table->foreign('lab_technician_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology_tests', function (Blueprint $table) {
            $table->dropForeign(['lab_technician_id']);
            $table->dropColumn('lab_technician_id');
        });
    }
};
