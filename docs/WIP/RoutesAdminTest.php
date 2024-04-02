<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\Feature\Admin\AdminTest;
use Tests\Helper\RoutesHelper;

class RoutesAdminTest extends AdminTest
{
    public function testRoutesAsAdmin(): void
    {
        $admin = $this->getSuperAdmin();

        $routes = new RoutesHelper();
        $adminRoutesGet = $routes->getAdminRoutesGet();
        foreach ($adminRoutesGet as $route) {
            $name = $route->getName();
            $this->debugOut($name);

            $response = $this->actingAs($admin)
                ->get(route($name));

            $code = $response->getStatusCode();

            $redirects = [
            ];

            $allowedResults = [200];
            if (in_array($name, $redirects)) {
                $allowedResults = [302];
            }

            $this->assertContains($code, $allowedResults, $name.' ('.route($name).')');
        }
    }

    private function debugOut(string $name, ?string $code = null): void
    {
        $message = $name.' ( '.route($name).' )';
        if ($code) {
            $message .= ' = '.$code;
        }

        fwrite(STDERR, print_r(PHP_EOL.$message, true));
    }
}
