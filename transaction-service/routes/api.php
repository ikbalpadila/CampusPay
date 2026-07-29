<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Catat transaksi baru — dipanggil Payment Service
Route::post('/transactions', [TransactionController::class, 'store']);

// Semua transaksi (admin)
Route::get('/transactions', [TransactionController::class, 'index']);

// Riwayat transaksi mahasiswa
Route::get('/transactions/my', [TransactionController::class, 'my']);

// Status Lamport Clock saat ini
Route::get('/transactions/clock', [TransactionController::class, 'clockStatus']);

// Demo Lamport Clock untuk presentasi
Route::post('/transactions/demo-lamport', [TransactionController::class, 'demoLamportClock']);

// Detail transaksi
Route::get('/transactions/{id}', [TransactionController::class, 'show']);