<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    public function posts()
    {
        return $this->morphMany(GarbagePost::class, 'locationable');
    }

    public function cities() 
    {
        return $this->hasMany(City::class);
    }
}
