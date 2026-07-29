<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        Semester::create(['nama' => 'Semester 1', 'tahun_ajaran' => '2024/2025', 'is_aktif' => false]);
        Semester::create(['nama' => 'Semester 2', 'tahun_ajaran' => '2024/2025', 'is_aktif' => false]);
        Semester::create(['nama' => 'Semester 3', 'tahun_ajaran' => '2024/2025', 'is_aktif' => false]);
        Semester::create(['nama' => 'Semester 4', 'tahun_ajaran' => '2024/2025', 'is_aktif' => false]);
        Semester::create(['nama' => 'Semester 5', 'tahun_ajaran' => '2024/2025', 'is_aktif' => true]);
        Semester::create(['nama' => 'Semester 6', 'tahun_ajaran' => '2024/2025', 'is_aktif' => false]);
        Semester::create(['nama' => 'Semester 7', 'tahun_ajaran' => '2024/2025', 'is_aktif' => false]);
        Semester::create(['nama' => 'Semester 8', 'tahun_ajaran' => '2024/2025', 'is_aktif' => false]);
    }
}