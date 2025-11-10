<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPathologyIdToPathologyTestTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pathology_test_templates', function (Blueprint $table) {
            // Add the pathology_id column
            $table->unsignedInteger('pathology_id')->after('id'); // Use 'after' to specify the column position if needed
            
            // Add the foreign key constraint
            $table->foreign('pathology_id')
                  ->references('id')
                  ->on('pathology_tests')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pathology_test_templates', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['pathology_id']);
            // Drop the pathology_id column
            $table->dropColumn('pathology_id');
        });
    }
}
