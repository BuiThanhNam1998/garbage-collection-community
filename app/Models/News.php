<?php

namespace App\Models;

use App\Enums\News\Status;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'category_id',
        'admin_id',
        'status',
        'publish_at'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class);
    }

    public function scopePublished($q)
    {
        return $q->where('status', Status::PUBLISHED);
    }
}
