<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class NotificationController extends Controller
{
  

  // 1. Show Notifications Page
    public function index()
    {
        $user = Auth::user();

        // Fetch notifications sorted by most recent
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Correct and only return statement:
        return view('Notification.Notification', compact('user', 'notifications'));
    }

    // 2. Mark specific notification as Read securely
    public function markAsRead($id)
    {
        $user = Auth::user();

        // Securely find the notification belonging ONLY to the logged-in user (prevents hackers from reading other's notifications)
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $notification->update([
            'read_at' => true
        ]);

        // If it has an action URL, redirect them to it
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back()->with('success', 'Notification marked as read.');
    }

}
