<?php

namespace App\Filament\Resources\Tagihans\Pages;

use App\Filament\Resources\Tagihans\TagihanResource;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateTagihan extends CreateRecord
{
    protected static string $resource = TagihanResource::class;

    protected function afterCreate(): void
    {
        try {

            $tagihan = $this->record;

            Http::timeout(10)
                ->post(
                    env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8007')
                    . '/api/notifications/send',
                    [
                        'user_id' => $tagihan->mahasiswa_id,

                        'type' => 'tagihan_baru',

                        'title' => 'Tagihan Baru',

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
                'NOTIF TAGIHAN BARU TERKIRIM',
                [
                    'mahasiswa_id' => $tagihan->mahasiswa_id,
                    'tagihan_id'   => $tagihan->id,
                ]
            );

        } catch (\Exception $e) {

            Log::error(
                'GAGAL KIRIM NOTIF TAGIHAN BARU : '
                . $e->getMessage()
            );
        }
    }
}