<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('user.role', function($user, ...$roleID){

            $userID = $user->id;
            //check have Role by UserID
            $userRepository = $this->app['App\Repositories\UserRepositoryInterface'];
            $hasRole =  $userRepository->getRolesIDByUserID($userID, $roleID);
            
            if ($hasRole) {
                return true;
            }
            return false;
        });
    }
}
