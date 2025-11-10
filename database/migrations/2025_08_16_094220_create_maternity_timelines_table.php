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
        Schema::create('maternity_timelines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('maternity_id');
            $table->string('title');
            $table->date('date');
            $table->text('description')->nullable();
            $table->boolean('visible_to_person')->default(true);
            $table->timestamps();

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
        Schema::dropIfExists('maternity_timelines');
    }
};
