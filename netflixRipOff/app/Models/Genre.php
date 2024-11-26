<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function preferences()
    {
        return $this->hasMany(ProfilePreference::class);
    }
}
