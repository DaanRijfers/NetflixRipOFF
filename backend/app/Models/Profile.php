<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'favorite_animal', 
        'media_preference',
        'language_id',
        'profile_picture', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function watchlist()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function history()
    {
        return $this->hasMany(ProfileHistory::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'profile_genre_preferences', 'profile_id', 'genre_id');
    }

    public function restrictionPreferences()
    {
        return $this->belongsToMany(Restriction::class, 'profile_restriction_preference');
    }
}
