<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

        // Filter by Teaching Mode (online, in-person, hybrid)
        if ($request->filled('teaching_mode')) {
            $query->where('teaching_mode', $request->teaching_mode);
        }

        $tutors = $query->get();

        // Dynamically compute average ratings and counts for each tutor card
        foreach ($tutors as $tutor) {
            $tutorReviews = DB::table('reviews')
                ->join('bookings', 'reviews.booking_id', '=', 'bookings.id')
                ->where('bookings.tutor_id', $tutor->user_id)
                ->get();
            
            $tutor->average_rating = $tutorReviews->avg('rating') ?: 0.0;
            $tutor->reviews_count = $tutorReviews->count();
        }

        // NEW: Fetch all tutor IDs this logged-in student has an accepted booking with
        $allowedTutorIds = [];
        if (Auth::check()) {
            $allowedTutorIds = DB::table('bookings')
                ->where('student_id', Auth::id())
                ->where('status', 'accepted')
                ->pluck('tutor_id')
                ->toArray();
        }

        // Load options for dropdowns
        $categories = Categories::with('subjects')->get();
        $locations = Location::all();

        return view('Search.Tutor_view', compact('tutors', 'categories', 'locations', 'allowedTutorIds'));
    }

    // 2. Show Tutor Profile with Secure Review & Chat Authorization
    public function showTutorProfile($username)
    {
        $tutor = TutorProfile::whereHas('user', function ($q) use ($username) {
                $q->where('username', $username);
            })
            ->where('availability_status', 'active')
            ->with(['user.location', 'user.schedules', 'subjects.category', 'gradeLevels'])
            ->firstOrFail();

        // Retrieve verified student reviews for this tutor
        $reviews = DB::table('reviews')
            ->join('bookings', 'reviews.booking_id', '=', 'bookings.id')
            ->join('users', 'bookings.student_id', '=', 'users.id')
            ->where('bookings.tutor_id', $tutor->user_id)
            ->select('reviews.*', 'users.first_name', 'users.last_name', 'users.profile_image')
            ->orderBy('reviews.created_at', 'desc')
            ->get();

        $averageRating = $reviews->avg('rating') ?: 0.0;

        // Check if the current authenticated user has an accepted unreviewed booking with this tutor
        $unreviewedBooking = null;
        $canMessage = false;

        if (Auth::check()) {
            $unreviewedBooking = DB::table('bookings')
                ->where('tutor_id', $tutor->user_id)
                ->where('student_id', Auth::id())
                ->where('status', 'accepted')
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                      ->from('reviews')
                      ->whereColumn('reviews.booking_id', 'bookings.id');
                })
                ->first();

            // NEW: Verify if this user has permission to message the tutor
            $canMessage = DB::table('bookings')
                ->where('student_id', Auth::id())
                ->where('tutor_id', $tutor->user_id)
                ->where('status', 'accepted')
                ->exists();
        }

        return view('Teacher.Teacher_profile', compact('tutor', 'reviews', 'averageRating', 'unreviewedBooking', 'canMessage'));
    }

    // 3. Secure Review Submission Controller Engine
    public function storeReview(Request $request, $bookingId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();

        // Verify the booking belongs to this user, is accepted, and is valid
        $booking = DB::table('bookings')
            ->where('id', $bookingId)
            ->where('student_id', $user->id)
            ->where('status', 'accepted')
            ->first();

        if (!$booking) {
            abort(403, 'Unauthorized. No accepted booking matches your session credentials.');
        }

        // Prevent double reviews on the same lesson booking
        $existingReview = DB::table('reviews')->where('booking_id', $bookingId)->exists();
        if ($existingReview) {
            return back()->withErrors(['error' => 'You have already reviewed this lesson session.']);
        }

        // Secure DB insert (cleans comments with strip_tags for XSS protection)
        DB::table('reviews')->insert([
            'booking_id' => $bookingId,
            'rating'     => $request->rating,
            'comment'    => strip_tags($request->comment), 
            'created_at' => now()
        ]);

        // Update the count on tutor profiles
        DB::table('tutor_profiles')
            ->where('user_id', $booking->tutor_id)
            ->increment('total_reviews');

        return back()->with('success', 'Thank you! Your review has been securely recorded.');
    }
}