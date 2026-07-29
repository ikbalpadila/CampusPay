<?php

namespace App\Jobs;

use App\Events\PaymentNotification;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPembayaranBerhasil implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $payload) {}

    public function handle(): void
    {
        $mahasiswaId = $this->payload['mahasiswa_id'] ?? null;
        $nominal     = number_format($this->payload['nominal'] ?? 0, 0, ',', '.');
        $jenis       = $this->payload['jenis'] ?? 'Pembayaran';
        $semester    = $this->payload['semester'] ?? '';

        if (!$mahasiswaId) return;

        $title   = "✅ Pembayaran Berhasil";
        $message = "{$jenis} {$semester} sebesar Rp {$nominal} telah LUNAS.";

        // Simpan ke database
        Notification::create([
            'user_id' => $mahasiswaId,
            'type'    => 'pembayaran_berhasil',
            'title'   => $title,
            'message' => $message,
            'data'    => $this->payload,
        ]);

        // Broadcast via WebSocket ke browser mahasiswa
        broadcast(new PaymentNotification(
            $mahasiswaId,
            'pembayaran_berhasil',
            $title,
            $message,
            $this->payload
        ));

        Log::info("Notifikasi LUNAS dikirim ke mahasiswa {$mahasiswaId}");
    }
}