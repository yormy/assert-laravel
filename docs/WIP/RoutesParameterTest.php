<?php

namespace Tests\Feature\Routes;

use Tests\Feature\Customer\CustomerTest;
use Tests\Helper\RoutesHelper;

class RoutesParameterTest extends CustomerTest
{
    //    public function testRoutesAsCompany()
    //    {
    //        $company = $this->getCompanyCustomer();
    //        $this->routesTester($company);
    //    }
    //

    private $parameters;

    protected function setUp(): void
    {
        parent::setUp();

        $customer = $this->getCustomer();

        $this->parameters = [
            'country_id' => '157',
            'postcode' => '3066ab',
            'postal_code' => '2055ab',
            'token' => 'xxxxxx',
            'houseNumber' => '11',
            'house_number' => '99',
            'address' => 1,
            'document' => 1,
            'invoice' => 1,
            'code' => '999', // discount code
            'postbox' => $customer->postboxes()->first()->id,
            'postbox_id' => $customer->postboxes()->first()->id,
            'letter' => $customer->postboxes()->first()->letters()->first()->id,
            'letter_id' => $customer->postboxes()->first()->letters()->first()->id,
            'full_parcelbox_number' => $customer->parcelboxes->first()->url_slug,
            'topup_id' => 1,
            'registration_id' => 1,
            'news' => 1,
            'location_consent' => 1,
            'full_postbox_number' => $customer->postboxes()->first()->url_slug,
            'retina' => 1,
            'is_contents' => 1,
            'carrier_id' => 1,
            'address_id' => 1,
            'recipient' => 1,
            'expected' => 1,
            'parcel_id' => 1,
            'index' => 1,
            'shipment_id' => 1,
        ];
    }
    //    public function testParam()
    //    {
    //        $customer = $this->getCustomer();
    //
    //        $routes = new RoutesHelper();
    //        $adminRoutesGet = $routes->getRoutesParameterizeGet();
    //
    //
    //
    //        foreach ($adminRoutesGet as $route) {
    //            $name = $route->getName();
    //            $this->debugOut($name);
    //
    //            if ($name !== "admin.password.request") {
    //                $response = $this->actingAs($customer)
    //                    ->followingRedirects()
    //                    ->get(route($name,$this->parameters));
    //
    //                $this->assertStringContainsString('Inloggen', $response->getContent());
    //            }
    //        }
    //    }

    public function testRoutesAsPerson()
    {
        $person = $this->getCustomer();

        $routes = new RoutesHelper();
        $customerRoutesGet = $routes->getCustomerRoutesGet();
        foreach ($customerRoutesGet as $route) {
            $name = $route->getName();

            $this->debugOut($name);

            $response = $this->actingAs($person)
                ->get(route($name, $this->parameters));

            $code = $response->getStatusCode();

            $redirects = [
                'customer.locale',
                'customer.login',
                'customer.logout',
                'customer.password.request',
                'customer.password.reset',
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
                'customer.account.ip-addresses.create', // this should be a post , there are parameters in the call?
                'customer.account.documents.show',
                'customer.priparcel.activate',
                'customer.prioffice.news.show',
            ];

            $allowedResults = [200];
            if (in_array($name, $redirects)) {
                $allowedResults = [302];
            }

            $missing = [
                'customer.account.create',
                'customer.signup.verify',
                //                'customer.priparcel.dashboard',
                //                'customer.priparcel.news.show',
                //                'customer.priparcel.settings.index',
                //                'customer.priparcel.settings.details.index'
            ];

            if (in_array($name, $missing)) {
                $allowedResults = [404];
            }

            $errors = [
                'customer.signup.discount-code',
                'customer.account.financial.topup.success',
                'customer.pripost.signup.verify',
                'customer.pripost.signup.mandate.checkTransaction',
                'customer.pripost.location-consent.create',
                'customer.pripost.location-consent.show',
                'customer.pripost.settings.others.index',
                'customer.priparcel.signup.verify',
                'customer.priparcel.settings.details.index',
                'customer.priparcel.settings.get-parcel-points',
                'customer.priparcel.settings.recipients.show',
                'customer.priparcel.settings.recipients.edit',
                'customer.priparcel.parcels.expected.edit',
                'customer.priparcel.parcels.parcel-image',
                'customer.priparcel.parcels.parcel-thumb',
                'customer.priparcel.shipments.shipment-image',
                'customer.priparcel.shipments.shipment-thumb',
                'customer.priparcel.shipments.payments.index',
                'customer.pritelecom.signup.verify',
                'customer.priparcel.shipments.payment.complete',
                'customer.prioffice.signup.discount-code',

            ];

            if (in_array($name, $errors)) {
                $allowedResults = [500];
            }

            $this->assertContains($code, $allowedResults, $name.' ('.route($name, $this->parameters).')');
        }
    }

    // in test basic class, call to show real exception in the test
    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler
        {
            public function __construct()
            {
                //
            }

            public function report(\Exception $e)
            {
                //
            }

            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }

    private function debugOut(string $name, string $code = null)
    {
        fwrite(STDERR, print_r('.', true));

        //        $message =  '????';
        //        if ($name !== 'postcode-nl::address') {
        //            $message = $name . " ( " . route($name, $this->parameters) . " )";
        //            if ($code) {
        //                $message .= ' = ' . $code;
        //            }
        //        }
        //        fwrite(STDERR, print_r(PHP_EOL . $message, true));
    }
}
