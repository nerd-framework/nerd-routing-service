<?php

namespace Nerd\Framework\Providers;

use Nerd\Framework\Routing\Router;
use Nerd\Framework\Routing\RouterContract;
use Nerd\Framework\Routing\RouterException;
use Nerd\Framework\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    private $routeSourceKey = 'router.routes';

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function register()
    {
        $router = new Router();

        $router->setGlobalRouteHandler(function ($action, $args) {
            return $this->app->invoke($action, array_values($args));
        });

        $router->setGlobalMiddlewareHandler(function ($action, $args, $next) {
            $args[] = $next;

            return $this->app->invoke($action, array_values($args));
        });

        $this->app['app.router'] = $router;

        $this->loadRoutes($router);
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['app.router'];
    }

    private function loadRoutes(Router $router)
    {
        $routes = $this->app->config($this->routeSourceKey);

        if (!function_exists($routes)) {
            throw new RouterException("Configuration key \"{$this->routeSourceKey}\" does not point to valid function");
        }

        $routes($router);
    }
}
