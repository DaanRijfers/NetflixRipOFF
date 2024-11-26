<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['genre_id', 'title', 'description', 'runtime'];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function watchtime()
    {
        return $this->hasMany(ProfileWatchtime::class);
    }

    public function viewingGuides()
    {
        return $this->belongsToMany(ViewingGuide::class, 'viewing_guide_movie');
    }
}
