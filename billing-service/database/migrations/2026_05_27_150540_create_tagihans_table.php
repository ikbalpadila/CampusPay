<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('mahasiswa_nim', 20);
            $table->string('mahasiswa_nama', 150);
            $table->foreignId('payment_type_id')
                  ->constrained('payment_types');
            $table->unsignedBigInteger('semester_id');
            $table->string('semester_nama', 50);
            $table->decimal('nominal', 15, 2);
            $table->date('jatuh_tempo');
            $table->enum('status', [
                'belum_bayar',
                'pending',
                'lunas'
            ])->default('belum_bayar');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};