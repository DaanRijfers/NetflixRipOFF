<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaQuality extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'quality',
    ];

    /**
     * Define the relationship with the Media model.
     */
    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * Define the relationship with the Quality model.
     */
    public function quality()
    {
        return $this->belongsTo(Quality::class);
    }
}
