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
        'total_reviews',
        'teaching_mode',
    ];

      public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     public function gradeLevels()
    {
        return $this->belongsToMany(
            GradeLevels::class, 
            'tutor_grade_levels', 
            'tutor_profile_id', 
            'grade_level_id'
        );
}

 public function subjects()
{
    return $this->belongsToMany(
        Subjects::class, 
        'tutor_subjects', 
        'tutor_profile_id', 
        'subject_id'
    );
}
}