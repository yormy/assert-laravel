<?php

namespace Yormy\AssertLaravel\Traits\Features;

use Illuminate\Support\Facades\Route;

trait DebugTrait
{
    public function showRoutes()
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->getName().'('.json_encode($route->getAction()['middleware'] ?? []).')';
        });
        dd($routes);
    }

    public function showUrls()
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->uri();
        });
        dd($routes);
    }
}
