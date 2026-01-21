<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', fn(User $user) => $user->role === 'admin');
        Gate::define('isPeternak', fn(User $user) => $user->role === 'peternak');
    }
}
