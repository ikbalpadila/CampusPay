<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Inisiasi pembayaran → generate VA
Route::post('/payments/initiate', [PaymentController::class, 'initiate']);

// Callback dari VA Service (simulasi bank)
Route::post('/payments/callback', [PaymentController::class, 'callback']);

// Upload bukti transfer manual
Route::post('/payments/upload-bukti', [PaymentController::class, 'uploadBukti']);

// Daftar pending untuk admin
Route::get('/payments/pending', [PaymentController::class, 'getPending']);

// Admin verifikasi
Route::post('/payments/manual-verify', [PaymentController::class, 'manualVerify']);