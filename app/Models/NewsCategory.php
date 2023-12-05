<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'admin_id',
        'status',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
