<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('sent_at', 'desc')
            ->paginate(15);

        return view('customer.notifications.index', compact('notifications'));
    }

    /**
     * Tandai notifikasi sebagai terbaca
     */
    public function markAsRead(string $id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notifikasi ditandai sebagai terbaca.');
    }

    /**
     * Tandai semua notifikasi sebagai terbaca
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai terbaca.');
    }
}
