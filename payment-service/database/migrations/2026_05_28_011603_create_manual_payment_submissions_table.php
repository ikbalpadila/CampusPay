<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_payment_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('mahasiswa_nim', 20);
            $table->string('mahasiswa_nama', 150);
            $table->decimal('nominal', 15, 2);
            $table->string('bukti_transfer', 255);
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_payment_submissions');
    }
};