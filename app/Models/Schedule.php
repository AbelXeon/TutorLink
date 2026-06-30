<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'tutor_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
