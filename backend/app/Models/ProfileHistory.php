<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'media_id',
        'watch_time',
        'completed',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
