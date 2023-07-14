<?php

namespace Yormy\AssertLaravel\Traits;

use PHPUnit\Framework\Assert as PHPUnitAssert;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

trait AdditionalAssertionsTrait
{
    public function createFormRequest(string $form_request, array $data = [])
    {
        return $form_request::createFromBase(SymfonyRequest::create('', 'POST', $data));
    }

    public static function assertArrayStructure(array $structure, array $actual)
    {
        foreach ($structure as $key => $type) {
            if (is_array($type) && $key === '*') {
                PHPUnitAssert::assertIsArray($actual);

                foreach ($actual as $data) {
                    static::assertArrayStructure($structure['*'], $data);
                }
            } elseif (is_array($type) && array_key_exists($key, $structure)) {
                if (is_array($structure[$key])) {
                    static::assertArrayStructure($structure[$key], $actual[$key]);
                }
            } else {
                switch ($type) {
                    case 'string':
                        PHPUnitAssert::assertIsString($actual[$key]);
                        break;
                    case 'integer':
                        PHPUnitAssert::assertIsInt($actual[$key]);
                        break;
                    case 'number':
                        PHPUnitAssert::assertIsNumeric($actual[$key]);
                        break;
                    case 'boolean':
                        PHPUnitAssert::assertIsBool($actual[$key]);
                        break;
                    case 'array':
                        PHPUnitAssert::assertIsArray($actual[$key]);
                        break;
                    default:
                        PHPUnitAssert::fail('unexpected type: '.$type);
                }
            }
        }
    }
}
