<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Categories;
use App\Models\Subjects;
use App\Models\TutorProfile;
use App\Models\Schedule;

class ProfileController extends Controller
{

     public function TeacherProfile(){
        return view('Teacher.Teacher_Profile');
    }

     public function TeacherProfileMake(){
        return view('Teacher.Teacher_Profile_Edit');
    }

    // Renders the make profile page with dynamic categories/subjects
    public function TutorProfile()
    {
        $categories = Categories::all();
        $subjects = Subjects::all(); // Sent to the blade to group inside JS

        return view('Profile.Tutor_Profile_make', compact('categories', 'subjects'));
    }

    // Stores the submitted details
    public function StoreTutorProfile(Request $request)
    {
        $request->validate([
            'bio'                 => 'required|string',
            'experience_years'    => 'required|integer|min:0',
            'availability_status' => 'required|string',
            'mode'                => 'required|string',
            'qualification'       => 'required|string',
            'grade_level'         => 'required|string',
            'price_per_hour'      => 'required|numeric|min:0',
            'max_students'        => 'required|integer|min:1',
            // Schedule validation
            'day_of_week'         => 'required|string',
            'start_time'          => 'required',
            'end_time'            => 'required',
            // Subjects validation
            'subject_ids'         => 'required|array',
            'subject_ids.*'       => 'exists:subjects,id',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('Auth.Login')->with('error', 'Please log in first.');
        }

        // 1. Create or Update the Tutor Profile record
        $tutorProfile = TutorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio'                 => $request->bio,
                'experience_years'    => $request->experience_years,
                'availability_status' => $request->availability_status,
                'qualification'       => $request->qualification,
                'max_students'        => $request->max_students,
                'price_per_hour'      => $request->price_per_hour,
                'grade_level'         => $request->grade_level,
                'mode'                => $request->mode,
                'total_reviews'       => 0,
            ]
        );

        // 2. Link selected subjects inside 'tutor_subjects' table
        // Delete previous selections to avoid duplicate entries
        DB::table('tutor_subjects')->where('tutor_profile_id', $tutorProfile->id)->delete();

        foreach ($request->subject_ids as $subjectId) {
            DB::table('tutor_subjects')->insert([
                'tutor_profile_id' => $tutorProfile->id,
                'subject_id'       => $subjectId,
            ]);
        }

        // 3. Create or Update the weekly schedule
        Schedule::updateOrCreate(
            ['tutor_id' => $tutorProfile->id],
            [
                'day_of_week' => $request->day_of_week,
                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time,
            ]
        );

        // 4. Redirect the teacher to their completed profile view
        return redirect()->route('Teacher.Teacher_Dashboard')
            ->with('success', 'Your profile has been created successfully!');
    }
}
