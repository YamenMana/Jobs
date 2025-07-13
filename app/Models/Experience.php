<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    /**
     * الحصول على المستخدم الذي يملك هذه الخبرة.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}