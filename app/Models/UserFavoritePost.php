<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavoritePost extends Model
{
    protected $table = 'users_favorite_posts';
    protected $primaryKey = ['user_id', 'garbage_post_id'];
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'garbage_post_id',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(GarbagePost::class, 'garbage_post_id', 'id');
    }
}
