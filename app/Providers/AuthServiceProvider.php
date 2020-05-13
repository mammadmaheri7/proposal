<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('define-supervisor',function ($user){
           return $user->role->title == 'admin';
        });

        Gate::define('department-head',function($user){
            return $user->role->title == 'group_manager';
        });

        Gate::define('modify-user-admin',function ($user){
            return $user->role->title == 'admin';
        });

        Gate::define('list-users',function ($user){
            return $user->role->title == 'admin';
        });

        Gate::define('professor',function ($user){
            return $user->role->title=='professor';
        });
        Passport::routes();
    }
}
