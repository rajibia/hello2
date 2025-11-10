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
        Schema::table('pathology_parameter_items', function (Blueprint $table) {
            // Drop the template_id column if it exists
            if (Schema::hasColumn('pathology_parameter_items', 'template_id')) {
                $table->dropForeign(['template_id']); // Drop foreign key constraint
                $table->dropColumn('template_id');   // Drop the template_id column
            }

            // Recreate the template_id column
            $table->unsignedInteger('template_id')->nullable()->after('pathology_id');

            // Add foreign key constraint for template_id
            $table->foreign('template_id')
                  ->references('id')
                  ->on('pathology_test_templates')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology_parameter_items', function (Blueprint $table) {
            // Drop the template_id column during rollback
            if (Schema::hasColumn('pathology_parameter_items', 'template_id')) {
                $table->dropForeign(['template_id']);
                $table->dropColumn('template_id');
            }
        });
    }
};
