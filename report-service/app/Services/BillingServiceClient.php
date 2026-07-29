<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BillingServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.billing_service.url',
                                'http://127.0.0.1:8003');
    }

    public function getAllTagihans(array $filters = []): array
    {
        $response = Http::timeout(10)
            ->get("{$this->baseUrl}/api/billings", $filters);

        return $response->successful()
            ? $response->json('data.data', [])
            : [];
    }

    public function getTagihansByStatus(string $status): array
    {
        $response = Http::timeout(10)
            ->get("{$this->baseUrl}/api/billings", [
                'status' => $status,
            ]);

        return $response->successful()
            ? $response->json('data.data', [])
            : [];
    }

    public function getSummary(): array
    {
        try {
            $allRes = Http::timeout(5)->get("{$this->baseUrl}/api/billings");
            $lunasRes = Http::timeout(5)->get("{$this->baseUrl}/api/billings", ['status' => 'lunas']);
            $pendingRes = Http::timeout(5)->get("{$this->baseUrl}/api/billings", ['status' => 'pending']);
            $belumBayarRes = Http::timeout(5)->get("{$this->baseUrl}/api/billings", ['status' => 'belum_bayar']);

            $all = $allRes->json('data.total') ?? count($allRes->json('data.data', $allRes->json('data', [])));
            $lunas = $lunasRes->json('data.total') ?? count($lunasRes->json('data.data', $lunasRes->json('data', [])));
            $pending = $pendingRes->json('data.total') ?? count($pendingRes->json('data.data', $pendingRes->json('data', [])));
            $belumBayar = $belumBayarRes->json('data.total') ?? count($belumBayarRes->json('data.data', $belumBayarRes->json('data', [])));

            return [
                'total'       => (int)$all,
                'lunas'       => (int)$lunas,
                'pending'     => (int)$pending,
                'belum_bayar' => (int)$belumBayar,
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0, 'lunas' => 0,
                'pending' => 0, 'belum_bayar' => 0,
            ];
        }
    }
}