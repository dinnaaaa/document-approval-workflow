<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Menampilkan daftar notifikasi user.
     */
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Menandai satu notifikasi sebagai sudah dibaca.
     */
    public function read(Request $request, string $notification)
    {
        $userNotification = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $userNotification->markAsRead();

        return redirect()->route('notifications.index');
    }
}