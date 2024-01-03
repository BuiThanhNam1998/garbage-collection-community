<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCheck extends Model
{
    protected $fillable = [
        'status',
        'details',
        'checked_at',
    ];
}
