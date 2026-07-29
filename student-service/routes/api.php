<?php

use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\SemesterController;
use Illuminate\Support\Facades\Route;

// Semester routes
Route::get('/semesters', [SemesterController::class, 'index']);

Route::post('/semesters', [SemesterController::class, 'store']);

Route::put('/semesters/{id}', [SemesterController::class, 'update']);

Route::delete('/semesters/{id}', [SemesterController::class, 'destroy']);

Route::post('/semesters/{id}/set-aktif', [SemesterController::class, 'setAktif']);

// Mahasiswa routes
Route::get('/students', [MahasiswaController::class, 'index']);
Route::get('/students/{id}', [MahasiswaController::class, 'show']);
Route::get('/students/nim/{nim}', [MahasiswaController::class, 'showByNim']);
Route::post('/students', [MahasiswaController::class, 'store']);
Route::put('/students/{id}', [MahasiswaController::class, 'update']);
Route::post('/students/import', [MahasiswaController::class, 'import']);

// Endpoint khusus untuk Billing Service
Route::get('/students/semester/{semester_id}/aktif',
    [MahasiswaController::class, 'aktifBySemester']);

    Route::delete(
        '/students/{id}',
        [MahasiswaController::class, 'destroy']
    );