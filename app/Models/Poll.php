<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'question',
        'duration',
        'creator_id',
        'status',
        'published_at',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }
}
