<?php 

namespace App\Models;

use App\Enums\User\GarbagePost\Status;
use Illuminate\Database\Eloquent\Model;

class GarbagePost extends Model
{
    protected $fillable = [
        'description',
        'locationable_id',
        'locationable_type',
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

    public function locationable()
    {
        return $this->morphTo();
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
