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
        Schema::create('maternity_consultant_registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maternity_id');
            $table->dateTime('applied_date');
            $table->unsignedBigInteger('doctor_id');
            $table->text('instruction');
            $table->date('instruction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maternity_consultant_registers');
    }
};
