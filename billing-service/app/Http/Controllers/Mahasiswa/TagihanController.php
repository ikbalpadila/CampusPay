<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TagihanController extends Controller
{
    private function getMahasiswaId(): int
    {
        return session('mahasiswa')['id'];
    }

    public function index()
    {
        $mahasiswaId = $this->getMahasiswaId();
        $mahasiswa = session('mahasiswa');

        $tagihans = Tagihan::where('mahasiswa_id', $mahasiswaId)
                           ->with('paymentType')
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('mahasiswa.tagihan.index',
                    compact('tagihans', 'mahasiswa'));
    }

    public function bayar($id)
{
    $mahasiswaId = $this->getMahasiswaId();

    $tagihan = Tagihan::with('paymentType')
        ->where('mahasiswa_id', $mahasiswaId)
        ->findOrFail($id);

    $vaData = null;

    try {

        $response = Http::get(
            env('VA_SERVICE_URL', 'http://127.0.0.1:8005')
            . '/api/va/tagihan/' . $tagihan->id
        );

        if ($response->successful()) {
            $vaData = $response->json('data');
        }

    } catch (\Exception $e) {
    }

    return view(
        'mahasiswa.tagihan.bayar',
        compact('tagihan', 'vaData')
    );
}


    public function generateVA(Request $request, $id)
    {
        $mahasiswaId = $this->getMahasiswaId();
        $tagihan     = Tagihan::with('paymentType')->where('mahasiswa_id', $mahasiswaId)
                              ->findOrFail($id);

        $response = Http::timeout(10)->post(
            env('PAYMENT_SERVICE_URL', 'http://127.0.0.1:8004') .
            '/api/payments/initiate',
            [
                'tagihan_id'        => $tagihan->id,
                'mahasiswa_id'      => $mahasiswaId,
                'nominal'           => $tagihan->nominal,
                'status'            => $tagihan->status,
                'payment_type_nama' => $tagihan->paymentType->nama ?? 'Pembayaran',
                'semester_nama'     => $tagihan->semester_nama,
                'jatuh_tempo'       => $tagihan->jatuh_tempo,
            ]
        );

        if ($response->successful()) {
            return redirect()->route('mahasiswa.tagihan.bayar', $id)
                ->with('success', 'Virtual Account berhasil dibuat!');
        }

        return back()->with('error', 'Gagal generate Virtual Account');
    }

    public function showUploadBukti($id)
    {
        $mahasiswaId = $this->getMahasiswaId();
        $mahasiswa = session('mahasiswa');
        $tagihan     = Tagihan::with('paymentType')
                              ->where('mahasiswa_id', $mahasiswaId)
                              ->findOrFail($id);

        return view('mahasiswa.tagihan.upload-bukti',
                    compact('tagihan', 'mahasiswa'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    
        $mahasiswaId = $this->getMahasiswaId();
        $mahasiswa = session('mahasiswa');
        $tagihan     = Tagihan::with('paymentType')
                              ->where('mahasiswa_id', $mahasiswaId)
                              ->findOrFail($id);
    
        // Simpan file ke storage/app/public/bukti-transfer/
        $path = $request->file('bukti_transfer')
                        ->store('bukti-transfer', 'public');
    
        // Simpan dengan format BUKTI_FILE: agar bisa di-parse admin
        $tagihan->update([
            'status'  => 'pending',
            'catatan' => 'BUKTI_FILE:' . $path,
        ]);
    
        // Kirim notifikasi pending ke admin (user_id 1 = admin)
        try {
            Http::timeout(3)->post(
                env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8007') .
                '/api/notifications/send',
                [
                    'user_id' => 1,
                    'type'    => 'pembayaran_pending',
                    'title'   => '⏳ Bukti Transfer Masuk',
                    'message' => $mahasiswa['nama'] .
                                 ' mengupload bukti transfer untuk tagihan ' .
                                 ($tagihan->paymentType->nama ?? '') .
                                 ' Rp ' .
                                 number_format($tagihan->nominal, 0, ',', '.') .
                                 '. Mohon segera diverifikasi.',
                ]
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning(
                'Gagal kirim notif pending: ' . $e->getMessage()
            );
        }
    
        return redirect()->route('mahasiswa.tagihan')
            ->with('success',
                   'Bukti transfer berhasil diupload! Menunggu verifikasi admin.');
    }

       
    public function riwayat()
    {
        $mahasiswaId = $this->getMahasiswaId();
        $mahasiswa = session('mahasiswa');

        $transactions = [];
        try {
            $response = Http::timeout(5)->get(
                env('TRANSACTION_SERVICE_URL', 'http://127.0.0.1:8006') .
                '/api/transactions/my',
                ['mahasiswa_id' => $mahasiswaId]
            );
            if ($response->successful()) {
                $transactions = $response->json('data', []);
            }
        } catch (\Exception $e) {}

        return view('mahasiswa.riwayat',
                    compact('transactions', 'mahasiswa'));
    }
}