<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;
     protected $table = 'bookings';

    protected $fillable = [
        'tutor_id',
        'student_id',
        'session_date',
        'start_time',
        'end_time',
        'location',
        'note',
        'status',
        'accepted_at',
        'rejected_at'
    ];

    protected $casts = [
        'session_date' => 'date',
        'accepted_at'  => 'datetime',
        'rejected_at'  => 'datetime'
    ];

    // Get the student associated with the booking
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Get the tutor associated with the booking
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    // Get the review for this booking (if completed)
    public function review()
    {
        return $this->hasOne(Review::class, 'booking_id');
    }
}
