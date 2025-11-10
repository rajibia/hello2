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
        Schema::table('opd_diagnoses', function (Blueprint $table) {
            // add name and code fields
            $table->string('name');
            $table->string('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_diagnoses', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('code');
        });
    }
};
