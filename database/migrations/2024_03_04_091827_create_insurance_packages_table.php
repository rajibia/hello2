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
        Schema::create('insurance_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('insurance_id');
            $table->string('package_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('insurance_id')->references('id')->on('insurances')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_packages');
    }
};
