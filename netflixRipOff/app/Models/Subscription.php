<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'quality_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function quality()
    {
        return $this->belongsTo(Quality::class);
    }
}
