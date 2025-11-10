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
        // Update the generated migration file
        Schema::table('antenatals', function (Blueprint $table) {
            $table->string('bleeding', 3)->change();
            $table->string('headache', 3)->change();
            $table->string('constipation', 3)->change();
            $table->string('vomiting', 3)->change();
            $table->string('cough', 3)->change();
            $table->string('oedema', 3)->change();
            $table->string('haemorrhoids', 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antenatals', function (Blueprint $table) {
            //
        });
    }
};
