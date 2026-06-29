<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TutorProfile;
use App\Models\Schedule;
use App\Models\Categories;
use App\Models\Subjects;

class TeacherController extends Controller
{
    // 1. Dashboard: Fetches bookings, notification badge counts, and schedule details
    public function Dashboard()
    {
        $user = Auth::user();

        // Fetch the teacher's profile
        $profile = TutorProfile::where('user_id', $user->id)->first();

        $subjects = [];
        $schedule = null;
        $bookings = [];
        
        // Count unread notifications
        $unreadNotificationsCount = DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        if ($profile) {
            // Fetch the subjects selected by this tutor
            // Note: Change 'tutor_subjects' to 'tutor__subjects' if using double underscores
            $subjects = DB::table('tutor_subjects') 
                ->join('subjects', 'tutor_subjects.subject_id', '=', 'subjects.id')
                ->where('tutor_subjects.tutor_profile_id', $profile->id)
                ->select('subjects.name')
                ->get();

            // Fetch the schedule
            $schedule = Schedule::where('tutor_id', $profile->id)->first();

            // Fetch incoming bookings made for this tutor profile
            $bookings = DB::table('bookings')
                ->join('users', 'bookings.student_id', '=', 'users.id')
                ->where('bookings.tutor_id', $profile->id)
                ->select('bookings.*', 'users.first_name', 'users.last_name', 'users.email')
                ->orderBy('bookings.created_at', 'desc')
                ->get();

            // Decode dates and times from JSON format
            foreach ($bookings as $booking) {
                $booking->slots = json_decode($booking->selected_slots, true);
            }
        }

        // Return view and pass bookings and notification variables
        return view('Teacher.Teacher_Dashboard', compact(
            'user', 
            'profile', 
            'subjects', 
            'schedule', 
            'bookings', 
            'unreadNotificationsCount'
        ));
    }

    public function TeacherProfileEdit()
    {
        $user = Auth::user();
        $profile = TutorProfile::where('user_id', $user->id)->firstOrFail();

        $categories = Categories::all();
        $subjects = Subjects::all();

        // Get currently selected subject IDs to check boxes by default
        // Note: Change 'tutor_subjects' to 'tutor__subjects' if using double underscores
        $selectedSubjectIds = DB::table('tutor_subjects')
            ->where('tutor_profile_id', $profile->id)
            ->pluck('subject_id')
            ->toArray();

        $schedule = Schedule::where('tutor_id', $profile->id)->first();

        return view('Teacher.Teacher_Profile_Edit', compact('user', 'profile', 'categories', 'subjects', 'selectedSubjectIds', 'schedule'));
    }

    // Process and save updated profile details
    public function UpdateTeacherProfile(Request $request)
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
            // Profile image validation
            'profile_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $profile = TutorProfile::where('user_id', $user->id)->firstOrFail();

        // Handle profile image update if a new file is uploaded
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->update(['profile_image' => $profileImagePath]);
        }

        // Update Tutor Profile
        $profile->update([
            'bio'                 => $request->bio,
            'experience_years'    => $request->experience_years,
            'availability_status' => $request->availability_status,
            'qualification'       => $request->qualification,
            'max_students'        => $request->max_students,
            'price_per_hour'      => $request->price_per_hour,
            'grade_level'         => $request->grade_level,
            'mode'                => $request->mode,
        ]);

        // Sync Selected Subjects (Delete existing associations and insert updated list)
        // Note: Change 'tutor_subjects' to 'tutor__subjects' if using double underscores
        DB::table('tutor_subjects')->where('tutor_profile_id', $profile->id)->delete();

        foreach ($request->subject_ids as $subjectId) {
            DB::table('tutor_subjects')->insert([
                'tutor_profile_id' => $profile->id,
                'subject_id'       => $subjectId,
            ]);
        }

        // Update Schedule
        Schedule::updateOrCreate(
            ['tutor_id' => $profile->id],
            [
                'day_of_week' => $request->day_of_week,
                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time,
            ]
        );

        return redirect()->route('Teacher.Teacher_Dashboard')
            ->with('success', 'Profile updated successfully!');
    }

    // Updated to handle the dynamic tutor ID
    public function TeacherProfile($id)
    {
        // Fetch the tutor profile and join user details
        $profile = TutorProfile::join('users', 'tutor_profiles.user_id', '=', 'users.id')
            ->select(
                'tutor_profiles.*',
                'users.first_name',
                'users.last_name',
                'users.profile_image',
                'users.email'
            )
            ->where('tutor_profiles.id', $id)
            ->firstOrFail();

        // Fetch the subjects taught by this tutor
        // Note: Change 'tutor_subjects' to 'tutor__subjects' if using double underscores
        $subjects = DB::table('tutor_subjects')
            ->join('subjects', 'tutor_subjects.subject_id', '=', 'subjects.id')
            ->where('tutor_subjects.tutor_profile_id', $profile->id)
            ->pluck('subjects.name')
            ->toArray();

        // Fetch the schedule
        $schedule = Schedule::where('tutor_id', $profile->id)->first();

        // Fetch reviews by joining reviews, bookings, and users (to get reviewer names)
        $reviews = DB::table('reviews')
            ->join('bookings', 'reviews.booking_id', '=', 'bookings.id')
            ->join('users', 'reviews.reviewer_id', '=', 'users.id')
            ->where('bookings.tutor_id', $profile->id)
            ->select('reviews.*', 'users.first_name', 'users.last_name')
            ->get();

        return view('Teacher.Teacher_Profile', compact('profile', 'subjects', 'schedule', 'reviews'));
    }
}