<?php declare(strict_types=1);

namespace Yormy\AssertLaravel\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

trait RouteHelperTrait
{
    private function dumpRouteName(string $routeName)
    {
        $routes = $this->findRouteName($routeName);
        dump($routes);
    }

    private function findRouteName(string $routeName): Collection
    {
        return $this->findRoute('route', $routeName);
    }

    private function findRouteUrl(string $url): Collection
    {
        return $this->findRoute('url', $url);
    }

    private function findRoute($field, $searchValue): Collection
    {
        $routeCollection = Route::getRoutes();

        $routes = collect([]);
        foreach ($routeCollection as $value) {

            $route = [
                'url' => $value->uri,
                'route' => $value->getName(),
                'action' => $value->getActionName(),
                'methods' => $value->methods()[0],
                'middleware' => implode(',', $value->middleware()),
                'controller' => $value->getControllerClass(),
            ];

            $routes->push($route);
        }

        $items = $routes->filter(function ($item) use ($field, $searchValue) {
            return false !== stristr($item[$field], $searchValue);
        });

        return $items;
    }
}
