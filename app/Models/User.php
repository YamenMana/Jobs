<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Profile;


use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * السمات التي يمكن تعيينها جماعيًا.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'company_name',
        'company_description',
        'phone',
        'address'
    ];





    /**
     * السمات التي يجب إخفاؤها عند الترميز.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * السمات التي يجب تحويلها إلى نوع معين.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * الحصول على جميع الخبرات المرتبطة بهذا المستخدم.
     */
    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }
    public function jobs()
    {
        return $this->hasMany(Job::class); // صاحب العمل يمكنه نشر وظائف
    }
    /**
     * الحصول على جميع التعليمات المرتبطة بهذا المستخدم.
     */
    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    /**
     * الحصول على جميع الطلبات المرتبطة بهذا المستخدم.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    // تصفية المستخدمين حسب النوع
    public function scopeEmployers($query)
    {
        return $query->where('type', 'employer');
    }

    public function scopeJobSeekers($query)
    {
        return $query->where('type', 'job_seeker');
    }
      public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
