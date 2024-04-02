<?php

declare(strict_types=1);

namespace Yormy\AssertLaravel\Traits;

use PHPUnit\Framework\Assert as PHPUnitAssert;

trait AssertValidationTrait
{
    public function assertValidationRules(array $expected, array $actual): void
    {
        \Illuminate\Testing\Assert::assertArraySubset($this->normalizeRules($expected), $this->normalizeRules($actual));
    }

    public function assertExactValidationRules(array $expected, array $actual): void
    {
        PHPUnitAssert::assertEquals($this->normalizeRules($expected), $this->normalizeRules($actual));
    }

    public function assertValidationRuleContains($rule, string $class): void
    {
        if (is_object($rule)) {
            PHPUnitAssert::assertInstanceOf($rule, $class);

            return;
        }

        $matches = array_filter($this->expandRules($rule), function ($rule) use ($class) {
            return $rule instanceof $class;
        });

        if (empty($matches)) {
            PHPUnitAssert::fail('Failed asserting rule contains '.$class);
        }
    }

    private function normalizeRules(array $rules)
    {
        return array_map([$this, 'expandRules'], $rules);
    }

    private function expandRules($rule)
    {
        return is_string($rule) ? explode('|', $rule) : $rule;
    }
}
