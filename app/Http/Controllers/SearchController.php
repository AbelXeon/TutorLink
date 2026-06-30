<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TutorProfile;
use App\Models\Categories;   
use App\Models\Location;     
use App\Models\User;

class SearchController extends Controller
{
    // 1. Browse & Filter Tutors
    public function browseTutors(Request $request)
    {
        // Get active tutors only
        $query = TutorProfile::where('availability_status', 'active')
            ->with(['user.location', 'subjects.category', 'gradeLevels']);

        // Filter by Location (City)
        if ($request->filled('location_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }

        // Filter by Address (District/Area)
        if ($request->filled('address')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('address', $request->address);
            });
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->whereHas('subjects', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by Subject
        if ($request->filled('subject_id')) {
            $query->whereHas('subjects', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        $tutors = $query->get();

        // Load options for dropdowns
        $categories = Categories::with('subjects')->get();
        $locations = Location::all();

        return view('Search.Tutor_view', compact('tutors', 'categories', 'locations'));
    }

    
    public function showTutorProfile($username)
    {
        $tutor = TutorProfile::whereHas('user', function ($q) use ($username) {
                $q->where('username', $username);
            })
            ->where('availability_status', 'active')
            ->with(['user.location', 'user.schedules', 'subjects.category', 'gradeLevels'])
            ->firstOrFail();

        return view('Teacher.Teacher_profile', compact('tutor'));
    }
    
}
