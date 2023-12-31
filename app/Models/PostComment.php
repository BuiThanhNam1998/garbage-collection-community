<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $fillable = [
        'content',
        'user_id',
        'commentable_id',
        'commentable_type',
        'parent_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function garbagePost()
    {
        return $this->belongsTo(GarbagePost::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function moderationQueue()
    {
        return $this->morphOne(ModerationQueue::class, 'moderatable', 'object_type', 'object_id');
    }

    public function userActivityLogs() {
        return $this->morphMany(UserActivityLog::class, 'loggable');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}
