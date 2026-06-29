<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
     public function Dashboard()
    {
        $user = Auth::user();
        
        // Get unread notifications count
        $unreadNotificationsCount = DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        
        // Get bookings with tutor details
        $bookings = DB::table('bookings')
            ->join('tutor_profiles', 'bookings.tutor_id', '=', 'tutor_profiles.id')
            ->join('users', 'tutor_profiles.user_id', '=', 'users.id')
            ->where('bookings.student_id', $user->id)
            ->select(
                'bookings.*',
                'users.first_name as tutor_first_name',
                'users.last_name as tutor_last_name'
            )
            ->orderBy('bookings.created_at', 'desc')
            ->get();

        // Decode the JSON slots
        foreach ($bookings as $booking) {
            $booking->slots = json_decode($booking->selected_slots, true);
        }
        
        return view('Student.Student_Dashboard', 
            compact('user', 'unreadNotificationsCount', 'bookings'));
    }
    }

