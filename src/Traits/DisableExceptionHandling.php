<?php

declare(strict_types=1);

namespace Yormy\AssertLaravel\Traits;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;

trait DisableExceptionHandling
{
    protected function disableExceptionHandling(): void
    {
        $this->app->instance(ExceptionHandler::class, new class() extends Handler {
            public function __construct()
            {
            }

            public function render($request, \Throwable $e): void
            {
                throw $e;
            }
        });
    }
}
