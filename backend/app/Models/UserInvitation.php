<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'invite_user_id',
        'invitee_user_id',
        'is_successful',
        'invitation_date',
    ];
}
