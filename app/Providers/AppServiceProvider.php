<?php

namespace App\Providers;

use App\Models\Job;
use App\Policies\JobPolicy;
use Illuminate\Support\Facades\Gate;

use App\Models\User;


use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */





    public function boot(): void
    {
        Gate::policy(Job::class, JobPolicy::class);
        Gate::define('admin', fn (User $user) => $user->type === 'admin');
        Gate::define('employer', fn (User $user) => $user->type === 'employer');
        Gate::define('job_seeker', fn (User $user) => $user->type === 'job_seeker');

        // ... أي إعدادات أخرى
    }
}
