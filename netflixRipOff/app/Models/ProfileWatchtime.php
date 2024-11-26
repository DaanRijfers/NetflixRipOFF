<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileWatchtime extends Model
{
    use HasFactory;

    protected $fillable = ['profile_id', 'movie_id', 'episode_id', 'watch_time'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}
