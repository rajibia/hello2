<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lab_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_visit_id')->constrained()->onDelete('cascade');
            $table->string('test_name'); // e.g., MALARIA TEST
            $table->enum('result', ['negative', 'positive', 'pending'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lab_test_results');
    }
};