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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payer_id')
                ->references('id')
                ->on('users')
                ->constrained();
            $table->foreignId('payee_id')
                ->references('id')
                ->on('users')
                ->constrained();
            $table->foreignId('status_id')
                ->references('id')
                ->on('statuses')
                ->constrained();
            $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
