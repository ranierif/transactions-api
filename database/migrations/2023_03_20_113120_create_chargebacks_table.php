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
        Schema::create('chargebacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_transaction_id')
                ->references('id')
                ->on('transactions')
                ->constrained()
                ->unique();
            $table->foreignId('reversal_transaction_id')
                ->references('id')
                ->on('transactions')
                ->constrained()
                ->unique();
            $table->string('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chargebacks');
    }
};
