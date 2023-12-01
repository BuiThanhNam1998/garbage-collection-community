<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotificationSetting extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'is_enabled',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
