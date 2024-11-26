<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewingGuide extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'viewing_guide_movie');
    }

    public function episodes()
    {
        return $this->belongsToMany(Episode::class, 'viewing_guide_episode');
    }

    public function profilePreferences()
    {
        return $this->hasMany(ProfilePreference::class);
    }
}
