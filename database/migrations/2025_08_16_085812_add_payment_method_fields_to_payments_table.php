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
        Schema::table('payments', function (Blueprint $table) {
            $table->tinyInteger('payment_type')->default(0)->comment('0=Cash, 1=Cheque, 2=Other');
            $table->decimal('paid_amount', 10, 2)->nullable()->comment('Amount actually paid by customer');
            $table->decimal('change_amount', 10, 2)->default(0)->comment('Change given to customer');
            $table->text('payment_note')->nullable()->comment('Optional payment note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'paid_amount', 'change_amount', 'payment_note']);
        });
    }
};
