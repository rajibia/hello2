<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lab_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->date('visit_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lab_visits');
    }
};