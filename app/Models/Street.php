<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    protected $fillable = [
        'name',
        'city_id',
        'latitude',
        'longitude',
    ];

    public function posts()
    {
        return $this->hasMany(GarbagePost::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
