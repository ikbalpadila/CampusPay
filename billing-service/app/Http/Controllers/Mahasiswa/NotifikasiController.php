<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotifikasiController extends Controller
{
    public function index()
    {
        $mahasiswa = session('mahasiswa');

        $mahasiswaId = $mahasiswa['id'];

        $notifications = [];

        try {
            $response = Http::timeout(5)->get(
                env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8007') .
                '/api/notifications',
                ['user_id' => $mahasiswaId]
            );
            if ($response->successful()) {
                $notifications = $response->json('data.data', []);
            }
        } catch (\Exception $e) {}

        return view('mahasiswa.notifikasi',
                    compact('notifications', 'mahasiswa'));
    }

    public function markRead(Request $request)
    {
        $mahasiswa = session('mahasiswa');

        $mahasiswaId = $mahasiswa['id'];
        try {
            Http::timeout(5)->post(
                env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8007') .
                '/api/notifications/mark-read',
                ['user_id' => $mahasiswaId]
            );
        } catch (\Exception $e) {}

        return back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }
}