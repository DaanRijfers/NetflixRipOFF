<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePreference extends Model
{
    use HasFactory;

    protected $fillable = ['profile_id', 'viewing_guide_id', 'genre_id'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function viewingGuide()
    {
        return $this->belongsTo(ViewingGuide::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
