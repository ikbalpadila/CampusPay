<?php

namespace App\Jobs;

use App\Events\PaymentNotification;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPembayaranDitolak implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $payload) {}

    public function handle(): void
    {
        $mahasiswaId = $this->payload['mahasiswa_id'] ?? null;
        $alasan      = $this->payload['alasan'] ?? 'Tidak ada keterangan';

        if (!$mahasiswaId) return;

        $title   = "❌ Pembayaran Ditolak";
        $message = "Bukti transfer ditolak admin. Alasan: {$alasan}. Silakan upload ulang.";

        Notification::create([
            'user_id' => $mahasiswaId,
            'type'    => 'pembayaran_ditolak',
            'title'   => $title,
            'message' => $message,
            'data'    => $this->payload,
        ]);

        broadcast(new PaymentNotification(
            $mahasiswaId,
            'pembayaran_ditolak',
            $title,
            $message,
            $this->payload
        ));
    }
}