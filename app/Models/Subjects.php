<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
     protected $table = 'subjects';

        protected $fillable = 
        [
            'category_id',
             'name'
             ];

             
    // NEW: Defines the relationship back to the parent Category
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

}
