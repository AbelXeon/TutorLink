<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    /** @use HasFactory<\Database\Factories\AdminActionFactory> */
    use HasFactory;
      // Explicitly maps this model to your admin_actions table
    protected $table = 'admin_actions';

    protected $fillable = [
        'admin_id',
        'target_user_id',
        'action_type',
        'description'
    ];

    // Relationship to the Admin who did the action
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relationship to the User who was affected by the action
    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
