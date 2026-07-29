<?php

namespace App\Http\Controllers;

use App\Jobs\PublishPaymentEvent;
use App\Models\ManualPaymentSubmission;
use App\Models\PaymentLog;
use App\Services\BillingServiceClient;
use App\Services\TransactionServiceClient;
use App\Services\VAServiceClient;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private BillingServiceClient     $billingClient,
        private VAServiceClient          $vaClient,
        private TransactionServiceClient $transactionClient
    ) {}

    // POST - Mahasiswa inisiasi pembayaran → generate VA
    public function initiate(Request $request)
    {
        $request->validate([
            'tagihan_id'        => 'required|integer',
            'mahasiswa_id'      => 'required|integer',
            'nominal'           => 'nullable|numeric',
            'status'            => 'nullable|string',
            'payment_type_nama' => 'nullable|string',
            'semester_nama'     => 'nullable|string',
            'jatuh_tempo'       => 'nullable|string',
        ]);

        $tagihan = null;
        if ($request->has(['nominal', 'status'])) {
            $tagihan = [
                'id'            => $request->tagihan_id,
                'nominal'       => $request->nominal,
                'status'        => $request->status,
                'payment_type'  => [
                    'nama' => $request->input('payment_type_nama', 'Pembayaran')
                ],
                'semester_nama' => $request->input('semester_nama'),
                'jatuh_tempo'   => $request->input('jatuh_tempo'),
            ];
        } else {
            // Ambil detail tagihan dari Billing Service
            $tagihan = $this->billingClient
                            ->getTagihan($request->tagihan_id);
        }

        if (!$tagihan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tagihan tidak ditemukan',
            ], 404);
        }

        if ($tagihan['status'] !== 'belum_bayar') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tagihan sudah dibayar atau sedang diproses',
                'current_status' => $tagihan['status'],
            ], 422);
        }

        // Generate VA dari VA Service
        $va = $this->vaClient->generateVA(
            $request->tagihan_id,
            $request->mahasiswa_id,
            $tagihan['nominal']
        );

        if (!$va) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal generate Virtual Account',
            ], 500);
        }

        // Log event
        PaymentLog::create([
            'tagihan_id'  => $request->tagihan_id,
            'event_type'  => 'va_generated',
            'payload'     => [
                'tagihan_id'   => $request->tagihan_id,
                'mahasiswa_id' => $request->mahasiswa_id,
                'nomor_va'     => $va['nomor_va'],
                'nominal'      => $tagihan['nominal'],
            ],
            'processed_by' => 'system',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Virtual Account berhasil dibuat',
            'data'    => [
                'nomor_va'   => $va['nomor_va'],
                'nominal'    => $tagihan['nominal'],
                'expired_at' => $va['expired_at'],
                'tagihan'    => [
                    'id'           => $tagihan['id'],
                    'nama'         => $tagihan['payment_type']['nama']
                                      ?? 'Pembayaran',
                    'semester'     => $tagihan['semester_nama'],
                    'jatuh_tempo'  => $tagihan['jatuh_tempo'],
                ],
                'cara_bayar' => 'Transfer ke nomor VA di atas melalui ATM/Mobile Banking manapun',
            ],
        ]);
    }

    // POST - Terima konfirmasi dari VA Service (simulasi bank callback)
    public function callback(Request $request)
    {
        $request->validate([
            'nomor_va'   => 'required|string',
            'tagihan_id' => 'required|integer',
            'nominal'    => 'required|numeric',
            'status'     => 'required|string',
        ]);

        if ($request->status !== 'success') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pembayaran gagal dari bank',
            ], 422);
        }

        // Ambil tagihan dari Billing Service
        $tagihan = $this->billingClient->getTagihan($request->tagihan_id);

        if (!$tagihan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tagihan tidak ditemukan',
            ], 404);
        }

        // Update status tagihan → LUNAS
        $this->billingClient->updateStatus(
            $request->tagihan_id,
            'lunas',
            'Pembayaran via Virtual Account ' . $request->nomor_va
        );

        // Catat transaksi di Transaction Service (opsional, tidak blocking)
        $transactionId = null;
        $lamportClock  = null;

        try {
            $transaction = $this->transactionClient->recordTransaction([
                'tagihan_id'   => $request->tagihan_id,
                'mahasiswa_id' => $tagihan['mahasiswa_id'],
                'nomor_va'     => $request->nomor_va,
                'nominal'      => $request->nominal,
                'metode'       => 'virtual_account',
                'status'       => 'success',
            ]);
            if ($transaction) {
                $transactionId = $transaction['id'] ?? null;
                $lamportClock  = $transaction['lamport_clock'] ?? null;
            } else {
                \Illuminate\Support\Facades\Log::error(
                    'Gagal mencatat transaksi Virtual Account ke Transaction Service: response null',
                    [
                        'tagihan_id' => $request->tagihan_id,
                        'mahasiswa_id' => $tagihan['mahasiswa_id'],
                        'nomor_va' => $request->nomor_va,
                    ]
                );
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning(
                'Transaction Service tidak bisa dihubungi: ' . $e->getMessage()
            );
        }

        // Simpan payment log
        PaymentLog::create([
            'transaction_id' => $transactionId,
            'tagihan_id'     => $request->tagihan_id,
            'event_type'     => 'pembayaran_berhasil',
            'payload'        => [
                'nomor_va'      => $request->nomor_va,
                'nominal'       => $request->nominal,
                'mahasiswa_id'  => $tagihan['mahasiswa_id'],
                'mahasiswa_nim' => $tagihan['mahasiswa_nim'],
                'lamport_clock' => $lamportClock,
            ],
            'processed_by'   => 'system',
        ]);

        // Publish event ke RabbitMQ (opsional, tidak blocking)
        try {
            PublishPaymentEvent::dispatch('pembayaran_berhasil', [
                'tagihan_id'     => $request->tagihan_id,
                'mahasiswa_id'   => $tagihan['mahasiswa_id'],
                'mahasiswa_nim'  => $tagihan['mahasiswa_nim'],
                'mahasiswa_nama' => $tagihan['mahasiswa_nama'],
                'nominal'        => $request->nominal,
                'nomor_va'       => $request->nomor_va,
                'transaction_id' => $transactionId,
                'jenis'          => $tagihan['payment_type']['nama'] ?? 'Pembayaran',
                'semester'       => $tagihan['semester_nama'],
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning(
                'RabbitMQ tidak bisa dihubungi: ' . $e->getMessage()
            );
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Pembayaran berhasil dikonfirmasi',
            'data'    => [
                'tagihan_id'     => $request->tagihan_id,
                'transaction_id' => $transactionId,
                'status'         => 'lunas',
            ],
        ]);
    }

    // POST - Mahasiswa upload bukti transfer manual
    public function uploadBukti(Request $request)
    {
        $request->validate([
            'tagihan_id'   => 'required|integer',
            'mahasiswa_id' => 'required|integer',
            'file'         => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $tagihan = $this->billingClient
                        ->getTagihan($request->tagihan_id);

        if (!$tagihan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tagihan tidak ditemukan',
            ], 404);
        }

        // Simpan file bukti
        $path = $request->file('file')->store(
            'bukti-transfer', 'public'
        );

        // Buat submission
        $submission = ManualPaymentSubmission::create([
            'tagihan_id'     => $request->tagihan_id,
            'mahasiswa_id'   => $request->mahasiswa_id,
            'mahasiswa_nim'  => $tagihan['mahasiswa_nim'],
            'mahasiswa_nama' => $tagihan['mahasiswa_nama'],
            'nominal'        => $tagihan['nominal'],
            'bukti_transfer' => $path,
            'status'         => 'pending',
        ]);

        // Update status tagihan → PENDING
        $this->billingClient->updateStatus(
            $request->tagihan_id,
            'pending',
            'Menunggu verifikasi admin'
        );

        // Log event
        PaymentLog::create([
            'tagihan_id'  => $request->tagihan_id,
            'event_type'  => 'bukti_diupload',
            'payload'     => [
                'submission_id' => $submission->id,
                'mahasiswa_id'  => $request->mahasiswa_id,
                'nominal'       => $tagihan['nominal'],
            ],
            'processed_by' => 'mahasiswa_' . $request->mahasiswa_id,
        ]);

        // Notify admin via RabbitMQ
        PublishPaymentEvent::dispatch('pembayaran_pending', [
            'submission_id'  => $submission->id,
            'tagihan_id'     => $request->tagihan_id,
            'mahasiswa_id'   => $request->mahasiswa_id,
            'mahasiswa_nim'  => $tagihan['mahasiswa_nim'],
            'mahasiswa_nama' => $tagihan['mahasiswa_nama'],
            'nominal'        => $tagihan['nominal'],
            'jenis'          => $tagihan['payment_type']['nama'] ?? 'Pembayaran',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Bukti transfer berhasil diupload, menunggu verifikasi admin',
            'data'    => [
                'submission_id' => $submission->id,
                'status'        => 'pending',
            ],
        ]);
    }

    // GET - Daftar submission pending untuk admin
    public function getPending()
    {
        $submissions = ManualPaymentSubmission::where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $submissions,
        ]);
    }

    // POST - Admin verifikasi pembayaran manual
    public function manualVerify(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer',
            'action'        => 'required|in:approve,reject',
            'catatan'       => 'nullable|string',
            'admin_id'      => 'required|integer',
        ]);

        $submission = ManualPaymentSubmission::findOrFail(
            $request->submission_id
        );

        if ($submission->status !== 'pending') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Submission sudah diproses sebelumnya',
            ], 422);
        }

        if ($request->action === 'approve') {

            // Update submission
            $submission->update([
                'status'        => 'approved',
                'catatan_admin' => $request->catatan,
                'reviewed_by'   => $request->admin_id,
                'reviewed_at'   => now(),
            ]);

            // Update tagihan → LUNAS
            $this->billingClient->updateStatus(
                $submission->tagihan_id,
                'lunas',
                'Disetujui oleh admin'
            );

            // Catat transaksi
            $transactionId = null;
            $lamportClock  = null;

            try {
                $transaction = $this->transactionClient->recordTransaction([
                    'tagihan_id'   => $submission->tagihan_id,
                    'mahasiswa_id' => $submission->mahasiswa_id,
                    'nominal'      => $submission->nominal,
                    'metode'       => 'transfer_manual',
                    'status'       => 'success',
                ]);
                if ($transaction) {
                    $transactionId = $transaction['id'] ?? null;
                    $lamportClock  = $transaction['lamport_clock'] ?? null;
                } else {
                    \Illuminate\Support\Facades\Log::error(
                        'Gagal mencatat transaksi Manual Transfer ke Transaction Service: response null',
                        [
                            'tagihan_id' => $submission->tagihan_id,
                            'mahasiswa_id' => $submission->mahasiswa_id,
                        ]
                    );
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning(
                    'Transaction Service tidak bisa dihubungi: ' . $e->getMessage()
                );
            }

            // Log
            PaymentLog::create([
                'transaction_id' => $transactionId,
                'tagihan_id'     => $submission->tagihan_id,
                'event_type'     => 'pembayaran_berhasil',
                'payload'        => [
                    'submission_id' => $submission->id,
                    'admin_id'      => $request->admin_id,
                    'metode'        => 'transfer_manual',
                    'lamport_clock' => $lamportClock,
                ],
                'processed_by' => 'admin_' . $request->admin_id,
            ]);

            // Publish event berhasil ke RabbitMQ
            PublishPaymentEvent::dispatch('pembayaran_berhasil', [
                'tagihan_id'     => $submission->tagihan_id,
                'mahasiswa_id'   => $submission->mahasiswa_id,
                'mahasiswa_nim'  => $submission->mahasiswa_nim,
                'mahasiswa_nama' => $submission->mahasiswa_nama,
                'nominal'        => $submission->nominal,
                'metode'         => 'transfer_manual',
                'transaction_id' => $transactionId,
            ]);

            $message = 'Pembayaran berhasil disetujui';

        } else {

            // REJECT
            $submission->update([
                'status'        => 'rejected',
                'catatan_admin' => $request->catatan,
                'reviewed_by'   => $request->admin_id,
                'reviewed_at'   => now(),
            ]);

            // Kembalikan status tagihan → BELUM BAYAR
            $this->billingClient->updateStatus(
                $submission->tagihan_id,
                'belum_bayar',
                'Ditolak: ' . $request->catatan
            );

            // Log
            PaymentLog::create([
                'tagihan_id'  => $submission->tagihan_id,
                'event_type'  => 'pembayaran_ditolak',
                'payload'     => [
                    'submission_id' => $submission->id,
                    'alasan'        => $request->catatan,
                    'admin_id'      => $request->admin_id,
                ],
                'processed_by' => 'admin_' . $request->admin_id,
            ]);

            // Publish event ditolak ke RabbitMQ
            PublishPaymentEvent::dispatch('pembayaran_ditolak', [
                'tagihan_id'     => $submission->tagihan_id,
                'mahasiswa_id'   => $submission->mahasiswa_id,
                'mahasiswa_nim'  => $submission->mahasiswa_nim,
                'mahasiswa_nama' => $submission->mahasiswa_nama,
                'alasan'         => $request->catatan,
            ]);

            $message = 'Pembayaran ditolak';
        }

        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $submission->fresh(),
        ]);
    }
}