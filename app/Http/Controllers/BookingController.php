<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
     // Shows the booking form
    public function ShowBookingForm($tutor_id)
    {
        // Fetch the tutor profile and join with user table to get the name
        $tutor = TutorProfile::join('users', 'tutor_profiles.user_id', '=', 'users.id')
            ->select('tutor_profiles.id', 'users.first_name', 'users.last_name')
            ->where('tutor_profiles.id', $tutor_id)
            ->firstOrFail();

        return view('Booking.Booking', compact('tutor'));
    }

    // Handles the booking submission
    public function StoreBooking(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutor_profiles,id',
            'message'  => 'required|string|max:1000',
            'dates'    => 'required|array',
            'dates.*'  => 'required|date|after_or_equal:today',
            'times'    => 'required|array',
            'times.*'  => 'required',
        ]);

        $student = Auth::user();

        // 1. Group dates and times into a single array
        $slots = [];
        foreach ($request->dates as $key => $date) {
            $slots[] = [
                'date' => $date,
                'time' => $request->times[$key] ?? '00:00'
            ];
        }

        // 2. Save the booking in the database
        $bookingId = DB::table('bookings')->insertGetId([
            'tutor_id'       => $request->tutor_id,
            'student_id'     => $student->id,
            'status'         => 'pending',
            'message'        => $request->message,
            'selected_slots' => json_encode($slots), // Convert array to JSON string
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 3. Find the tutor's User ID to send them a notification
        $tutorProfile = TutorProfile::findOrFail($request->tutor_id);

        // 4. Create an automated notification for the teacher
        DB::table('notifications')->insert([
            'user_id'           => $tutorProfile->user_id, // Receives the notification
            'notification_type' => 'booking',
            'title'             => 'New Booking Request 📅',
            'message'           => $student->first_name . ' has requested a lesson booking with you.',
            'action_url'        => route('Teacher.Teacher_Dashboard'),
            'is_read'           => false,
            'created_at'        => now(),
        ]);

        return redirect()->route('Student.Student_Dashboard')
            ->with('success', 'Booking request sent successfully!');
    }

    // 5. Handles Teacher accepting a booking
    public function AcceptBooking($id)
    {
        $booking = DB::table('bookings')->where('id', $id)->first();
        
        if (!$booking) {
            return back()->with('error', 'Booking request not found.');
        }

        // Update booking status
        DB::table('bookings')->where('id', $id)->update(['status' => 'accepted']);

        $tutorUser = Auth::user();

        // Send confirmation notification to the student
        DB::table('notifications')->insert([
            'user_id'           => $booking->student_id,
            'notification_type' => 'booking_accepted',
            'title'             => 'Booking Request Approved! 🎉',
            'message'           => 'Tutor ' . $tutorUser->first_name . ' has approved your requested slots.',
            'action_url'        => route('Student.Student_Dashboard'),
            'is_read'           => false,
            'created_at'        => now(),
        ]);

        // Check if a conversation already exists between this student and tutor to prevent duplicates
        $existingConvo = DB::table('conversations')
            ->where('student_id', $booking->student_id)
            ->where('tutor_id', $tutorUser->id)
            ->first();

        if ($existingConvo) {
            // Update timestamp of existing thread
            DB::table('conversations')
                ->where('id', $existingConvo->id)
                ->update(['last_message_at' => now()]);
        } else {
            // Create a brand new single thread
            DB::table('conversations')->insert([
                'student_id'      => $booking->student_id,
                'tutor_id'        => $tutorUser->id,
                'last_message_at' => now(),
            ]);
        }

        return back()->with('success', 'You have accepted this booking request successfully!');
    }

    // 6. Handles Teacher declining a booking
    public function DeclineBooking($id)
    {
        $booking = DB::table('bookings')->where('id', $id)->first();
        
        if (!$booking) {
            return back()->with('error', 'Booking request not found.');
        }

        // Update booking status
        DB::table('bookings')->where('id', $id)->update(['status' => 'rejected']);

        $tutorUser = Auth::user();

        // Send notification to the student
        DB::table('notifications')->insert([
            'user_id'           => $booking->student_id,
            'notification_type' => 'booking_declined',
            'title'             => 'Booking Request Declined ❌',
            'message'           => 'Tutor ' . $tutorUser->first_name . ' has declined your booking request.',
            'action_url'        => route('Student.Student_Dashboard'),
            'is_read'           => false,
            'created_at'        => now(),
        ]);

        return back()->with('success', 'Booking request declined.');
    }

}
