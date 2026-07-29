<?php

namespace App\Http\Controllers;

use App\Events\TagihanDibuat;
use App\Models\PaymentType;
use App\Models\Tagihan;
use App\Services\StudentServiceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Jobs\GenerateTagihanMassalJob;

class TagihanController extends Controller
{

    public function __construct(
        private StudentServiceClient $studentClient
    ) {}

    // ── Private helper: kirim notifikasi tagihan baru ─────────────────
    private function kirimNotifikasiTagihanBaru(Tagihan $tagihan): void
{
    try {

        Http::timeout(10)->post(
            env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8007')
            . '/api/notifications/send',
            [
                'user_id' => $tagihan->mahasiswa_id,
                'type'    => 'tagihan_baru',
                'title'   => '🔔 Tagihan Baru',
                'message' =>
                    'Tagihan ' .
                    ($tagihan->paymentType->nama ?? 'Pembayaran') .
                    ' semester ' .
                    $tagihan->semester_nama .
                    ' sebesar Rp ' .
                    number_format(
                        $tagihan->nominal,
                        0,
                        ',',
                        '.'
                    ) .
                    ' telah dibuat.',
            ]
        );

        Log::info(
            'Notifikasi tagihan baru terkirim',
            [
                'mahasiswa_id' => $tagihan->mahasiswa_id,
                'tagihan_id'   => $tagihan->id,
            ]
        );

    } catch (\Exception $e) {

        Log::error(
            'Gagal kirim notif tagihan baru: ' .
            $e->getMessage()
        );
    }
}

private function kirimNotifikasiTagihanDisetujui(Tagihan $tagihan): void
{
    try {

        Http::timeout(10)->post(
            env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8007')
            . '/api/notifications/send',
            [
                'user_id' => $tagihan->mahasiswa_id,
                'type'    => 'tagihan_disetujui',
                'title'   => '✅ Pembayaran Disetujui',
                'message' =>
                    'Pembayaran tagihan ' .
                    ($tagihan->paymentType->nama ?? '-') .
                    ' telah diverifikasi admin dan dinyatakan LUNAS.',
            ]
        );

        Log::info(
            'Notif pembayaran disetujui terkirim',
            [
                'tagihan_id' => $tagihan->id,
            ]
        );

    } catch (\Exception $e) {

        Log::error(
            'Gagal kirim notif lunas: ' .
            $e->getMessage()
        );
    }
}

    // ── GET tagihan per mahasiswa ──────────────────────────────────────
    public function getByMahasiswa(Request $request)
    {
        $mahasiswaId = $request->query('mahasiswa_id');

        $tagihans = Tagihan::with('paymentType')
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $tagihans,
        ]);
    }

    // ── GET semua tagihan (admin) ──────────────────────────────────────
    public function index(Request $request)
    {
        $query = Tagihan::with('paymentType');

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }
        if ($request->mahasiswa_id) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        $tagihans = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data'   => $tagihans,
        ]);
    }

    // ── GET detail tagihan by ID ───────────────────────────────────────
    public function show($id)
    {
        $tagihan = Tagihan::with('paymentType')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $tagihan,
        ]);
    }

    // ── POST buat tagihan individual ──────────────────────────────────
    public function store(Request $request)
    {
        Log::info('STORE TAGIHAN DIPANGGIL');
        
        $request->validate([
            'mahasiswa_id'    => 'required|integer',
            'payment_type_id' => 'required|exists:payment_types,id',
            'semester_id'     => 'required|integer',
            'nominal'         => 'required|numeric|min:1000',
            'jatuh_tempo'     => 'required|date',
        ]);

        // Ambil data mahasiswa dari Student Service
        try {
            $mahasiswa = $this->studentClient
                              ->getMahasiswaById($request->mahasiswa_id);
        } catch (\Exception $e) {
            $mahasiswa = null;
        }

        if (!$mahasiswa) {
            // Fallback data mahasiswa
            $mahasiswa = [
                'id'   => $request->mahasiswa_id,
                'nim'  => '0000000000',
                'nama' => 'Mahasiswa ' . $request->mahasiswa_id,
            ];
        }

        // Ambil data semester dari Student Service
        try {
            $semesters    = $this->studentClient->getSemesters();
            $semester     = collect($semesters)
                              ->firstWhere('id', $request->semester_id);
            $semesterNama = $semester['nama']
                            ?? 'Semester ' . $request->semester_id;
        } catch (\Exception $e) {
            $semesterNama = 'Semester ' . $request->semester_id;
        }

        $tagihan = Tagihan::create([
            'mahasiswa_id'    => $mahasiswa['id'],
            'mahasiswa_nim'   => $mahasiswa['nim'],
            'mahasiswa_nama'  => $mahasiswa['nama'],
            'payment_type_id' => $request->payment_type_id,
            'semester_id'     => $request->semester_id,
            'semester_nama'   => $semesterNama,
            'nominal'         => $request->nominal,
            'jatuh_tempo'     => $request->jatuh_tempo,
            'catatan'         => $request->catatan,
        ]);

        // Kirim notifikasi tagihan baru ke mahasiswa
        $this->kirimNotifikasiTagihanBaru(
            $tagihan->load('paymentType')
        );

        // Publish event ke RabbitMQ
        try {
            event(new TagihanDibuat($tagihan));
        } catch (\Exception $e) {
            Log::warning('Event TagihanDibuat gagal: ' . $e->getMessage());
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Tagihan berhasil dibuat',
            'data'    => $tagihan->load('paymentType'),
        ], 201);
    }

    // ── POST generate tagihan massal ──────────────────────────────────
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'semester_id'     => 'required|integer',
            'nominal'         => 'required|numeric|min:1000',
            'jatuh_tempo'     => 'required|date',
        ]);

        GenerateTagihanMassalJob::dispatch([
            'payment_type_id' => $request->payment_type_id,
            'semester_id'     => $request->semester_id,
            'nominal'         => $request->nominal,
            'jatuh_tempo'     => $request->jatuh_tempo,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Proses generate tagihan massal telah dijadwalkan di background queue',
        ], 202);
    }
    // ── PUT update status tagihan (dipanggil Payment Service) ─────────
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:belum_bayar,pending,lunas',
    ]);

    $tagihan = Tagihan::with('paymentType')
        ->findOrFail($id);

    $statusLama = $tagihan->status;

    $tagihan->update([
        'status'  => $request->status,
        'catatan' => $request->catatan,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Notifikasi saat lunas
    |--------------------------------------------------------------------------
    */

    if (
        $statusLama !== 'lunas'
        && $request->status === 'lunas'
    ) {

        $this->kirimNotifikasiTagihanDisetujui(
            $tagihan
        );
    }

    return response()->json([
        'status'  => 'success',
        'message' => 'Status tagihan berhasil diupdate',
        'data'    => $tagihan,
    ]);
}
}