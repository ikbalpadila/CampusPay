<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config(
            'services.transaction_service.url',
            'http://127.0.0.1:8006'
        );
    }

    public function recordTransaction(array $data): ?array
    {
        try {
            // Increment local clock in payment-service
            $localClock = \Illuminate\Support\Facades\Cache::increment('lamport_clock');

            Log::info('TRANSACTION REQUEST', array_merge($data, ['_local_clock' => $localClock]));

            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Lamport-Clock' => $localClock
                ])
                ->acceptJson()
                ->post(
                    "{$this->baseUrl}/api/transactions",
                    $data
                );

            Log::info('TRANSACTION RESPONSE', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if (!$response->successful()) {

                Log::error('TRANSACTION FAILED', [
                    'url'     => "{$this->baseUrl}/api/transactions",
                    'payload' => $data,
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                ]);

                return null;
            }

            return $response->json('data');

        } catch (\Throwable $e) {

            Log::error(
                'TRANSACTION SERVICE ERROR: '
                . $e->getMessage(),
                [
                    'url'     => "{$this->baseUrl}/api/transactions",
                    'payload' => $data,
                    'exception' => $e
                ]
            );

            return null;
        }
    }
}