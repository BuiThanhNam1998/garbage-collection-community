<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostShare extends Model
{
    protected $fillable = [
        'garbage_post_id',
        'user_id',
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
