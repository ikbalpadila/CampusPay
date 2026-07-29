<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
Route::post('/notifications/mark-read', [NotificationController::class, 'markRead']);
Route::post('/notifications/send', [NotificationController::class, 'send']);