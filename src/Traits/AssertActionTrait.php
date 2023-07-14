<?php

namespace Yormy\AssertLaravel\Traits;

use PHPUnit\Framework\Assert as PHPUnitAssert;

trait AssertActionTrait
{
    public function assertActionUsesFormRequest(string $controller, string $method, string $form_request)
    {
        PHPUnitAssert::assertTrue(is_subclass_of($form_request, 'Illuminate\\Foundation\\Http\\FormRequest'), $form_request.' is not a type of Form Request');

        try {
            $reflector = new \ReflectionClass($controller);
            $action = $reflector->getMethod($method);
        } catch (\ReflectionException $exception) {
            PHPUnitAssert::fail('Controller action could not be found: '.$controller.'@'.$method);
        }

        PHPUnitAssert::assertTrue($action->isPublic(), 'Action "'.$method.'" is not public, controller actions must be public.');

        $actual = collect($action->getParameters())->contains(function ($parameter) use ($form_request) {
            return $parameter->getType() instanceof \ReflectionNamedType && $parameter->getType()->getName() === $form_request;
        });

        PHPUnitAssert::assertTrue($actual, 'Action "'.$method.'" does not have validation using the "'.$form_request.'" Form Request.');
    }

    public function assertActionUsesMiddleware($controller, $method, $middleware = null)
    {
        $router = resolve(\Illuminate\Routing\Router::class);

        if (is_null($middleware)) {
            $middleware = $method;
            $method = '__invoke';
        }

        if ($method === '__invoke') {
            $route = $router->getRoutes()->getByAction($controller);

            PHPUnitAssert::assertNotNull($route, 'Unable to find route for invokable controller ('.$controller.')');
        } else {
            $route = $router->getRoutes()->getByAction($controller.'@'.$method);

            PHPUnitAssert::assertNotNull($route, 'Unable to find route for controller action ('.$controller.'@'.$method.')');
        }

        if (is_array($middleware)) {
            PHPUnitAssert::assertSame([], array_diff($middleware, $route->gatherMiddleware()), 'Controller action does not use middleware ('.implode(', ', $middleware).')');
        } else {
            PHPUnitAssert::assertTrue(in_array($middleware, $route->gatherMiddleware()), 'Controller action does not use middleware ('.$middleware.')');
        }
    }
}
