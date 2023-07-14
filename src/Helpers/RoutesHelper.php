<?php

namespace Yormy\AssertLaravel\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class RoutesHelper
{
    const IGNORE_URL = [
        //        '/', // no route name
    ];

    const DEBUG_URL = [
        '_debugbar*',
        '_dusk*',
        'horizon*',
    ];

    public function getFilteredRoutesGet(string $routeStartsWith = null)
    {
        $routes = $this->getRoutesGet();

        return $this->filterRoutes($routes, $routeStartsWith);
    }

    public function getFilteredRoutesPost(string $routeStartsWith = null)
    {
        $routes = $this->getRoutesPost();

        return $this->filterRoutes($routes, $routeStartsWith);
    }

    private function filterRoutes(Collection $routes, string $routeStartsWith = null): Collection
    {
        if (! $routeStartsWith) {
            return $routes;
        }

        return $routes->filter(fn ($value) => fnmatch("$routeStartsWith.*", $value->getName()));
    }
    //
    //
    //    private function filterCustomerRoutes(Collection $routes): Collection
    //    {
    //        return $routes->filter(function ($value) {
    //            return fnmatch('concern.*', $value->getName());
    //        });
    //    }

    public function getRoutesGet()
    {
        $routes = Route::getRoutes()->get('GET');
        $routes = collect($routes);

        $routes = $this->removeDebugUrl($routes);
        $routes = $this->removeIgnoreUrl($routes);
        $routes = $this->removeParameterizedUrl($routes);

        foreach ($routes as $route) {
            $name = $route->getName();

            if (! $name) {
                $message = "* Name missing for $route->uri \r\n";
                fwrite(STDERR, print_r($message, true));
            }
        }

        return $routes;
    }

    public function getRoutesPost()
    {
        $routes = Route::getRoutes()->get('POST');
        $routes = collect($routes);

        $routes = $this->removeParameterizedUrl($routes);

        foreach ($routes as $route) {
            $name = $route->getName();

            if (! $name) {
                $message = "* Name missing for $route->uri \r\n";
                fwrite(STDERR, print_r($message, true));
            }
        }

        return $routes;
    }

    //
    //
    //
    //
    //
    //
    //
    //
    //    public function getAdminRoutesGet()
    //    {
    //        $routes = $this->getRoutesGet();
    //        return $this->filterAdminRoutes($routes);
    //    }
    //

    //
    //    public function getRoutesParameterizeGet()
    //    {
    //        $routes = Route::getRoutes()->get('GET');
    //        $routes = collect($routes);
    //
    //        $routes = $this->filterCustomerRoutes($routes);
    //
    //        $routes = $this->removeDebugUrl($routes);
    //        $routes = $this->removeIgnoreUrl($routes);
    //        $routes = $this->onlyParameterizedUrl($routes);
    //
    //        foreach ($routes as $route) {
    //            $name = $route->getName();
    //            if (!$name) {
    //                $message = "* Name missing for $route->uri \r\n";
    //                fwrite(STDERR, print_r($message, true));
    //            }
    //        }
    //
    //        return $routes;
    //    }
    //
    //
    private function removeParameterizedUrl(Collection $routes): Collection
    {
        return $routes->filter(function ($value) {
            $parameterRegex = '/{.*}/';

            preg_match($parameterRegex, $value->uri, $matches);
            if (count($matches) === 0) {
                return true;
            }

            // Also return optional parameter urls
            foreach ($matches as $match) {
                if (false === strpos($match, '?}')) {
                    return false;
                }
            }

            return true;
        });
    }

    private function onlyParameterizedUrl(Collection $routes): Collection
    {
        return $routes->filter(function ($value) {
            $parameterRegex = '/{.*}/';

            preg_match($parameterRegex, $value->uri, $matches);
            if (count($matches) === 0) {
                return true;
            }

        });
    }

    private function removeDebugUrl(Collection $routes): Collection
    {
        $filterdRoutes = $routes;
        foreach (self::DEBUG_URL as $debugRoute) {
            $filterdRoutes = $this->removeUrl($filterdRoutes, $debugRoute);
        }

        return $filterdRoutes;
    }

    private function removeIgnoreUrl(Collection $routes): Collection
    {
        $filterdRoutes = $routes;
        foreach (self::IGNORE_URL as $debugRoute) {
            $filterdRoutes = $this->removeUrl($filterdRoutes, $debugRoute);
        }

        return $filterdRoutes;
    }

    private function removeUrl(Collection $routes, string $pattern): Collection
    {
        return $routes->filter(function ($value) use ($pattern) {
            return ! fnmatch($pattern, $value->uri);
        });
    }
}
