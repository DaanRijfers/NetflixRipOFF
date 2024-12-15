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
        'profile_picture_path',
        'date_of_birth',
        'language_id',
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

    public function genrePreferences()
    {
        return $this->belongsToMany(Genre::class, 'profile_genre_preference');
    }

    public function categoryPreferences()
    {
        return $this->belongsToMany(Category::class, 'profile_category_preference');
    }

    public function restrictionPreferences()
    {
        return $this->belongsToMany(Restriction::class, 'profile_restriction_preference');
    }
}
