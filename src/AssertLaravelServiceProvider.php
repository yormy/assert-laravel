<?php

namespace Yormy\AssertLaravel;

use Illuminate\Support\ServiceProvider;

class AssertLaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/assert-laravel.php', 'assert-laravel');
    }
}
