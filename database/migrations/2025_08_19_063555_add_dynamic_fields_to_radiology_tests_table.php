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
        Schema::table('radiology_tests', function (Blueprint $table) {
            // Add new fields for dynamic functionality
            $table->unsignedBigInteger('template_id')->nullable()->after('case_id');
            $table->json('test_results')->nullable()->after('template_id');
            $table->unsignedBigInteger('lab_technician_id')->nullable()->after('test_results');
            $table->unsignedBigInteger('approved_by_id')->nullable()->after('lab_technician_id');
            $table->timestamp('approved_date')->nullable()->after('approved_by_id');
            $table->timestamp('collection_date')->nullable()->after('approved_date');
            $table->timestamp('expected_date')->nullable()->after('collection_date');
            $table->text('diagnosis')->nullable()->after('expected_date');
            $table->unsignedBigInteger('performed_by')->nullable()->after('diagnosis');

            // Note: Foreign key constraints will be added separately if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radiology_tests', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn([
                'template_id',
                'test_results',
                'lab_technician_id',
                'approved_by_id',
                'approved_date',
                'collection_date',
                'expected_date',
                'diagnosis',
                'performed_by'
            ]);
        });
    }
};
