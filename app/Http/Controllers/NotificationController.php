<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        $this->notifiable()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function clear()
    {
        $this->notifiable()->notifications()->delete();

        return back()->with('success', 'Notifications cleared.');
    }

    private function notifiable()
    {
        return auth()->user()
            ?? auth('pro_agency')->user()
            ?? auth('pro_employee')->user()
            ?? abort(403);
    }
}
