<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarbagePostImage extends Model
{
    protected $fillable = ['image_path', 'type', 'garbage_post_id'];

    public function garbagePost()
    {
        return $this->belongsTo(GarbagePost::class);
    }
}
