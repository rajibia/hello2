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
        Schema::table('patients', function (Blueprint $table) {
            // add guardian's name, phone number and relationship tot he patient
            $table->string('guardian_name');
            $table->string('guardian_phone');
            $table->string('guardian_relation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('guardian_name');
            $table->dropColumn('guardian_phone');
            $table->dropColumn('guardian_relation');
        });
    }
};
