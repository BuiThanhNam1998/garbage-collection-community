<?php 

namespace App\Models;

use App\Enums\User\GarbagePost\Status;
use Illuminate\Database\Eloquent\Model;

class GarbagePost extends Model
{
    protected $fillable = [
        'description',
        'street_id',
        'latitude',
        'longitude',
        'date',
        'user_id',
        'verification_status',
        'ai_verification_status',
        'manual_verification_date',
        'ai_verification_date',
    ];

    public function images()
    {
        return $this->hasMany(GarbagePostImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function moderationQueue()
    {
        return $this->morphOne(ModerationQueue::class, 'moderatable', 'object_type', 'object_id');
    }

    public function userActivityLogs() {
        return $this->morphMany(UserActivityLog::class, 'loggable');
    }

    public function sharedBy() {
        return $this->belongsToMany(User::class, 'post_shares', 'garbage_post_id', 'user_id');
    }

    public function scopeApproved($q) 
    {
        return $q->where('verification_status', Status::APPROVED)
            ->orWhere('ai_verification_status', Status::APPROVED);
    }

    public function scopePending($q) 
    {
        return $q->where('verification_status', Status::PENDING)
            ->where('ai_verification_status', Status::PENDING);
    }
}
