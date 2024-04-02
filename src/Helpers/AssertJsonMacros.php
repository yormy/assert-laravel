<?php

declare(strict_types=1);

namespace Yormy\AssertLaravel\Helpers;

use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse;

class AssertJsonMacros
{
    public static function getDataCount($self): int
    {
        $items = collect(json_decode($self->getContent(), true)['data']);

        return $items->count();
    }

    public static function register(): void
    {
        TestResponse::macro('getDataCount', function () {
            return AssertJsonMacros::getDataCount($this); //@phpstan-ignore-line
        });

        TestResponse::macro('assertJsonDataArrayEmpty', function (): void {
            $itemCount = AssertJsonMacros::getDataCount($this); //@phpstan-ignore-line
            PHPUnit::assertTrue($itemCount === 0, 'Array not empty');
        });

        TestResponse::macro('assertJsonDataArrayNotEmpty', function (): void {
            $itemCount = AssertJsonMacros::getDataCount($this); //@phpstan-ignore-line
            PHPUnit::assertTrue($itemCount !== 0, 'Array empty');
        });

        TestResponse::macro('assertJsonDataArrayCount', function (int $expectedCount): void {
            $itemCount = AssertJsonMacros::getDataCount($this); //@phpstan-ignore-line
            PHPUnit::assertTrue($itemCount === $expectedCount, "Expected count: {$expectedCount} does not match actual count: ".$itemCount);
        });

        TestResponse::macro('assertJsonDataArrayHasElement', function ($fieldname, $expectedValue, int $expectedItems = 1): void {
            $items = collect(json_decode($this->getContent(), true)['data']); //@phpstan-ignore-line
            $found = (bool) $items->firstWhere($fieldname, $expectedValue);
            PHPUnit::assertTrue($found, "{$fieldname} with {$expectedValue} not found");
        });

        TestResponse::macro('assertJsonDataItemHasElement', function ($fieldname, $expectedValue, int $expectedItems = 1): void {
            $items = collect(json_decode($this->getContent(), true)['data']); //@phpstan-ignore-line

            $found = false;
            if ($items->get($fieldname) === $expectedValue) {
                $found = true;
            }
            PHPUnit::assertTrue($found, "{$fieldname} with {$expectedValue} not found");
        });

        TestResponse::macro('assertJsonDataItemNotHasElement', function ($fieldname, $expectedValue, int $expectedItems = 1): void {
            $items = collect(json_decode($this->getContent(), true)['data']); //@phpstan-ignore-line

            $found = false;
            if ($items->get($fieldname) === $expectedValue) {
                $found = true;
            }
            PHPUnit::assertFalse($found, "{$fieldname} with {$expectedValue} found");
        });

        TestResponse::macro('assertJsonDataArrayNotHasElement', function ($fieldname, $expectedValue, int $expectedItems = 1): void {
            $items = collect(json_decode($this->getContent(), true)['data']); //@phpstan-ignore-line
            $found = (bool) $items->firstWhere($fieldname, $expectedValue);
            PHPUnit::assertFalse($found, "{$fieldname} with {$expectedValue} not found");
        });

        TestResponse::macro('assertJsonDataHasTranslatedElement', function ($fieldname, $language, $expectedValue, int $expectedItems = 1): void {
            $items = collect(json_decode($this->getContent(), true)['data']); //@phpstan-ignore-line
            $found = false;

            if (is_array($items[$fieldname])) {
                $translations = $items[$fieldname];
                if ($translations[$language] === $expectedValue) {
                    $found = true;
                }
                PHPUnit::assertTrue($found, "{$fieldname} with {$expectedValue} not found");
            }
        });
    }
}
