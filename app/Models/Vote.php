<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'poll_id',
        'option_id',
        'user_id',
    ];

    public function option() {
        return $this->belongsTo(PollOption::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function poll() {
        return $this->belongsTo(Poll::class);
    }
}
