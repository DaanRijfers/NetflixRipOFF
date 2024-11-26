<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_picture_path',
        'is_child_locked',
        'description_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function description()
    {
        return $this->belongsTo(ContentPreference::class, 'description_id');
    }

    public function watchtime()
    {
        return $this->hasMany(ProfileWatchtime::class);
    }

    public function preferences()
    {
        return $this->hasMany(ProfilePreference::class);
    }
}
