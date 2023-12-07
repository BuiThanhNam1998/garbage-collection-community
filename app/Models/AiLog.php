<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLog extends Model
{
    protected $fillable = [
        'garbage_post_id',
        'verification_status',
        'status',
    ];

    public function post()
    {
        return $this->belongsTo(GarbagePost::class, 'garbage_post_id', 'id');
    }
}
