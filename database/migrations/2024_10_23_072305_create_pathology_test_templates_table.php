<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pathology_test_templates')) {
            Schema::create('pathology_test_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('test_name', 160);
            $table->string('short_name');
            $table->string('test_type');
            $table->unsignedInteger('category_id');
            $table->integer('unit')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('method')->nullable();
            $table->integer('report_days')->nullable();
            $table->unsignedInteger('charge_category_id');
            $table->integer('standard_charge');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('pathology_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('charge_category_id')->references('id')->on('charge_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pathology_test_templates')) {
            Schema::dropIfExists('pathology_test_templates');
        }
    }
};
