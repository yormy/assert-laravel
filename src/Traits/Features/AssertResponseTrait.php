<?php

namespace Yormy\AssertLaravel\Traits\Features;

use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

trait AssertResponseTrait
{
    protected function assertHasResponseCode($response, string $field)
    {
        $data = Arr::dot(json_decode($response->getContent(), true));
        $this->assertEquals(Arr::get($data, 'code'), $field);
    }

    protected function assertHasValidationError($response, string $field, $message = null)
    {
        $this->assertNotNull($response);

        $this->assertStatusValidationError($response);
        $data = Arr::dot(json_decode($response->getContent(), true));

        if ($message) {
            $this->assertTrue(Arr::exists($data, 'data.errors.'.$field.'.0'), $message);
        } else {
            $this->assertTrue(Arr::exists($data, 'data.errors.'.$field.'.0'));
        }
    }

    public function assertHasNotValidationError($response, string $error, $message = null)
    {
        $data = Arr::dot(json_decode($response->getContent(), true));
        $this->assertTrue(! Arr::exists($data, 'errors.'.$error.'.0'), $message);
    }

    public function assertStatusUnAuthenticated($response, $data = [], $message = null)
    {
        $this->assertStatusHelper(Response::HTTP_UNAUTHORIZED, $response, $data, $message);
    }

    public function assertStatusServerError($response, $data = [], $message = null)
    {
        $this->assertStatusHelper(Response::HTTP_INTERNAL_SERVER_ERROR, $response, $data, $message);
    }

    public function assertStatusOk($response, $data = [], $message = null)
    {
        $this->assertStatusHelper(Response::HTTP_OK, $response, $data, $message);
    }

    public function assertStatusCreated($response, $data = [], $message = null)
    {
        $this->assertStatusHelper(Response::HTTP_CREATED, $response, $data, $message);
    }

    public function assertStatusValidationError($response, $data = [], $message = null)
    {
        $this->assertStatusHelper(Response::HTTP_UNPROCESSABLE_ENTITY, $response, $data, $message); // 422 validation error
    }

    // |--------------------------------------------------------------------------
    // | Helpers
    // |--------------------------------------------------------------------------
    private function assertStatusHelper(int $status, $response, array $data = [], $message = null): void
    {
        if ($response->status() !== $status) {
            $this->logFailed($response, $data);
        }

        if ($message) {
            $this->assertEquals($status, $response->status(), $message);
        } else {
            $this->assertEquals($status, $response->status());
        }
    }

    private function logFailed($response, $data)
    {
        try {
            $responseData = $response->json();
        } catch (\Exception) {
            $responseData = '';
        }

        Log::debug([$response->status(), $data, $responseData]);
    }
}
