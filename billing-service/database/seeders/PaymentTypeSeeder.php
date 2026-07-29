<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['nama' => 'UKT',          'deskripsi' => 'Uang Kuliah Tunggal'],
            ['nama' => 'Praktikum',    'deskripsi' => 'Biaya praktikum semester'],
            ['nama' => 'Seminar',      'deskripsi' => 'Biaya seminar proposal/hasil'],
            ['nama' => 'Wisuda',       'deskripsi' => 'Biaya pendaftaran wisuda'],
            ['nama' => 'Perpustakaan', 'deskripsi' => 'Denda atau biaya perpustakaan'],
            ['nama' => 'Denda',        'deskripsi' => 'Denda keterlambatan pembayaran'],
        ];

        foreach ($types as $type) {
            PaymentType::create($type);
        }
    }
}