<?php

namespace Tests\Unit\Models;

use App\Models\Endpoint;
use App\Models\Extension;
use App\Models\PhoneNumber;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function can_get_extension()
    {
        $company = $this->createCompany();

        $user = $this->createUser($company);

        /** @var PhoneNumber $number */
        $number = factory(PhoneNumber::class)->create([
            'company_id' => $company->id,
            'number' => '+15005550006',
        ]);

        $endpoint = factory(Endpoint::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'pointable_type' => 'endpoint',
            'pointable_id' => $number->id,
        ]);

        $this->assertInstanceOf(Endpoint::class, $user->endpoints->first());
    }

    /** @test */
    public function can_format_for_dial()
    {
        $company = $this->createCompany();

        $user = $this->createUser($company);

        /** @var PhoneNumber $number */
        $number = factory(PhoneNumber::class)->create([
            'company_id' => $company->id,
            'number' => '+15005550006',
        ]);

        $endpoint = factory(Endpoint::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'pointable_type' => 'number',
            'pointable_id' => $number->id,
        ]);

        $expected = [
            [
                'type' => 'number',
                'to' => $number->number,
            ]
        ];

        $this->assertEquals($expected, $number->formatForDial());
    }

    /** @test */
    public function can_format_for_dial_for_multiple_endpoints()
    {
        $company = $this->createCompany();

        $user = $this->createUser($company);

        /** @var PhoneNumber $number */
        $number = factory(PhoneNumber::class)->create([
            'company_id' => $company->id,
            'number' => '+15005550006',
        ]);

        $endpoint = factory(Endpoint::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'pointable_type' => 'number',
            'pointable_id' => $number->id,
        ]);

        /** @var PhoneNumber $number */
        $number2 = factory(PhoneNumber::class)->create([
            'company_id' => $company->id,
            'number' => '+15005550007',
        ]);

        $endpoint2 = factory(Endpoint::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'pointable_type' => 'number',
            'pointable_id' => $number2->id,
        ]);

        $expected = [
            [
                'type' => 'number',
                'to' => $number->number,
            ],
            [
                'type' => 'number',
                'to' => $number2->number,
            ]
        ];

        $this->assertEquals($expected, $user->formatForDial());
    }

    /** @test */
    public function can_get_user_extension()
    {
        $company = $this->createCompany();

        $user = $this->createUser($company);

        $extension = factory(Extension::class)->create([
            'company_id' => $company->id,
            'extendable_type' => 'user',
            'extendable_id' => $user->id
        ]);

        $this->assertEquals($extension->id, $user->extension->id);
    }
}
