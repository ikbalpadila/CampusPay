<?php

namespace App\Jobs;

use App\Events\PaymentNotification;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTagihanDibuat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $payload) {}

    public function handle(): void
    {
        $mahasiswaId = $this->payload['mahasiswa_id'] ?? null;
        $nominal     = number_format($this->payload['nominal'] ?? 0, 0, ',', '.');
        $jenis       = $this->payload['jenis'] ?? 'Pembayaran';
        $jatuhTempo  = $this->payload['jatuh_tempo'] ?? '-';

        if (!$mahasiswaId) return;

        $title   = "🔔 Tagihan Baru";
        $message = "Tagihan {$jenis} Rp {$nominal} telah dibuat. Jatuh tempo: {$jatuhTempo}.";

        Notification::create([
            'user_id' => $mahasiswaId,
            'type'    => 'tagihan_baru',
            'title'   => $title,
            'message' => $message,
            'data'    => $this->payload,
        ]);

        broadcast(new PaymentNotification(
            $mahasiswaId,
            'tagihan_baru',
            $title,
            $message,
            $this->payload
        ));
    }
}