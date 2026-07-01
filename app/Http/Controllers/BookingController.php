<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Booking;
use App\Models\Notification;
use Carbon\Carbon;

class BookingController extends Controller
{
    // 1. Show the Booking Form
    public function showBookingForm($username)
    {
        // Find the tutor by their secure username
        $tutor = User::where('username', $username)
            ->whereHas('role', function($q) {
                $q->where('role_type', 'Teacher');
            })
            ->firstOrFail();

        $tutorProfile = TutorProfile::where('user_id', $tutor->id)->firstOrFail();
        $schedules = $tutor->schedules; 

        
 // Generate the rolling next 7 days from today
        $availabilities = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($index = $i);
            $availabilities[] = [
                'date_string' => $date->format('Y-m-d'),
                'day_name'    => $date->format('l'), 
                'formatted'   => $date->format('D, M d'), 
                'is_today'    => $date->isToday(),
            ];
        }

        return view('Booking.Booking', 
        compact('tutor', 'tutorProfile', 'schedules', 'availabilities'));


    }

    // 2. Save the Booking Request
    public function storeBooking(Request $request, $username)
    {
        $request->validate([
            'session_date' => 'required|date|after_or_equal:today',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
            'note'         => 'nullable|string|max:500',
        ]);

        $tutor = User::where('username', $username)->firstOrFail();
        $student = Auth::user();

        // Security check: You cannot book yourself
        if ($tutor->id === $student->id) {
            return back()->withInput()->withErrors(['error' => 'You cannot book a tutoring session with yourself.']);
        }

        // 1. DOUBLE-BOOKING CHECK (Overlapping time slots for the same date)
        $overlap = Booking::where('tutor_id', $tutor->id)
            ->where('session_date', $request->session_date)
            ->where('status', 'accepted') // Only overlapping accepted bookings are conflicts
            ->where(function($q) use ($request) {
                $q->where(function($sub) use ($request) {
                    $sub->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                });
            })->exists();

        if ($overlap) {
            return back()->withInput()->withErrors(['error' => 'This timeslot has already been booked and accepted by another student. Please select a different time or date.']);
        }

        // 2. Create the Booking with 'pending' status
        $booking = Booking::create([
            'tutor_id'     => $tutor->id,
            'student_id'   => $student->id,
            'session_date' => $request->session_date,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'note'         => $request->note,
            'status'       => 'pending',
        ]);

        // 3. Create a Notification record for the Tutor
        Notification::create([
            'user_id'           => $tutor->id,
            'notification_type' => 'booking_request',
            'title'             => 'New Booking Request',
            'message'           => 'You have received a new booking request from ' . $student->first_name . ' for ' . Carbon::parse($request->session_date)->format('M d, Y') . '.',
            'action_url'        => route('tutor.dashboard'), // Sends them straight to their dashboard to accept/decline
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Booking requested successfully! You will be notified once the tutor accepts.');
    }



     // 3. Accept Booking Request (Tutors accept booking requests)
    public function acceptBooking($id)
    {
        $tutor = Auth::user();

        // Fetch the pending booking securely
        $booking = Booking::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->where('status', 'pending')
            ->firstOrFail();

        // Update the booking status to accepted
        $booking->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        // Auto-create or retrieve existing chat Conversation between them
        $conversation = \App\Models\Conversation::firstOrCreate([
            'student_id' => $booking->student_id,
            'tutor_id'   => $tutor->id
        ]);

        // Notify the Student and point the REDIRECT URL directly to the new Conversation
        Notification::create([
            'user_id'           => $booking->student_id,
            'notification_type' => 'booking_accepted',
            'title'             => 'Booking Request Accepted!',
            'message'           => 'Tutor ' . $tutor->first_name . ' accepted your booking request for ' . Carbon::parse($booking->session_date)->format('M d, Y') . '. Click here to start chatting.',
            'action_url'        => route('messages.show', $tutor->username), // <-- SECURED TO USERNAME
        ]);

        return back()->with('success', 'Booking accepted successfully! A secure chat channel has been unlocked.');
    }



    // 4. Decline/Reject Booking Request
    public function rejectBooking($id)
    {
        $tutor = Auth::user();

        $booking = Booking::where('id', $id)
            ->where('tutor_id', $tutor->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $booking->update([
            'status' => 'rejected',
            'rejected_at' => now()
        ]);

        // Notify the Student
        Notification::create([
            'user_id'           => $booking->student_id,
            'notification_type' => 'booking_rejected',
            'title'             => 'Booking Request Declined',
            'message'           => 'Tutor ' . $tutor->first_name . ' declined your booking request for ' . Carbon::parse($booking->session_date)->format('M d, Y') . '.',
            'action_url'        => route('student.dashboard'),
        ]);

        return back()->with('success', 'Booking request declined successfully.');
    }

    
}
