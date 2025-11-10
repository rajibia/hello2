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
        Schema::table('pathology_test_templates', function (Blueprint $table) {
            // Add fields for dynamic form configuration
            $table->json('form_configuration')->nullable()->after('standard_charge');
            $table->string('template_type')->default('standard')->after('form_configuration'); // standard, blood_test, fbc, urine, stool
            $table->string('icon_class')->nullable()->after('template_type');
            $table->string('icon_color')->nullable()->after('icon_class');
            $table->boolean('is_dynamic_form')->default(false)->after('icon_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology_test_templates', function (Blueprint $table) {
            $table->dropColumn(['form_configuration', 'template_type', 'icon_class', 'icon_color', 'is_dynamic_form']);
        });
    }
};
