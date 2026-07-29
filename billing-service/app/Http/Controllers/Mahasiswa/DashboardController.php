<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
{
    
    $mahasiswa = session('mahasiswa');

    if (!$mahasiswa) {
        return redirect()
            ->route('mahasiswa.login');
    }

    $mahasiswaId = $mahasiswa['id'];

        $tagihans = Tagihan::where('mahasiswa_id', $mahasiswaId)
                           ->with('paymentType')
                           ->orderBy('created_at', 'desc')
                           ->get();

        $totalTagihan   = $tagihans->count();
        $tagihanLunas   = $tagihans->where('status', 'lunas')->count();
        $tagihanPending = $tagihans->where('status', 'pending')->count();
        $tagihanBelum   = $tagihans->where('status', 'belum_bayar')->count();

        $totalNominalBelum = $tagihans
            ->whereIn('status', ['belum_bayar', 'pending'])
            ->sum('nominal');

        // Ambil notifikasi belum dibaca
        $unreadCount = 0;
        try {
            $notifResponse = Http::timeout(3)->get(
                env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8007') .
                '/api/notifications/unread-count',
                ['user_id' => $mahasiswaId]
            );
            $unreadCount = $notifResponse->json('data.unread_count', 0);
        } catch (\Exception $e) {}

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'tagihans',
            'totalTagihan',
            'tagihanLunas',
            'tagihanPending',
            'tagihanBelum',
            'totalNominalBelum',
            'unreadCount'
        ));
    }
}