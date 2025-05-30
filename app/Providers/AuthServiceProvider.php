<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * As políticas de autorização do projeto.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //User::class => UserPolicy::class,
    ];

    /**
     * Regista quaisquer serviços de autenticação/autorização.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate global para verificar se o utilizador é da direção
        Gate::define('manageSite', function (User $user) {
            return $user->type == "board";
        });
    }
}
