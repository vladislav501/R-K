<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('is-admin', function ($user) {
            Log::info('Checking is-admin Gate', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'result' => $user->role === 'admin',
            ]);
            return $user->role === 'admin';
        });

        Gate::define('is-manager', function ($user) {
            Log::info('Checking is-manager Gate', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'result' => $user->role === 'manager',
            ]);
            return $user->role === 'manager';
        });
    }
}