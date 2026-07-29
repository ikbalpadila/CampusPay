<?php

namespace App\Http\Controllers;

use App\Services\VAGeneratorService;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    public function __construct(
        private VAGeneratorService $vaService
    ) {}

    // POST generate VA baru
    // Dipanggil oleh Payment Service
    public function generate(Request $request)
    {
        $request->validate([
            'tagihan_id'   => 'required|integer',
            'mahasiswa_id' => 'required|integer',
            'nominal'      => 'required|numeric|min:1000',
        ]);

        $va = $this->vaService->generate(
            $request->tagihan_id,
            $request->mahasiswa_id,
            $request->nominal
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Virtual Account berhasil digenerate',
            'data'    => [
                'id'           => $va->id,
                'nomor_va'     => $va->nomor_va,
                'nominal'      => $va->nominal,
                'status'       => $va->status,
                'expired_at'   => $va->expired_at,
                'tagihan_id'   => $va->tagihan_id,
                'mahasiswa_id' => $va->mahasiswa_id,
            ],
        ], 201);
    }

    // POST validasi VA
    // Dipanggil oleh Payment Service saat konfirmasi
    public function validate(Request $request)
    {
        $request->validate([
            'nomor_va' => 'required|string',
            'nominal'  => 'required|numeric',
        ]);

        $result = $this->vaService->validate(
            $request->nomor_va,
            $request->nominal
        );

        if (!$result['valid']) {
            return response()->json([
                'status'  => 'error',
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $result['data'],
        ]);
    }

    // GET detail VA by nomor
    public function show(string $nomorVA)
    {
        $va = VirtualAccount::where('nomor_va', $nomorVA)->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data'   => $va,
        ]);
    }

    // POST simulasi konfirmasi pembayaran dari bank
    // Endpoint ini mensimulasikan callback bank
    public function simulatePayment(Request $request)
    {
        $request->validate([
            'nomor_va' => 'required|string',
            'nominal'  => 'required|numeric',
        ]);

        $result = $this->vaService->validate(
            $request->nomor_va,
            $request->nominal
        );

        if (!$result['valid']) {
            return response()->json([
                'status'  => 'error',
                'message' => $result['message'],
            ], 422);
        }

        $va = $result['data'];

        // Update status VA menjadi digunakan
        $va->update(['status' => 'digunakan']);

        // Kirim callback ke Payment Service
        $callbackResult = $this->sendCallbackToPaymentService($va);

        return response()->json([
            'status'  => 'success',
            'message' => 'Simulasi pembayaran berhasil',
            'data'    => [
                'nomor_va'   => $va->nomor_va,
                'tagihan_id' => $va->tagihan_id,
                'nominal'    => $va->nominal,
                'callback'   => $callbackResult,
            ],
        ]);
    }

    // Kirim callback ke Payment Service
    private function sendCallbackToPaymentService(
        VirtualAccount $va
    ): string {
        try {
            $response = \Illuminate\Support\Facades\Http::post(
                config('services.payment_service.url') .
                '/api/payments/callback',
                [
                    'nomor_va'   => $va->nomor_va,
                    'tagihan_id' => $va->tagihan_id,
                    'nominal'    => $va->nominal,
                    'status'     => 'success',
                ]
            );

            return $response->successful() ? 'sent' : 'failed';
        } catch (\Exception $e) {
            return 'failed: ' . $e->getMessage();
        }
    }

    public function getByTagihan($tagihanId)
{
    $va = VirtualAccount::where('tagihan_id', $tagihanId)
        ->where('status', 'aktif')
        ->first();

    if (!$va) {
        return response()->json([
            'status' => 'error',
            'message' => 'VA tidak ditemukan',
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'id' => $va->id,
            'nomor_va' => $va->nomor_va,
            'nominal' => $va->nominal,
            'status' => $va->status,
            'expired_at' => $va->expired_at,
            'tagihan_id' => $va->tagihan_id,
            'mahasiswa_id' => $va->mahasiswa_id,
        ],
    ]);
    }
}