<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('create_note', function (User $user) {
            $userRole = $user->role;

            switch($userRole){
                case 'admin':
                    return true;
                case 'editor':
                    return true;
                case 'common':
                    return false;
                default:
                    return false;
            }
        });

        Gate::define('read_note', function (User $user) {
            $userRole = $user->role;

            switch($userRole){
                case 'admin':
                    return true;
                case 'editor':
                    return true;
                case 'common':
                    return false;
                default:
                    return false;
            }
        });

        Gate::define('update_note', function (User $user) {
            $userRole = $user->role;

            switch($userRole){
                case 'admin':
                    return true;
                case 'editor':
                    return true;
                case 'common':
                    return false;
                default:
                    return false;
            }
        });

        Gate::define('delete_note', function (User $user) {
            $userRole = $user->role;

            switch($userRole){
                case 'admin':
                    return true;
                case 'editor':
                    return true;
                case 'common':
                    return false;
                default:
                    return false;
            }
        });
    }
}
