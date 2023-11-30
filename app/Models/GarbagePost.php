<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarbagePost extends Model
{
    protected $fillable = [
        'description',
        'location',
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
}
