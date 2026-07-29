<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/reports/summary',      [ReportController::class, 'summary']);
Route::get('/reports/transactions', [ReportController::class, 'transactions']);
Route::get('/reports/outstanding',  [ReportController::class, 'outstanding']);
Route::post('/reports/export-pdf',  [ReportController::class, 'exportPdf']);

// Route GET khusus untuk test PDF via browser
Route::get('/reports/export-pdf/{type}', function ($type) {
    $controller = app(\App\Http\Controllers\ReportController::class);
    return $controller->exportPdf(
        new \Illuminate\Http\Request(['type' => $type])
    );
});