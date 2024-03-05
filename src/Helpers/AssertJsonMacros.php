<?php declare(strict_types=1);

namespace Yormy\AssertLaravel\Helpers;

use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse;

class AssertJsonMacros
{
    public static function register()
    {
        TestResponse::macro('assertJsonDataArrayHasElement', function ($fieldname, $expectedValue, int $expectedItems = 1) {
            $items = collect(json_decode($this->getContent(), true)['data']);
            $found = (bool) $items->firstWhere($fieldname, $expectedValue);
            PHPUnit::assertTrue($found, "$fieldname with $expectedValue not found");
        });

        TestResponse::macro('assertJsonDataItemHasElement', function ($fieldname, $expectedValue, int $expectedItems = 1) {
            $items = collect(json_decode($this->getContent(), true)['data']);

            $found = false;
            if ($items->get($fieldname) === $expectedValue) {
                $found = true;
            }
            PHPUnit::assertTrue($found, "$fieldname with $expectedValue not found");
        });

        TestResponse::macro('assertJsonDataItemNotHasElement', function ($fieldname, $expectedValue, int $expectedItems = 1) {
            $items = collect(json_decode($this->getContent(), true)['data']);

            $found = false;
            if ($items->get($fieldname) === $expectedValue) {
                $found = true;
            }
            PHPUnit::assertFalse($found, "$fieldname with $expectedValue found");
        });

        TestResponse::macro('assertJsonDataArrayNotHasElement', function ($fieldname, $expectedValue, int $expectedItems = 1) {
            $items = collect(json_decode($this->getContent(), true)['data']);
            $found = (bool) $items->firstWhere($fieldname, $expectedValue);
            PHPUnit::assertFalse($found, "$fieldname with $expectedValue not found");
        });

        TestResponse::macro('assertJsonDataHasTranslatedElement', function ($fieldname, $language, $expectedValue, int $expectedItems = 1) {
            $items = collect(json_decode($this->getContent(), true)['data']);
            $found = false;

            if (is_array($items[$fieldname])) {
                $translations = $items[$fieldname];
                if ($translations[$language] === $expectedValue) {
                    $found = true;
                }
                PHPUnit::assertTrue($found, "$fieldname with $expectedValue not found");
            }
        });

    }
}
