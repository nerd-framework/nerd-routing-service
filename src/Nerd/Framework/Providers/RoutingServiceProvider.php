<?php

namespace Nerd\Framework\Providers;

use Nerd\Framework\Routing\Router;
use Nerd\Framework\Routing\RouterContract;
use Nerd\Framework\Routing\RouterException;
use Nerd\Framework\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $router = new Router();

        $router->setGlobalRouteHandler(function ($action, $args) {
            return $this->getApplication()->invoke($action, $args);
        });
        $router->setGlobalMiddlewareHandler(function ($action, $args, $next) {
            return $this->getApplication()->invoke($action, array_merge($args, ["next" => $next]));
        });

        $this->getApplication()->bind(RouterContract::class, $router);
        $routes = $this->getApplication()->config('router.source');
        if (!function_exists($routes)) {
            throw new RouterException("Configuration key \"router.source\" must point to routes source function");
        }
        $routes($router);
    }

    public static function provides()
    {
        return [RouterContract::class];
    }
}
