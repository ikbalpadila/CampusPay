<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lamport_clock', function (Blueprint $table) {
            $table->integer('id')->primary()->default(1);
            $table->unsignedBigInteger('value')->default(0);
        });

        // Insert nilai awal clock = 0
        DB::table('lamport_clock')->insert([
            'id'    => 1,
            'value' => 0,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('lamport_clock');
    }
};