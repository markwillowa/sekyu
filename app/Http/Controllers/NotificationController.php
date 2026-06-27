<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function clear()
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Notifications cleared.');
    }
}
