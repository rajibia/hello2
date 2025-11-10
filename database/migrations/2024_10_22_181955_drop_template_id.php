<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to drop template_id.
     */
    public function up(): void
    {
        Schema::table('pathology_parameter_items', function (Blueprint $table) {
            // Drop the template_id column if it exists
            if (Schema::hasColumn('pathology_parameter_items', 'template_id')) {
                $table->dropForeign(['template_id']); // Drop foreign key constraint
                $table->dropColumn('template_id');   // Drop the template_id column
            }
        });
    }

    /**
     * Reverse the migrations (recreate template_id if rolled back).
     */
    public function down(): void
    {
        Schema::table('pathology_parameter_items', function (Blueprint $table) {
            // Recreate the template_id column when rolling back
            $table->unsignedBigInteger('template_id')->nullable()->after('pathology_id');
            
            // Add foreign key constraint
            $table->foreign('template_id')
                ->references('id')
                ->on('pathology_test_templates')
                ->onDelete('cascade');
        });
    }
};
