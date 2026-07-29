<?php

namespace App\Http\Controllers;

use App\Services\BillingServiceClient;
use App\Services\TransactionServiceClient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private BillingServiceClient     $billingClient,
        private TransactionServiceClient $transactionClient
    ) {}

    // GET ringkasan statistik keuangan
    public function summary()
    {
        $billingSummary  = $this->billingClient->getSummary();
        $totalPemasukan  = $this->transactionClient->getTotalPemasukan();
        $lamportStatus   = $this->transactionClient->getLamportClockStatus();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'tagihan' => [
                    'total'       => $billingSummary['total'],
                    'lunas'       => $billingSummary['lunas'],
                    'pending'     => $billingSummary['pending'],
                    'belum_bayar' => $billingSummary['belum_bayar'],
                ],
                'keuangan' => [
                    'total_pemasukan' => $totalPemasukan,
                    'total_pemasukan_formatted' =>
                        'Rp ' . number_format($totalPemasukan, 0, ',', '.'),
                ],
                'distributed_system' => [
                    'current_lamport_clock' =>
                        $lamportStatus['current_lamport_clock'] ?? 0,
                    'total_transactions' =>
                        count($lamportStatus['last_5_transactions'] ?? []),
                ],
            ],
        ]);
    }

    // GET laporan transaksi dengan filter
    public function transactions(Request $request)
    {
        $filters = array_filter([
            'status'       => $request->status,
            'mahasiswa_id' => $request->mahasiswa_id,
        ]);

        $transactions = $this->transactionClient
                             ->getAllTransactions($filters);

        $total = collect($transactions)
            ->where('status', 'success')
            ->sum('nominal');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'transactions'          => $transactions,
                'total_transaksi'       => count($transactions),
                'total_nominal'         => $total,
                'total_nominal_formatted' =>
                    'Rp ' . number_format($total, 0, ',', '.'),
            ],
        ]);
    }

    // GET laporan tunggakan mahasiswa
    public function outstanding()
    {
        $tagihans = $this->billingClient
                         ->getTagihansByStatus('belum_bayar');

        $totalTunggakan = collect($tagihans)->sum('nominal');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'tagihans'                => $tagihans,
                'total_mahasiswa_menunggak' => count($tagihans),
                'total_tunggakan'           => $totalTunggakan,
                'total_tunggakan_formatted' =>
                    'Rp ' . number_format($totalTunggakan, 0, ',', '.'),
            ],
        ]);
    }

    // POST export laporan ke PDF
    public function exportPdf(Request $request)
    {
        $request->validate([
            'type' => 'required|in:transactions,outstanding,summary',
        ]);

        if ($request->type === 'transactions') {
            $data = [
                'title'        => 'Laporan Transaksi Pembayaran',
                'subtitle'     => 'CampusPay — Universitas Muhammadiyah Banten',
                'generated_at' => now()->format('d F Y H:i'),
                'items'        => $this->transactionClient
                                       ->getAllTransactions(['status' => 'success']),
                'type'         => 'transactions',
            ];

        } elseif ($request->type === 'outstanding') {
            $data = [
                'title'        => 'Laporan Tunggakan Mahasiswa',
                'subtitle'     => 'CampusPay — Universitas Muhammadiyah Banten',
                'generated_at' => now()->format('d F Y H:i'),
                'items'        => $this->billingClient
                                       ->getTagihansByStatus('belum_bayar'),
                'type'         => 'outstanding',
            ];

        } else {
            $billingSummary = $this->billingClient->getSummary();
            $data = [
                'title'         => 'Laporan Ringkasan Keuangan',
                'subtitle'      => 'CampusPay — Universitas Muhammadiyah Banten',
                'generated_at'  => now()->format('d F Y H:i'),
                'summary'       => $billingSummary,
                'total_pemasukan' => $this->transactionClient
                                         ->getTotalPemasukan(),
                'type'          => 'summary',
            ];
        }

        $pdf = Pdf::loadView('reports.' . $request->type, $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->download(
            "laporan_{$request->type}_" .
            now()->format('Ymd_His') . '.pdf'
        );
    }

    // POST export PDF dengan dataset custom yang sudah di-filter
    public function exportPdfCustom(Request $request)
    {
        $type  = $request->input('type', 'transactions');
        $items = $request->input('items', []);

        $title = match ($type) {
            'outstanding' => 'Laporan Tunggakan Mahasiswa',
            'summary'     => 'Laporan Ringkasan Keuangan',
            default       => 'Laporan Transaksi Pembayaran',
        };

        $data = [
            'title'        => $title,
            'subtitle'     => 'CampusPay — Universitas Muhammadiyah Banten',
            'generated_at' => now()->format('d F Y H:i'),
            'items'        => $items,
            'type'         => $type,
        ];

        $viewName = in_array($type, ['transactions', 'outstanding', 'summary']) ? $type : 'transactions';

        $pdf = Pdf::loadView('reports.' . $viewName, $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->download("laporan_{$type}_" . now()->format('Ymd_His') . '.pdf');
    }
}