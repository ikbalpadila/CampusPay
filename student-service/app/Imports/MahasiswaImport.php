<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Semester;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $semester = Semester::where(
            'nama',
            $row['semester']
        )->first();

        if (! $semester) {
            return null;
        }

        $mahasiswa = Mahasiswa::create([
            'nim'         => $row['nim'],
            'nama'        => $row['nama'],
            'prodi'       => $row['prodi'],
            'fakultas'    => $row['fakultas'],
            'semester_id' => $semester->id,
            'kelas'       => $row['kelas'],
            'status_aktif'=> true,
        ]);

        try {

            Http::post(
                env('AUTH_SERVICE_URL')
                . '/api/register-mahasiswa',
                [
                    'nim'  => $mahasiswa->nim,
                    'nama' => $mahasiswa->nama,
                ]
            );

        } catch (\Exception $e) {

            logger()->error(
                'Gagal membuat akun auth mahasiswa',
                [
                    'nim' => $mahasiswa->nim,
                    'error' => $e->getMessage(),
                ]
            );
        }

        return $mahasiswa;
    }
}