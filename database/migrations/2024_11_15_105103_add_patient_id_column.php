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
        if (!Schema::hasColumn('antenatals', 'patient_id')) {
            Schema::table('antenatals', function (Blueprint $table) {
                // Add patient_id field after id
                $table->unsignedInteger('patient_id')->after('id');

                // Add foreign key constraint to reference the 'id' column in 'patients' table
                $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antenatals', function (Blueprint $table) {
            // Drop the foreign key constraint if it exists
            if (Schema::hasColumn('antenatals', 'patient_id')) {
                $table->dropForeign(['patient_id']);
            }

            // Drop the patient_id column if it exists
            if (Schema::hasColumn('antenatals', 'patient_id')) {
                $table->dropColumn('patient_id');
            }
        });
    }
};
