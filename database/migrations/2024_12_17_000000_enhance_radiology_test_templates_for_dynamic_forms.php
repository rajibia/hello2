<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('radiology_test_templates', function (Blueprint $table) {
            $table->json('form_configuration')->nullable()->after('standard_charge');
            $table->string('template_type')->nullable()->after('form_configuration');
            $table->string('icon_class')->nullable()->after('template_type');
            $table->string('icon_color', 7)->default('#007bff')->after('icon_class');
            $table->boolean('is_dynamic_form')->default(false)->after('icon_color');
        });
    }

    public function down(): void
    {
        Schema::table('radiology_test_templates', function (Blueprint $table) {
            $table->dropColumn([
                'form_configuration',
                'template_type',
                'icon_class',
                'icon_color',
                'is_dynamic_form'
            ]);
        });
    }
};
