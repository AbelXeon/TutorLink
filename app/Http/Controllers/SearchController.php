<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TutorProfile;

class SearchController extends Controller
{
    public function SearchTutorView(){
    // 1. Fetch all tutors by joining with the users table
        $tutors = TutorProfile::join('users', 'tutor_profiles.user_id', '=', 'users.id')
            ->select(
                'tutor_profiles.*',
                'users.first_name',
                'users.last_name',
                'users.profile_image',
                'users.email'
            )
            ->get();

        // 2. Fetch and assign the subjects each tutor teaches
        foreach ($tutors as $tutor) {
            
            // NOTE: If you decided to keep the double underscore, change 'tutor_subjects' to 'tutor__subjects' below
            $tutor->subjects = DB::table('tutor_subjects') 
                ->join('subjects', 'tutor_subjects.subject_id', '=', 'subjects.id')
                ->where('tutor_subjects.tutor_profile_id', $tutor->id)
                ->pluck('subjects.name')
                ->toArray();
        }

        // 3. Send the $tutors variable to your blade view
        return view('Search.Tutor_View', compact('tutors'));
    }
    
}
