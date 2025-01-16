<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
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

    public function favoriteContent()
    {
        return $this->belongsToMany(Media::class, 'favorite_content', 'user_id', 'media_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
