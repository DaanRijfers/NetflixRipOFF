<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentPreference extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'description_id');
    }
}
