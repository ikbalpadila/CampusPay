<?php

use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\TagihanController;
use APp\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

// Payment Types
Route::get('/payment-types', [PaymentTypeController::class, 'index']);
Route::post('/payment-types', [PaymentTypeController::class, 'store']);
Route::put('/payment-types/{id}', [PaymentTypeController::class, 'update']);

/// Tagihan
Route::prefix('billings')->group(function () {

    Route::get('/', [TagihanController::class, 'index']);
    Route::get('/my', [TagihanController::class, 'getByMahasiswa']);

    // 🔥 TARUH DI ATAS
    Route::post('/bulk-generate', [TagihanController::class, 'bulkGenerate']);

    Route::post('/', [TagihanController::class, 'store']);
    Route::get('/{id}', [TagihanController::class, 'show']);
    Route::put('/{id}/status', [TagihanController::class, 'updateStatus']);

});
