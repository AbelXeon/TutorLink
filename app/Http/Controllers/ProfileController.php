<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use App\Models\Categories;
use App\Models\Subjects;
use App\Models\TutorProfile;
use App\Models\Schedule;
use App\Models\GradeLevels;

class ProfileController extends Controller
{

     public function showTutorProfileSetup()
    {
        $user = Auth::user();
        $tutorProfile = TutorProfile::firstOrCreate(['user_id' => $user->id]);
        $gradeLevels = GradeLevels::all();

        // Load categories with their respective subjects securely in one query
        $categories = Categories::with('subjects')->get();
          $schedules = $user->schedules;

        if (empty($tutorProfile->bio)) {
            return view('Profile.Tutor_Profile_make',
             compact('user', 'tutorProfile', 'gradeLevels', 'categories','schedules'));
        }

        return view('Teacher.Teacher_Profile_Edit', 
        compact('user', 'tutorProfile', 'gradeLevels', 'categories','schedules'));
    }




    // 2. Save/Update Profile Data
    public function storeTutorProfileSetup(Request $request)
    {
        $user = Auth::user();
        $limiterKey = 'tutor-profile-edit:' . $user->id;

        if (RateLimiter::tooManyAttempts($limiterKey, 3)) {
            $secondsLeft = RateLimiter::availableIn($limiterKey);
            $hoursLeft = ceil($secondsLeft / 3600);

            return back()->withErrors([
                'error' => "Security Limit: You can only edit your profile 3 times per day. Please try again in {$hoursLeft} hours."
            ]);
        }

        $request->validate([
            'bio'              => 'required|string|min:20|max:1000',
            'experience_years' => 'required|integer|min:0|max:50',
            'qualification'    => 'required|string|in:High School Diploma,Bachelor Degree,Master Degree,PhD,Other Certification',
            'max_students'     => 'required|integer|min:1|max:100',
            'price_per_hour'   => 'required|numeric|min:0',
            'teaching_mode'    => 'required|string|in:online,in-person,hybrid',
            'grade_levels'     => 'required|array',
            'grade_levels.*'   => 'exists:grade_levels,id',
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'subjects'         => 'required|array',
            'subjects.*'       => 'exists:subjects,id', 

             'schedules'            => 'nullable|array',
            'schedules.*.day'      => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'schedules.*.start'    => 'required|date_format:H:i',
            'schedules.*.end'      => 'required|date_format:H:i|after:schedules.*.start',
        ]);

        // Securely verify that all selected subjects belong to the same category
        if ($request->has('subjects') && count($request->subjects) > 0) {
            $subjectIds = $request->subjects;
            
            // Get the category of the first selected subject
            $firstSubject = \App\Models\Subjects::find($subjectIds[0]);
            if ($firstSubject) {
                $categoryId = $firstSubject->category_id;
                
                // Check if any selected subject has a different category
                $invalidCount = \App\Models\Subjects::whereIn('id', $subjectIds)
                    ->where('category_id', '!=', $categoryId)
                    ->count();

                if ($invalidCount > 0) {
                    return back()->withErrors(['subjects' => 'All selected subjects must belong to the same category.']);
                }
            }
        }

        $profile = TutorProfile::where('user_id', $user->id)->firstOrFail();

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->update(['profile_image' => $path]);
        }

        $profile->update([
            'bio'              => $request->bio,
            'experience_years' => $request->experience_years,
            'qualification'    => $request->qualification,
            'max_students'     => $request->max_students,
            'price_per_hour'   => $request->price_per_hour,
            'teaching_mode'    => $request->teaching_mode,
            'availability_status' => 'active', 
        ]);

        // Securely sync relationships
        $profile->gradeLevels()->sync($request->grade_levels);
        $profile->subjects()->sync($request->subjects); 


         DB::transaction(function () use ($user, $request) {
            $user->schedules()->delete();

            if ($request->has('schedules')) {
                foreach ($request->schedules as $sched) {
                    Schedule::create([
                        'tutor_id'    => $user->id,
                        'day_of_week' => $sched['day'],
                        'start_time'  => $sched['start'],
                        'end_time'    => $sched['end']
                    ]);
                }
            }
            });

        RateLimiter::hit($limiterKey, 86400);

        return redirect()->route('tutor.dashboard')->with('success', 'Your tutor profile has been updated!');
    }

    // 3. Render Dashboard
    public function showTeacherDashboard()
    {
        $user = Auth::user();
        $tutorProfile = TutorProfile::where('user_id', $user->id)->firstOrFail();
        $gradeLevels = GradeLevels::all();
        $categories = Categories::with('subjects')->get(); 

             
        // Load schedules to display on Dashboard
        $schedules = $user->schedules()->orderBy(DB::raw("CASE 
            WHEN day_of_week = 'Monday' THEN 1
            WHEN day_of_week = 'Tuesday' THEN 2
            WHEN day_of_week = 'Wednesday' THEN 3
            WHEN day_of_week = 'Thursday' THEN 4
            WHEN day_of_week = 'Friday' THEN 5
            WHEN day_of_week = 'Saturday' THEN 6
            WHEN day_of_week = 'Sunday' THEN 7
        END"))->get();

        return view('Teacher.Teacher_Dashboard', 
        compact('user', 'tutorProfile', 'gradeLevels', 'categories','schedules'));
    }


}

