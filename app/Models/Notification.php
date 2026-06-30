<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationFactory> */
    use HasFactory;
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'notification_type',
        'title',
        'message',
        'action_url',
        'read_at' 
    ];

    protected $casts = [
        'read_at' => 'boolean'
    ];

    // Get the user associated with this notification
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
