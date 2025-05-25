<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Model::class => Policy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Contoh gate untuk fitur PKL
        Gate::define('manage-pkl', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::before(function ($user, $ability) {
            if (str_starts_with(request()->path(), 'admin/shield/roles')) {
                return $user->hasRole('admin');
            }
        });
    }
}
