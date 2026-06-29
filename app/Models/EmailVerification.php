<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    /** @use HasFactory<\Database\Factories\EmailVerificationsFactory> */
    use HasFactory;
        // Explicitly define the table name if it is 'email_verifications'
    protected $table = 'email_verifications';

    // Disable default timestamps if you are managing created_at manually, 
    // or keep true if you want Laravel to manage updated_at / created_at.
    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'email',
        'code',
        'attempt_count',
        'is_used',
        'purpose',
        'expires_at',
        'verified_at',
        'created_at'
    ];

    // Ensure expires_at and verified_at are cast as dates
    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'is_used' => 'boolean'
    ];

}
