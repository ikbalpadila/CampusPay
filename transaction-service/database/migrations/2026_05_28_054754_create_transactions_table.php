<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('nomor_va', 20)->nullable();
            $table->decimal('nominal', 15, 2);
            $table->enum('metode', [
                'virtual_account',
                'transfer_manual'
            ]);
            $table->unsignedBigInteger('lamport_clock');
            $table->enum('status', [
                'success',
                'pending',
                'failed'
            ])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};