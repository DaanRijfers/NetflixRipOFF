<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'payment_method',
        'failed_login_attempts',
        'subscription_id',
    ];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
}
