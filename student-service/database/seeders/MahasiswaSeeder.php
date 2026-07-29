<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nim' => '2021010001', 'nama' => 'Budi Santoso',    'prodi' => 'Teknik Informatika', 'fakultas' => 'Fakultas Teknik', 'semester_id' => 5, 'kelas' => 'TI-5A'],
            ['nim' => '2021010002', 'nama' => 'Siti Rahayu',     'prodi' => 'Teknik Informatika', 'fakultas' => 'Fakultas Teknik', 'semester_id' => 5, 'kelas' => 'TI-5A'],
            ['nim' => '2021010003', 'nama' => 'Ahmad Fauzi',     'prodi' => 'Teknik Informatika', 'fakultas' => 'Fakultas Teknik', 'semester_id' => 5, 'kelas' => 'TI-5B'],
            ['nim' => '2022010001', 'nama' => 'Dewi Lestari',    'prodi' => 'Teknik Informatika', 'fakultas' => 'Fakultas Teknik', 'semester_id' => 3, 'kelas' => 'TI-3A'],
            ['nim' => '2022010002', 'nama' => 'Rizky Pratama',   'prodi' => 'Teknik Informatika', 'fakultas' => 'Fakultas Teknik', 'semester_id' => 3, 'kelas' => 'TI-3A'],
        ];

        foreach ($data as $d) {
            Mahasiswa::create($d);
        }
    }
}