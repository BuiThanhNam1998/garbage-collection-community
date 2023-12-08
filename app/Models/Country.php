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

    public function streets()
    {
        return $this->hasManyThrough(Street::class, City::class);
    }

    public function cities() 
    {
        return $this->hasMany(City::class);
    }
}
