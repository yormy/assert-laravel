<?php declare(strict_types=1);

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


    public static function register()
    {
        TestResponse::macro('getDataCount', function () {
            return AssertJsonMacros::getDataCount($this);
        });

        TestResponse::macro('assertJsonDataArrayEmpty', function () {
            $itemCount = AssertJsonMacros::getDataCount($this);
            PHPUnit::assertTrue($itemCount === 0,'Array not empty');
        });

        TestResponse::macro('assertJsonDataArrayNotEmpty', function () {
            $itemCount = AssertJsonMacros::getDataCount($this);
            PHPUnit::assertTrue($itemCount !== 0,'Array empty');
        });

        TestResponse::macro('assertJsonDataArrayCount', function (int $expectedCount) {
            $itemCount = AssertJsonMacros::getDataCount($this);
            PHPUnit::assertTrue($itemCount === $expectedCount,"Expected count: $expectedCount does not match actual count: ". $itemCount );
        });

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
