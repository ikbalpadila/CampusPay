<?php

namespace App\Jobs;

use App\Models\Tagihan;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateBillingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mahasiswas;
    protected $requestData;
    protected $semesterNama;

    public function __construct($mahasiswas, $requestData, $semesterNama)
    {
        $this->mahasiswas = $mahasiswas;
        $this->requestData = $requestData;
        $this->semesterNama = $semesterNama;
    }

    public function handle(): void
    {
        DB::beginTransaction();

        try {

            // Ambil existing data sekali saja
            $existing = Tagihan::where('payment_type_id', $this->requestData['payment_type_id'])
                ->where('semester_id', $this->requestData['semester_id'])
                ->pluck('mahasiswa_id')
                ->toArray();

            $insertData = [];

            foreach ($this->mahasiswas as $mhs) {

                if (in_array($mhs['id'], $existing)) {
                    continue;
                }

                $insertData[] = [
                    'mahasiswa_id'    => $mhs['id'],
                    'mahasiswa_nim'   => $mhs['nim'],
                    'mahasiswa_nama'  => $mhs['nama'],
                    'payment_type_id' => $this->requestData['payment_type_id'],
                    'semester_id'     => $this->requestData['semester_id'],
                    'semester_nama'   => $this->semesterNama,
                    'nominal'         => $this->requestData['nominal'],
                    'jatuh_tempo'     => $this->requestData['jatuh_tempo'],
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];

                // 🔥 Insert per 200 data
                if (count($insertData) >= 200) {
                    Tagihan::insert($insertData);
                    $insertData = [];
                }
            }

            // Insert sisa
            if (!empty($insertData)) {
                Tagihan::insert($insertData);
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('GenerateBillingJob Error: ' . $e->getMessage());

            throw $e;
        }
    }
}