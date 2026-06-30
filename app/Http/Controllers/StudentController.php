<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
      public function showStudentDashboard()
    {
        $user = Auth::user();
        
        $bookings = collect(); 

        return view('Student.Student_Dashboard', compact('user', 'bookings'));
    }


}

