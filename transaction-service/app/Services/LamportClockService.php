<?php

namespace App\Services;

use App\Models\LamportClock;
use Illuminate\Support\Facades\DB;

class LamportClockService
{
    /**
     * Increment Lamport Clock secara atomic dan return nilai baru.
     *
     * Menggunakan database transaction + SELECT FOR UPDATE
     * untuk memastikan tidak ada dua transaksi yang mendapat
     * nilai clock yang sama (race condition prevention).
     *
     * Aturan Lamport Clock:
     * - Setiap event lokal: clock = clock + 1
     * - Setiap receive event: clock = MAX(local, received) + 1
     */
    public function increment(): int
    {
        return DB::transaction(function () {

            // Lock row supaya tidak ada proses lain yang
            // bisa baca/tulis nilai clock secara bersamaan
            $clock = LamportClock::lockForUpdate()->find(1);

            if (!$clock) {
                // Buat record baru jika belum ada
                LamportClock::create(['id' => 1, 'value' => 0]);
                $clock = LamportClock::lockForUpdate()->find(1);
            }

            $newValue = $clock->value + 1;

            $clock->update(['value' => $newValue]);

            return $newValue;
        });
    }

    /**
     * Sync clock saat menerima event dari service lain.
     * clock = MAX(local_clock, received_clock) + 1
     */
    public function sync(int $receivedClock): int
    {
        return DB::transaction(function () use ($receivedClock) {

            $clock = LamportClock::lockForUpdate()->find(1);

            if (!$clock) {
                // Buat record baru jika belum ada
                LamportClock::create(['id' => 1, 'value' => 0]);
                $clock = LamportClock::lockForUpdate()->find(1);
            }

            $newValue = max($clock->value, $receivedClock) + 1;

            $clock->update(['value' => $newValue]);

            return $newValue;
        });
    }

    /**
     * Ambil nilai clock saat ini tanpa increment.
     */
    public function current(): int
    {
        $clock = LamportClock::find(1);
        return $clock ? $clock->value : 0;
    }
}