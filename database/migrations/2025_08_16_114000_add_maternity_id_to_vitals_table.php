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
        Schema::table('vitals', function (Blueprint $table) {
            $table->unsignedInteger('maternity_id')->nullable()->after('opd_id');
            
            // Add foreign key constraint
            $table->foreign('maternity_id')->references('id')->on('maternity')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropForeign(['maternity_id']);
            $table->dropColumn('maternity_id');
        });
    }
};
