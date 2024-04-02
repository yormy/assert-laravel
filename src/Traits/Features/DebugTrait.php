<?php

declare(strict_types=1);

namespace Yormy\AssertLaravel\Traits\Features;

use Illuminate\Support\Facades\Route;

trait DebugTrait
{
    public function showRoutes(): void
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->getName().'('.json_encode($route->getAction()['middleware'] ?? []).')';
        });
        dd($routes);
    }

    public function showUrls(): void
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->uri();
        });
        dd($routes);
    }
}
