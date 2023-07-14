<?php

namespace Yormy\BedrockUsers\Tests\Features\Traits;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;

trait DisableExceptionHandling
{
    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler
        {
            public function __construct()
            {
                //
            }
            //
            //            public function report(\Exception $e)
            //            {
            //                //
            //            }

            public function render($request, \Throwable $e)
            {
                throw $e;
            }
        });
    }
}
