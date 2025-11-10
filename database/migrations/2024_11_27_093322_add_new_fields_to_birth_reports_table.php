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
        Schema::table('birth_reports', function (Blueprint $table) {
            $table->string('child_name')->nullable()->after('description');
            $table->enum('gender', ['Male', 'Female'])->nullable()->after('child_name');
            $table->decimal('weight', 5, 2)->nullable()->after('gender');
            $table->string('parent_name')->nullable()->after('weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birth_reports', function (Blueprint $table) {
            $table->dropColumn(['child_name', 'gender', 'weight', 'parent_name']);
        });
    }
};
