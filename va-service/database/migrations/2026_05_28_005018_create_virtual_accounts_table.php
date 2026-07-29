<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id')->unique();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('nomor_va', 20)->unique();
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['aktif', 'digunakan', 'expired'])
                  ->default('aktif');
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_accounts');
    }
};