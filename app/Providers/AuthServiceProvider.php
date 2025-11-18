<?php

namespace App\Providers;

use App\Models\WorkoutProgram;
use App\Policies\WorkoutProgramPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        WorkoutProgram::class => WorkoutProgramPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manage-admin', function ($user) {
            return in_array($user->role, ['admin', 'coach'], true);
        });
    }
}
