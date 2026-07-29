<?php

namespace Database\Seeders;

use App\Models\MahasiswaAuth;
use Illuminate\Database\Seeder;

class MahasiswaAuthSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswas = [
            [
                'mahasiswa_id' => 1,
                'nim'          => '2021010001',
                'nama'         => 'Budi Santoso',
                'email'        => 'budi@mahasiswa.umb.ac.id',
                'password'     => 'password123',
            ],
            [
                'mahasiswa_id' => 2,
                'nim'          => '2021010002',
                'nama'         => 'Siti Rahayu',
                'email'        => 'siti@mahasiswa.umb.ac.id',
                'password'     => 'password123',
            ],
            [
                'mahasiswa_id' => 3,
                'nim'          => '2021010003',
                'nama'         => 'Ahmad Fauzi',
                'email'        => 'ahmad@mahasiswa.umb.ac.id',
                'password'     => 'password123',
            ],
        ];

        foreach ($mahasiswas as $mhs) {
            MahasiswaAuth::create($mhs);
        }
    }
}