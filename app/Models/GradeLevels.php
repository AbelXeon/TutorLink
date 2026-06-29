<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevels extends Model
{
    /** @use HasFactory<\Database\Factories\GradeLevelsFactory> */
    use HasFactory;
    
    protected $table = 'grade_levels';
    protected $fillable = ['name'];

}
