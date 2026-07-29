<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BillingServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.billing_service.url',
                                'http://localhost:8003');
    }

    public function getTagihan(int $tagihanId): ?array
    {
        $response = Http::get(
            "{$this->baseUrl}/api/billings/{$tagihanId}"
        );

        return $response->successful()
            ? $response->json('data')
            : null;
    }

    public function updateStatus(
        int    $tagihanId,
        string $status,
        string $catatan = null
    ): bool {
        $response = Http::put(
            "{$this->baseUrl}/api/billings/{$tagihanId}/status",
            array_filter([
                'status'  => $status,
                'catatan' => $catatan,
            ])
        );

        return $response->successful();
    }
}