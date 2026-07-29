<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET semua notifikasi milik user
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'status' => 'success',
            'data'   => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    // GET jumlah notifikasi belum dibaca
    public function unreadCount(Request $request)
    {
        $userId = $request->query('user_id');

        $count = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'status' => 'success',
            'data'   => ['unread_count' => $count],
        ]);
    }

    // POST tandai notifikasi sudah dibaca
    public function markRead(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|integer',
            'notification_ids' => 'nullable|array',
        ]);

        $query = Notification::where('user_id', $request->user_id);

        // Kalau ada ID spesifik, tandai yang itu saja
        // Kalau tidak ada, tandai semua
        if ($request->notification_ids) {
            $query->whereIn('id', $request->notification_ids);
        }

        $query->update(['is_read' => true]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Notifikasi ditandai sudah dibaca',
        ]);
    }

    // POST kirim notifikasi manual (untuk testing)
    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'type'    => 'required|string',
            'title'   => 'required|string',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'type'    => $request->type,
            'title'   => $request->title,
            'message' => $request->message,
            'data'    => $request->data ?? [],
        ]);

        // Broadcast via WebSocket
        broadcast(new \App\Events\PaymentNotification(
            $request->user_id,
            $request->type,
            $request->title,
            $request->message,
            $request->data ?? []
        ));

        return response()->json([
            'status'  => 'success',
            'message' => 'Notifikasi berhasil dikirim',
            'data'    => $notification,
        ]);
    }
}