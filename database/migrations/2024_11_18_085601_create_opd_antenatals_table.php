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
        if (!Schema::hasTable('opd_antenatals')) {
            Schema::create('opd_antenatals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('patient_id'); // Matching patients.id which uses increments (unsigned integer)
            $table->string('bleeding')->nullable();
            $table->string('headache')->nullable();
            $table->string('pain')->nullable();
            $table->string('constipation')->nullable();
            $table->string('urinary_symptoms')->nullable();
            $table->string('vomiting')->nullable();
            $table->string('cough')->nullable();
            $table->string('vaginal_discharge')->nullable();
            $table->string('oedema')->nullable();
            $table->string('haemorrhoids')->nullable();
            $table->date('date');
            $table->string('condition')->nullable();
            $table->string('special_findings_and_remark')->nullable();
            $table->string('pelvic_examination')->nullable();
            $table->string('sp')->nullable();
            $table->string('uter_size')->nullable();
            $table->string('uterus_size')->nullable();
            $table->string('presentation_position')->nullable();
            $table->string('presenting_part_to_brim')->nullable();
            $table->string('foetal_heart')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('antenatal_oedema')->nullable();
            $table->string('urine_sugar')->nullable();
            $table->string('urine_albumin')->nullable();
            $table->decimal('antenatal_weight', 8, 2)->nullable();
            $table->string('remark')->nullable();
            $table->date('next_visit')->nullable();
            $table->string('previous_antenatal_details')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_antenatals');
    }
};
