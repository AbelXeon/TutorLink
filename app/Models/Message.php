<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    
    use HasFactory;
    protected $table = 'messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message_text',
        'file_path',
        'file_type',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
