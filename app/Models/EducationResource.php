<?php

namespace App\Models;

use App\Enums\EducationResource\Status;
use Illuminate\Database\Eloquent\Model;

class EducationResource extends Model
{
    protected $fillable = [
        'title',
        'description',
        'link',
        'duration_minutes',
        'status',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', Status::ACTIVE);
    }
}
