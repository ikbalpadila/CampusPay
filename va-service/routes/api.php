<?php

use App\Http\Controllers\VirtualAccountController;
use Illuminate\Support\Facades\Route;

// Generate VA - dipanggil Payment Service
Route::post('/va/generate', [VirtualAccountController::class, 'generate']);

// Validasi VA - dipanggil Payment Service
Route::post('/va/validate', [VirtualAccountController::class, 'validate']);

// Detail VA by nomor
Route::get('/va/{nomor_va}', [VirtualAccountController::class, 'show']);

// Simulasi konfirmasi pembayaran bank (untuk testing)
Route::post('/va/simulate-payment',
    [VirtualAccountController::class, 'simulatePayment']);

Route::get('/va/tagihan/{tagihanId}',
    [VirtualAccountController::class, 'getByTagihan']);
