<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPostQueue extends Model
{
    protected $table = 'ai_post_queue';

    protected $fillable = [
        'garbage_post_id',
        'admin_id',
    ];

    public function admin() 
    {
        return $this->belongsTo(Admin::class);
    }

    public function post()
    {
        return $this->belongsTo(GarbagePost::class, 'garbage_post_id', 'id');
    }
}
