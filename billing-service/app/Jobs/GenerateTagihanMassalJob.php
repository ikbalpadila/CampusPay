<?php

namespace App\Jobs;

use App\Models\Tagihan;
use App\Services\StudentServiceClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateTagihanMassalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(StudentServiceClient $studentClient): void
    {
        try {
            $mahasiswas = $studentClient
                ->getMahasiswaAktifBySemester($this->data['semester_id']);

            if (empty($mahasiswas)) {
                return;
            }

            // Ambil semester name
            try {
                $semesters = $studentClient->getSemesters();
                $semester  = collect($semesters)
                    ->firstWhere('id', $this->data['semester_id']);

                $semesterNama = $semester['nama']
                    ?? 'Semester ' . $this->data['semester_id'];
            } catch (\Exception $e) {
                $semesterNama = 'Semester ' . $this->data['semester_id'];
            }

            DB::beginTransaction();

            $existing = Tagihan::where('payment_type_id', $this->data['payment_type_id'])
                ->where('semester_id', $this->data['semester_id'])
                ->pluck('mahasiswa_id')
                ->toArray();

            $insertData = [];

            foreach ($mahasiswas as $mhs) {

                if (in_array($mhs['id'], $existing)) {
                    continue;
                }

                $insertData[] = [
                    'mahasiswa_id'    => $mhs['id'],
                    'mahasiswa_nim'   => $mhs['nim'],
                    'mahasiswa_nama'  => $mhs['nama'],
                    'payment_type_id' => $this->data['payment_type_id'],
                    'semester_id'     => $this->data['semester_id'],
                    'semester_nama'   => $semesterNama,
                    'nominal'         => $this->data['nominal'],
                    'jatuh_tempo'     => $this->data['jatuh_tempo'],
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];

                // batch insert per 200
                if (count($insertData) >= 200) {
                    Tagihan::insert($insertData);
                    $insertData = [];
                }
            }

            if (!empty($insertData)) {
                Tagihan::insert($insertData);
            }

            DB::commit();

            // Kirim Notifikasi di Background
            $tagihanBaru = Tagihan::with('paymentType')
                ->where('payment_type_id', $this->data['payment_type_id'])
                ->where('semester_id', $this->data['semester_id'])
                ->whereDate('created_at', today())
                ->get();

            $notifUrl = env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8007') . '/api/notifications/send';
            foreach ($tagihanBaru as $tagihan) {
                try {
                    \Illuminate\Support\Facades\Http::timeout(3)->post($notifUrl, [
                        'user_id' => $tagihan->mahasiswa_id,
                        'type'    => 'tagihan_baru',
                        'title'   => '🔔 Tagihan Baru',
                        'message' => 'Tagihan ' . ($tagihan->paymentType->nama ?? 'Pembayaran') .
                                     ' semester ' . $tagihan->semester_nama .
                                     ' sebesar Rp ' . number_format($tagihan->nominal, 0, ',', '.') .
                                     ' telah dibuat.',
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Gagal kirim notif tagihan baru (background): ' . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Generate Job Error: ' . $e->getMessage());
        }
    }
}