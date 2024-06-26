<?php

declare(strict_types=1);

namespace Yormy\AssertLaravel\Traits\Features;

trait ReportTrait
{
    public function report($message): void
    {
        if (is_array($message) || is_object($message)) {
            fwrite(STDERR, (string) print_r($message)); // bool passed in.. how?
        } else {
            fwrite(STDERR, (string) $message);
        }
        fwrite(STDERR, PHP_EOL);
    }
}
