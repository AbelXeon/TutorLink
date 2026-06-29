<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    public function subjects()
    {
        return $this->hasMany(Subjects::class, 'category_id');
    }

}
