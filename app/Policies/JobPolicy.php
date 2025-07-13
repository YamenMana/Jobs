<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // الجميع يمكنهم عرض قائمة الوظائف
    }

    public function view(User $user, Job $job): bool
    {
        return true; // الجميع يمكنهم رؤية تفاصيل الوظيفة
    }

    public function create(User $user): bool
    {
        return $user->type === 'employer'; // فقط أصحاب العمل
    }

    public function update(User $user, Job $job): bool
    {
        return $user->id === $job->user_id; // فقط مالك الوظيفة
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->id === $job->user_id; // فقط مالك الوظيفة
    }
}