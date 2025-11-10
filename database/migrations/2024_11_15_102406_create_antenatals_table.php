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
        Schema::create('antenatals', function (Blueprint $table) {
            $table->id();
            $table->boolean('bleeding')->default(false);
            $table->boolean('headache')->default(false);
            $table->boolean('pain')->default(false);
            $table->boolean('constipation')->default(false);
            $table->boolean('urinary_symptoms')->default(false);
            $table->boolean('vomiting')->default(false);
            $table->boolean('cough')->default(false);
            $table->boolean('vaginal_discharge')->default(false);
            $table->boolean('oedema')->default(false);
            $table->boolean('haemorrhoids')->default(false);
            $table->date('date');
            $table->string('condition', 255)->nullable();
            $table->text('special_findings_and_remark')->nullable();
            $table->text('pelvic_examination')->nullable();
            $table->string('sp', 255)->nullable();
            $table->string('uter_size', 255)->nullable();
            $table->string('uterus_size', 255)->nullable();
            $table->string('presentation_position', 255)->nullable();
            $table->string('presenting_part_to_brim', 255)->nullable();
            $table->string('foetal_heart', 255)->nullable();
            $table->string('blood_pressure', 50)->nullable();
            $table->boolean('antenatal_oedema')->default(false);
            $table->string('urine_sugar', 255)->nullable();
            $table->string('urine_albumin', 255)->nullable();
            $table->float('antenatal_weight')->nullable();
            $table->text('remark')->nullable();
            $table->date('next_visit')->nullable();
            $table->text('previous_antenatal_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antenatals');
    }
};
