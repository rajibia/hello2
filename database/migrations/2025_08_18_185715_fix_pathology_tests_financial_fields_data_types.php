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
            // Change total field to decimal(10,2) to support decimal amounts
            $table->decimal('total', 10, 2)->change();

            // Change discount field from text to decimal(10,2) for proper calculations
            $table->decimal('discount', 10, 2)->change();

            // Change amount_paid field to decimal(10,2) to support decimal amounts
            $table->decimal('amount_paid', 10, 2)->change();

            // Change balance field to decimal(10,2) to support decimal amounts
            $table->decimal('balance', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology_tests', function (Blueprint $table) {
            // Revert total field back to decimal(10,0)
            $table->decimal('total', 10, 0)->change();

            // Revert discount field back to text
            $table->text('discount')->change();

            // Revert amount_paid field back to decimal(10,0)
            $table->decimal('amount_paid', 10, 0)->change();

            // Revert balance field back to decimal(10,0)
            $table->decimal('balance', 10, 0)->change();
        });
    }
};
