<?php

use App\Http\Controllers\Mahasiswa\AuthController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\TagihanController;
use App\Http\Controllers\Mahasiswa\NotifikasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/mahasiswa/login'));

Route::prefix('mahasiswa')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Guest
    |--------------------------------------------------------------------------
    */

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('mahasiswa.login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('mahasiswa.login.post');

    /*
    |--------------------------------------------------------------------------
    | Protected Area
    |--------------------------------------------------------------------------
    */

    Route::middleware('mahasiswa.auth')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('mahasiswa.logout');

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('mahasiswa.dashboard');

        Route::get('/tagihan', [TagihanController::class, 'index'])
            ->name('mahasiswa.tagihan');

        Route::get('/tagihan/{id}/bayar', [TagihanController::class, 'bayar'])
            ->name('mahasiswa.tagihan.bayar');

        Route::post('/tagihan/{id}/generate-va', [TagihanController::class, 'generateVA'])
            ->name('mahasiswa.tagihan.generate-va');

        Route::get('/tagihan/{id}/upload-bukti', [TagihanController::class, 'showUploadBukti'])
            ->name('mahasiswa.tagihan.upload-bukti');

        Route::post('/tagihan/{id}/upload-bukti', [TagihanController::class, 'uploadBukti'])
            ->name('mahasiswa.tagihan.upload-bukti.post');

        Route::get('/riwayat', [TagihanController::class, 'riwayat'])
            ->name('mahasiswa.riwayat');

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('mahasiswa.notifikasi');

        Route::post('/notifikasi/mark-read', [NotifikasiController::class, 'markRead'])
            ->name('mahasiswa.notifikasi.mark-read');
    });

});

/*
|--------------------------------------------------------------------------
| Template Import
|--------------------------------------------------------------------------
*/

Route::get('/template-mahasiswa', function () {

    $headers = [
        'nim',
        'nama',
        'prodi',
        'fakultas',
        'semester',
        'kelas',
    ];

    $rows = [
        [
            '2021010001',
            'Contoh Mahasiswa',
            'Teknik Informatika',
            'Fakultas Teknik',
            'Semester 5',
            'TI-5A',
        ],
    ];

    $csv = implode(',', $headers) . "\n";

    foreach ($rows as $row) {
        $csv .= implode(',', $row) . "\n";
    }

    return response($csv, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' =>
            'attachment; filename=template-mahasiswa.csv',
    ]);

})->name('template.mahasiswa');
