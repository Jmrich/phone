<?php

namespace Tests;

use App\Models\Company;
use App\Models\User;
use App\Services\Twilio\Twilio;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        $this->disableExceptionHandling();
    }

    protected function initializeTwilioTestInstance()
    {
        $twilio = new Twilio(\Config::get('twilio.test_auth_id'), \Config::get('twilio.test_auth_token'));

        $this->app->instance(Twilio::class, $twilio);
    }

    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(\Exception $e) {}
            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }
    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }

    protected function createCompany(): Company
    {
        return factory(Company::class)->create();
    }

    protected function createUser($company): User
    {
        return factory(User::class)->create([
            'company_id' => $company->id,
        ]);
    }
}
