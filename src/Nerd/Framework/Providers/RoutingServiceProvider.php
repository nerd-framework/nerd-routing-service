<?php

namespace Nerd\Framework\Providers;

use Nerd\Framework\Routing\Router;
use Nerd\Framework\Routing\RouterContract;
use Nerd\Framework\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $router = new Router();
        $this->getApplication()->bind(RouterContract::class, $router);
        $routes = $this->getApplication()->config('router.source');
        $routes($router);
    }

    public static function provides()
    {
        return [RouterContract::class];
    }
}
