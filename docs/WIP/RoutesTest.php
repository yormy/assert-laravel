<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Tests\Feature\Customer\CustomerTest;
use Tests\Helper\RoutesHelper;

class RoutesTest extends CustomerTest
{
    //    public function testRoutesAsCompany()
    //    {
    //        $company = $this->getCompanyCustomer();
    //        $this->routesTester($company);
    //    }
    //
    public function testPreventAdminRoutesAsCustomer(): void
    {
        $customer = $this->getCustomer();

        $routes = new RoutesHelper();
        $adminRoutesGet = $routes->getAdminRoutesGet();
        foreach ($adminRoutesGet as $route) {
            $name = $route->getName();
            $this->debugOut($name);

            if ($name !== 'admin.password.request') {
                $response = $this->actingAs($customer)
                    ->followingRedirects(route($name))
                    ->get(route($name));

                $this->assertStringContainsString('Inloggen', $response->getContent());
            }
        }
    }

    public function testRoutesAsPerson(): void
    {
        $person = $this->getCustomer();

        $routes = new RoutesHelper();
        $customerRoutesGet = $routes->getCustomerRoutesGet();
        foreach ($customerRoutesGet as $route) {
            $name = $route->getName();
            $this->debugOut($name);

            $response = $this->actingAs($person)
                ->get(route($name));

            $code = $response->getStatusCode();

            $redirects = [
                'customer.login',
                'customer.logout',
                'customer.password.request',
                'customer.forgot_username',
                'customer.account.terms.agree',
                'customer.account.upload-id.index',
                'customer.account.documents.create',
                'customer.pripost.terms.agree',
                'customer.pripost.signup.index',
                'customer.priparcel.terms.agree',
                'customer.prioffice.dashboard',
                'customer.prioffice.rental-agreement.download',
                'customer.prioffice.rental-agreement.show',
                'customer.prioffice.rental-agreement.signed',
                'customer.prioffice.rental-agreement.index',

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
