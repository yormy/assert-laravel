<?php

namespace Yormy\AssertLaravel\Traits;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Assert as PHPUnitAssert;
use Yormy\AssertLaravel\Traits\Features\ReportTrait;

trait AssertRoutesTrait
{
    use ReportTrait;

    public function assertRouteIs($routeName)
    {
        $this->assertTrue(url()->current() === route($routeName));
    }

    public function assertRouteIsNot($routeName)
    {
        $this->assertTrue(url()->current() !== route($routeName));
    }

    public function assertRouteUsesFormRequest(string $routeName, string $formRequest)
    {
        $controllerAction = collect(Route::getRoutes())
            ->filter(fn (\Illuminate\Routing\Route $route) => $route->getName() == $routeName)
            ->pluck('action.controller');

        PHPUnitAssert::assertNotEmpty($controllerAction, 'Route "'.$routeName.'" is not defined.');
        PHPUnitAssert::assertCount(1, $controllerAction, 'Route "'.$routeName.'" is defined multiple times, route names should be unique.');

        $controller = $controllerAction->first();
        $method = '__invoke';
        if (strstr($controllerAction->first(), '@')) {
            [$controller, $method] = explode('@', $controllerAction->first());
        }

        $this->assertActionUsesFormRequest($controller, $method, $formRequest);
    }

    public function assertRouteNotUsesMiddleware(Kernel $appKernel, string $routeName, array $middlewares, $showReport = false)
    {
        $unusedMiddlewares = $this->getUnusedMiddleware($appKernel, $routeName, $middlewares);

        if ($showReport) {
            $this->report($routeName.' - OK');
        }

        PHPUnitAssert::assertCount(
            1,
            $unusedMiddlewares,
            "Route `$routeName` uses not expected `".implode(', ', $unusedMiddlewares).'` middleware(s)'
        );
    }

    public function assertRouteUsesMiddleware(Kernel $appKernel, string $routeName, array $middlewares, $showReport = false)
    {
        $unusedMiddlewares = $this->getUnusedMiddleware($appKernel, $routeName, $middlewares);

        if ($showReport) {
            $this->report($routeName.' - OK');
        }

        PHPUnitAssert::assertCount(
            0,
            $unusedMiddlewares,
            "Route `$routeName` does not use expected `".implode(', ', $unusedMiddlewares).'` middleware(s)'
        );
    }

    private function getUnusedMiddleware(Kernel $appKernel, string $routeName, array $middlewares): array
    {
        $middlewareGroups = $appKernel->getMiddlewareGroups();

        $route = $this->findOneRouteByName($routeName);
        $usedMiddlewares = $this->collectUsedMiddlewares($route, $middlewareGroups);

        $unusedMiddlewares = array_diff($middlewares, $usedMiddlewares);

        return $unusedMiddlewares;
    }

    private function collectUsedMiddlewares($route, array $middlewareGroups): array
    {
        $usedMiddlewares = $route->gatherMiddleware();

        foreach ($usedMiddlewares as $middleware) {
            if ($groupMembers = Arr::get($middlewareGroups, $middleware, null)) {
                $usedMiddlewares = array_merge($usedMiddlewares, $groupMembers);
            }
        }

        return $usedMiddlewares;
    }

    private function findOneRouteByName($routeName)
    {
        $routesFound = collect(Route::getRoutes())->filter(fn ($route) => $route->getName() === $routeName);

        self::assertCount(1, $routesFound, "Multiple routes found with the name: $routeName");

        return $routesFound->first();
    }

    private function findAllRoutesByName($routeName, array $exceptRoutes = []): Collection
    {
        $routesFound = collect(Route::getRoutes())->filter(function ($route) use ($routeName, $exceptRoutes) {

            if (stripos($route->getName(), $routeName) !== false &&
                ! in_array($route->getName(), $exceptRoutes)
            ) {
                return true;
            }

            return false;
        });

        return $routesFound;
    }

    //    public function assertRouteUsesMiddleware(string $routeName, array $middlewares, bool $exact = false)
    //    {
    //        $router = resolve(\Illuminate\Routing\Router::class);
    //
    //        $route = $router->getRoutes()->getByName($routeName);
    //        PHPUnitAssert::assertNotNull($route, "Unable to find route for name `$routeName`");
    //
    //        $usedMiddlewares = $route->gatherMiddleware();
    //
    //        if ($exact) {
    //            $unusedMiddlewares = array_diff($middlewares, $usedMiddlewares);
    //            $extraMiddlewares = array_diff($usedMiddlewares, $middlewares);
    //
    //            $messages = [];
    //
    //            if ($extraMiddlewares) {
    //                $messages[] = "uses unexpected `" . implode(', ', $extraMiddlewares) . "` middlware(s)";
    //            }
    //
    //            if ($unusedMiddlewares) {
    //                $messages[] = "doesn't use expected `" . implode(', ', $unusedMiddlewares) . "` middlware(s)";
    //            }
    //
    //            $messages = implode(" and ", $messages);
    //
    //            PHPUnitAssert::assertTrue(count($unusedMiddlewares) + count($extraMiddlewares) === 0, "Route `$routeName` " . $messages);
    //        } else {
    //            $unusedMiddlewares = array_diff($middlewares, $usedMiddlewares);
    //
    //            PHPUnitAssert::assertTrue(count($unusedMiddlewares) === 0, "Route `$routeName` does not use expected `" . implode(', ', $unusedMiddlewares) . "` middleware(s)");
    //        }
    //    }
}
