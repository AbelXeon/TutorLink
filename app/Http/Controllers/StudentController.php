<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Booking; 

class StudentController extends Controller
{
      public function showStudentDashboard()
    {
        $user = Auth::user();
        
            $bookings = Booking::where('student_id', $user->id)
            ->with('tutor') // Eager load the tutor User relation
            ->orderBy('created_at', 'desc')
            ->get();
 
        

        return view('Student.Student_Dashboard', compact('user', 'bookings'));
    }


}

