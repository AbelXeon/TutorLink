<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'experience_years',
        'availability_status',
        'qualification',
        'max_students',
        'price_per_hour',
        'grade_level',
        'total_reviews',
        'mode',
    ];
}
