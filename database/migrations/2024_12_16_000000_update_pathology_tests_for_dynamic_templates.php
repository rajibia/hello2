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
            // Add new fields for dynamic template system
            if (!Schema::hasColumn('pathology_tests', 'template_id')) {
                $table->unsignedInteger('template_id')->nullable()->after('status');
            }
            if (!Schema::hasColumn('pathology_tests', 'test_results')) {
                $table->json('test_results')->nullable()->after('template_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology_tests', function (Blueprint $table) {
            $table->dropColumn(['template_id', 'test_results']);
        });
    }
};
