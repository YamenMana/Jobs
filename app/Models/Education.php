<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    /**
     * الحصول على المستخدم الذي يملك هذه التعليمات.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}