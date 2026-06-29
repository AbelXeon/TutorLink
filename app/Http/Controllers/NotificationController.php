<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
  

   public function Notification()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('Auth.Login');
        }

        // 1. Fetch all notifications for the logged-in user (newest first)
        $notifications = DB::table('notifications')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Mark any unread notifications as read, since they are looking at them now
        DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('Notification.Notification', compact('notifications'));
    }

   // Returns sum of unread notifications AND unread chat messages for dashboard live alerts
    public function getUnreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $userId = Auth::id();

        // 1. Fetch unread notifications count
        $notificationCount = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        // 2. Fetch unread messages count (where user is part of conversation, but didn't send the message)
        $messageCount = DB::table('messages')
            ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
            ->where(function($query) use ($userId) {
                $query->where('conversations.student_id', $userId)
                      ->orWhere('conversations.tutor_id', $userId);
            })
            ->where('messages.sender_id', '!=', $userId)
            ->where('messages.is_read', false)
            ->count();

        // Sum both values for dashboard badge updates
        return response()->json(['count' => $notificationCount + $messageCount]);
    }

}
