<?php

namespace Tests\Unit\Models;

use App\Models\PhoneNumber;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PhoneNumberTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function can_format_number_for_dialer()
    {
        $company = $this->createCompany();

        /** @var PhoneNumber $phoneNumber */
        $phoneNumber = factory(PhoneNumber::class)->create([
            'company_id' => $company->id,
            'number' => '+15005550006',
        ]);

        $expected = [
            [
                'type' => 'number',
                'to' => $phoneNumber->number,
            ]
        ];

        $this->assertEquals($expected, $phoneNumber->formatForDial());
    }
}
