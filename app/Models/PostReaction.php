<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostReaction extends Model
{
    protected $fillable = [
        'user_id',
        'garbage_post_id',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function garbagePost()
    {
        return $this->belongsTo(GarbagePost::class);
    }
}
