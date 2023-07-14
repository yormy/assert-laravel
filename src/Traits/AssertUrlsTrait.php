<?php

namespace Yormy\AssertLaravel\Traits;

trait AssertUrlsTrait
{
    public function assertUrlIs($url)
    {
        $this->assertTrue(url()->current() === $url);
    }

    public function assertUrlIsNot($url)
    {
        $this->assertTrue(url()->current() !== $url);
    }
}
