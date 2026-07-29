<?php

namespace App\Services;

use App\Models\VirtualAccount;

class VAGeneratorService
{
    // Prefix VA UMB
    private string $prefix = '8808';

    public function generate(
        int $tagihanId,
        int $mahasiswaId,
        float $nominal
    ): VirtualAccount {

        // Cek apakah VA aktif dan BELUM expired sudah ada untuk tagihan ini
        $existing = VirtualAccount::where('tagihan_id', $tagihanId)
            ->where('status', 'aktif')
            ->where('expired_at', '>', now())
            ->first();

        if ($existing) {
            return $existing;
        }

        // Generate nomor VA unik baru
        $nomorVA = $this->generateNomorVA($mahasiswaId);

        // Update jika record lama ada (expired/digunakan), atau buat baru
        // Menggunakan updateOrCreate agar tidak crash karena UNIQUE tagihan_id
        $va = VirtualAccount::updateOrCreate(
            ['tagihan_id' => $tagihanId],
            [
                'mahasiswa_id' => $mahasiswaId,
                'nomor_va'     => $nomorVA,
                'nominal'      => $nominal,
                'status'       => 'aktif',
                'expired_at'   => now()->addHours(24),
            ]
        );

        return $va;
    }

    private function generateNomorVA(int $mahasiswaId): string
    {
        do {
            // Format: 8808 + mahasiswa_id (4 digit) + random (4 digit)
            $mahasiswaPart = str_pad($mahasiswaId, 4, '0', STR_PAD_LEFT);
            $randomPart    = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
            $nomorVA       = $this->prefix . $mahasiswaPart . $randomPart;

        // Pastikan nomor VA benar-benar unik
        } while (VirtualAccount::where('nomor_va', $nomorVA)->exists());

        return $nomorVA;
    }

    public function validate(string $nomorVA, float $nominal): array
    {
        $va = VirtualAccount::where('nomor_va', $nomorVA)->first();

        if (!$va) {
            return [
                'valid'   => false,
                'message' => 'Nomor VA tidak ditemukan',
            ];
        }

        if ($va->status !== 'aktif') {
            return [
                'valid'   => false,
                'message' => 'VA sudah digunakan atau expired',
            ];
        }

        if ($va->expired_at->isPast()) {
            $va->update(['status' => 'expired']);
            return [
                'valid'   => false,
                'message' => 'VA sudah expired',
            ];
        }

        if ((float) $va->nominal !== (float) $nominal) {
            return [
                'valid'   => false,
                'message' => "Nominal tidak sesuai. Harus Rp " .
                             number_format($va->nominal, 0, ',', '.'),
            ];
        }

        return [
            'valid' => true,
            'data'  => $va,
        ];
    }
}