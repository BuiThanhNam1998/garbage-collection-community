<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'country_id',
        'latitude',
        'longitude',
    ];

    public function posts()
    {
        return $this->morphMany(GarbagePost::class, 'locationable');
    }
}
