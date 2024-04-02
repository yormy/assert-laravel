<?php

declare(strict_types=1);

namespace Yormy\AssertLaravel;

use Illuminate\Support\ServiceProvider;

class AssertLaravelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/assert-laravel.php', 'assert-laravel');
    }
}
