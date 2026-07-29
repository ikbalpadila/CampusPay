<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VAServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.va_service.url',
                                'http://localhost:8005');
    }

    public function generateVA(
        int   $tagihanId,
        int   $mahasiswaId,
        float $nominal
    ): ?array {
        $response = Http::post("{$this->baseUrl}/api/va/generate", [
            'tagihan_id'   => $tagihanId,
            'mahasiswa_id' => $mahasiswaId,
            'nominal'      => $nominal,
        ]);

        return $response->successful()
            ? $response->json('data')
            : null;
    }

    public function validateVA(
        string $nomorVA,
        float  $nominal
    ): array {
        $response = Http::post("{$this->baseUrl}/api/va/validate", [
            'nomor_va' => $nomorVA,
            'nominal'  => $nominal,
        ]);

        return [
            'valid' => $response->successful(),
            'data'  => $response->json('data'),
        ];
    }
}