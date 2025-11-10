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
        Schema::create('id_settings', function (Blueprint $table) {
            $table->id();
            $table->string('scope')->unique();
            $table->boolean('enabled')->default(true);
            $table->string('prefix')->default('PT');
            $table->unsignedInteger('digits')->default(5);
            $table->unsignedBigInteger('current_counter')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('id_settings');
    }
};
