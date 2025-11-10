<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if pathology_id column exists
        $hasPathologyIdColumn = Schema::hasColumn('pathology_test_templates', 'pathology_id');

        if ($hasPathologyIdColumn) {
            // If column exists, try to drop the foreign key constraint first
            try {
                DB::statement('ALTER TABLE pathology_test_templates DROP FOREIGN KEY pathology_test_templates_pathology_id_foreign');
            } catch (\Exception $e) {
                // Constraint might not exist, continue
            }

            // Make the pathology_id column nullable
            Schema::table('pathology_test_templates', function (Blueprint $table) {
                $table->unsignedInteger('pathology_id')->nullable()->change();
            });
        } else {
            // If column doesn't exist, add it as nullable
            Schema::table('pathology_test_templates', function (Blueprint $table) {
                $table->unsignedInteger('pathology_id')->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology_test_templates', function (Blueprint $table) {
            // Make the column not nullable again
            $table->unsignedInteger('pathology_id')->nullable(false)->change();

            // Re-add the foreign key constraint
            $table->foreign('pathology_id')
                  ->references('id')
                  ->on('pathology_tests')
                  ->onDelete('cascade');
        });
    }
};
