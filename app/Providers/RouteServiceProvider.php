<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Caminho para onde o utilizador será redirecionado após login/verificação.
     */
    public const HOME = '/dashboard';

    /**
     * Aqui é onde defines as rotas da aplicação.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Web routes (públicas e protegidas)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Auth routes (login, registo, verificação, reset)
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));

            // // API routes (opcional)
            // Route::middleware('api')
            //     ->prefix('api')
            //     ->group(base_path('routes/api.php'));
        });
    }
}
