<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'release_date',
        'duration',
        'media_type',
        'season_number',
        'episode_number',
        'language_id',
        'file_path',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'media_category');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'media_genre');
    }

    public function restrictions()
    {
        return $this->belongsToMany(Restriction::class, 'media_restriction');
    }

    public function subtitles()
    {
        return $this->hasMany(Subtitle::class);
    }

    public function quality()
    {
        return $this->belongsToMany(Quality::class, 'media_qualities');
    }
}
