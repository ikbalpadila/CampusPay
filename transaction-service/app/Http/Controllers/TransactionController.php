<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\LamportClockService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private LamportClockService $clockService
    ) {}

    /**
     * POST /api/transactions
     * Dipanggil oleh Payment Service setelah pembayaran dikonfirmasi.
     * Mencatat transaksi dengan Lamport Clock untuk ordering.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tagihan_id'   => 'required|integer',
            'mahasiswa_id' => 'required|integer',
            'nomor_va'     => 'nullable|string',
            'nominal'      => 'required|numeric',
            'metode'       => 'required|in:virtual_account,transfer_manual',
            'status'       => 'required|in:success,pending,failed',
        ]);

        // Sync Lamport Clock jika header X-Lamport-Clock dikirim oleh service lain,
        // jika tidak, lakukan increment lokal secara atomic.
        $receivedClock = (int) $request->header('X-Lamport-Clock');
        if ($receivedClock > 0) {
            $clockValue = $this->clockService->sync($receivedClock);
        } else {
            $clockValue = $this->clockService->increment();
        }

        $transaction = Transaction::create([
            'tagihan_id'    => $request->tagihan_id,
            'mahasiswa_id'  => $request->mahasiswa_id,
            'nomor_va'      => $request->nomor_va,
            'nominal'       => $request->nominal,
            'metode'        => $request->metode,
            'lamport_clock' => $clockValue,
            'status'        => $request->status,
            'paid_at'       => $request->status === 'success'
                               ? now()
                               : null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Transaksi berhasil dicatat',
            'data'    => [
                'id'            => $transaction->id,
                'tagihan_id'    => $transaction->tagihan_id,
                'mahasiswa_id'  => $transaction->mahasiswa_id,
                'nominal'       => $transaction->nominal,
                'metode'        => $transaction->metode,
                'lamport_clock' => $transaction->lamport_clock,
                'status'        => $transaction->status,
                'paid_at'       => $transaction->paid_at,
            ],
        ], 201);
    }

    /**
     * GET /api/transactions
     * Ambil semua transaksi diurutkan berdasarkan Lamport Clock.
     * ORDER BY lamport_clock memastikan urutan konsisten.
     */
    public function index(Request $request)
    {
        $query = Transaction::orderBy('lamport_clock', 'asc');

        if ($request->mahasiswa_id) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data'   => $transactions,
            'current_clock' => $this->clockService->current(),
        ]);
    }

    /**
     * GET /api/transactions/my
     * Riwayat transaksi milik mahasiswa yang login.
     */
    public function my(Request $request)
    {
        $mahasiswaId = $request->query('mahasiswa_id');

        $transactions = Transaction::where('mahasiswa_id', $mahasiswaId)
            ->orderBy('lamport_clock', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $transactions,
        ]);
    }

    /**
     * GET /api/transactions/{id}
     * Detail satu transaksi beserta nilai Lamport Clock-nya.
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $transaction,
            'clock_info' => [
                'transaction_clock' => $transaction->lamport_clock,
                'current_clock'     => $this->clockService->current(),
                'keterangan'        => "Transaksi ini adalah event ke-{$transaction->lamport_clock} dalam sistem",
            ],
        ]);
    }

    /**
     * GET /api/transactions/clock
     * Lihat nilai Lamport Clock saat ini.
     * Endpoint ini berguna untuk demonstrasi ke dosen.
     */
    public function clockStatus()
    {
        $current = $this->clockService->current();

        $lastTransactions = Transaction::orderBy('lamport_clock', 'desc')
            ->limit(5)
            ->get(['id', 'tagihan_id', 'nominal', 'lamport_clock',
                   'status', 'created_at']);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'current_lamport_clock' => $current,
                'keterangan'            => 'Nilai clock bertambah 1 setiap ada transaksi baru',
                'last_5_transactions'   => $lastTransactions,
            ],
        ]);
    }

    /**
     * Demo concurrent transactions — untuk presentasi ke dosen.
     * Mensimulasikan 3 transaksi yang masuk hampir bersamaan
     * dan membuktikan Lamport Clock menjamin ordering yang konsisten.
     */
    public function demoLamportClock()
    {
        $results = [];

        // Simulasi 3 transaksi concurrent
        $demoData = [
            ['tagihan_id' => 101, 'mahasiswa_id' => 1,
             'nominal' => 4000000, 'metode' => 'virtual_account',
             'keterangan' => 'Pembayaran UKT Mahasiswa A'],
            ['tagihan_id' => 102, 'mahasiswa_id' => 2,
             'nominal' => 250000, 'metode' => 'virtual_account',
             'keterangan' => 'Pembayaran Praktikum Mahasiswa B'],
            ['tagihan_id' => 103, 'mahasiswa_id' => 3,
             'nominal' => 500000, 'metode' => 'transfer_manual',
             'keterangan' => 'Pembayaran Seminar Mahasiswa C'],
        ];

        foreach ($demoData as $data) {
            $clockValue = $this->clockService->increment();

            $transaction = Transaction::create([
                'tagihan_id'    => $data['tagihan_id'],
                'mahasiswa_id'  => $data['mahasiswa_id'],
                'nominal'       => $data['nominal'],
                'metode'        => $data['metode'],
                'lamport_clock' => $clockValue,
                'status'        => 'success',
                'paid_at'       => now(),
            ]);

            $results[] = [
                'keterangan'    => $data['keterangan'],
                'transaction_id'=> $transaction->id,
                'lamport_clock' => $clockValue,
                'urutan'        => "Transaksi ke-{$clockValue}",
            ];
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Demo Lamport Clock — 3 transaksi concurrent',
            'data'    => $results,
            'kesimpulan' => 'Meskipun 3 transaksi masuk hampir bersamaan, ' .
                           'Lamport Clock memastikan setiap transaksi punya ' .
                           'urutan yang unik dan konsisten',
        ]);
    }
}