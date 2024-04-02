<?php

declare(strict_types=1);

namespace Yormy\AssertLaravel\Traits;

trait AssertUrlsTrait
{
    public function assertUrlIs($url): void
    {
        $this->assertTrue(url()->current() === $url);
    }

    public function assertUrlIsNot($url): void
    {
        $this->assertTrue(url()->current() !== $url);
    }
}
