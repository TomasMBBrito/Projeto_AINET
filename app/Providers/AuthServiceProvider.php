<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        //User::class => UserPolicy::class,
    ];


    public function boot(): void
    {
        $this->registerPolicies();

        // Gate global para verificar se o utilizador é da direção
        Gate::define('manageSite', function (User $user) {
            return $user->type == "board";
        });
    }
}
