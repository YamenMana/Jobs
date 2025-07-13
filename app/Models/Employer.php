<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    /**
     * الحصول على جميع الوظائف المرتبطة بهذا صاحب العمل.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}