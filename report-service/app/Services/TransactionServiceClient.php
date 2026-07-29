<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TransactionServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.transaction_service.url',
                                'http://127.0.0.1:8006');
    }

    public function getAllTransactions(array $filters = []): array
    {
        $response = Http::timeout(10)
            ->get("{$this->baseUrl}/api/transactions", $filters);

        return $response->successful()
            ? $response->json('data.data', [])
            : [];
    }

    public function getLamportClockStatus(): array
    {
        $response = Http::timeout(10)
            ->get("{$this->baseUrl}/api/transactions/clock");

        return $response->successful()
            ? $response->json('data', [])
            : [];
    }

    public function getTotalPemasukan(): float
    {
        $transactions = $this->getAllTransactions(['status' => 'success']);

        return collect($transactions)->sum('nominal');
    }
}