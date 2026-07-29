<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class StudentServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.student_service.url',
                                 'http://localhost:8002');
    }

    // Ambil mahasiswa aktif berdasarkan semester
    public function getMahasiswaAktifBySemester(int $semesterId): array
    {
        $response = Http::get(
            "{$this->baseUrl}/api/students/semester/{$semesterId}/aktif"
        );

        if ($response->successful()) {
            return $response->json('data', []);
        }

        return [];
    }

    // Ambil detail mahasiswa by ID
    public function getMahasiswaById(int $id): ?array
    {
        $response = Http::get("{$this->baseUrl}/api/students/{$id}");

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }

    // Ambil semester aktif
    public function getSemesters(): array
    {
        $response = Http::get("{$this->baseUrl}/api/semesters");

        if ($response->successful()) {
            return $response->json('data', []);
        }

        return [];
    }
}