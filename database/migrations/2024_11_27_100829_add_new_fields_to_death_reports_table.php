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
        Schema::table('death_reports', function (Blueprint $table) {
            $table->string('cause_of_death')->nullable()->after('description');
            $table->string('immediate_cause_of_death')->nullable()->after('cause_of_death');
            $table->string('location_of_death')->nullable()->after('immediate_cause_of_death');
            $table->string('next_of_kin')->nullable()->after('location_of_death');
            $table->string('next_of_kin_contact')->nullable()->after('next_of_kin');
            $table->string('attachments')->nullable()->after('next_of_kin_contact'); // File path or URL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('death_reports', function (Blueprint $table) {
            $table->dropColumn([
                'cause_of_death',
                'immediate_cause_of_death',
                'location_of_death',
                'next_of_kin',
                'next_of_kin_contact',
                'attachments',
            ]);
        });
    }
};
