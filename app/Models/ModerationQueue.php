<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModerationQueue extends Model
{
    protected $table = 'moderation_queue';
    
    protected $fillable = [
        'object_id',
        'object_type',
        'status',
        'admin_id',
    ];

    public function moderatable()
    {
        return $this->morphTo('object', 'object_type', 'object_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
