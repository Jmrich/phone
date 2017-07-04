<?php

namespace Tests\Unit\Models\Observers;

use App\Models\Endpoint;
use App\Models\PhoneNumber;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EndpointObserverTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function user_cannot_have_more_than_ten_endpoints()
    {
        $this->expectException(\Exception::class);

        $company = $this->createCompany();

        $user = $this->createUser($company);

        $number = factory(PhoneNumber::class)->create([
            'company_id' => $company->id,
            'number' => '+15005550006',
        ]);

        factory(Endpoint::class, 11)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'pointable_type' => 'number',
            'pointable_id' => $number->id,
        ]);
    }
}
