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
        Schema::create('maternity', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('patient_id');
            $table->string('maternity_number', 160)->unique();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('bp')->nullable();
            $table->string('temperature')->nullable();
            $table->string('pulse')->nullable();
            $table->string('respiration')->nullable();
            $table->string('oxygen_saturation')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('notes')->nullable();
            $table->datetime('appointment_date');
            $table->unsignedInteger('case_id')->nullable();
            $table->boolean('is_old_patient')->nullable()->default(false);
            $table->boolean('is_antenatal')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->double('standard_charge');
            $table->tinyInteger('payment_mode');
            $table->string('currency_symbol')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('change')->nullable();
            $table->unsignedInteger('charge_id')->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->boolean('served')->default(false);
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('case_id')->references('id')->on('patient_cases')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('doctor_id')->references('id')->on('doctors')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maternity');
    }
};
