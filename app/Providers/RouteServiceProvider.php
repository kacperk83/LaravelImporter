<?php

namespace App\Providers;

//use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @param Router $router
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/Routes/adminRoutes.php');
            require app_path('Http/Routes/v1Routes.php');
        });


        //$this->mapApiRoutes();

        //$this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
//    protected function mapWebRoutes()
//    {
//        Route::middleware('web')
//             ->namespace($this->namespace)
//             ->group(base_path('routes/web.php'));
//    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
//    protected function mapApiRoutes()
//    {
//        Route::prefix('api')
//             ->middleware('api')
//             ->namespace($this->namespace)
//             ->group(base_path('routes/api.php'));
//    }
}
