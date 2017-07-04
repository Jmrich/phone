<?php

namespace Tests\Unit\Models;

use App\Exceptions\InvalidTypeException;
use App\Models\Company;
use App\Models\Extension;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExtensionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function cannot_create_with_invalid_type()
    {
        $this->expectException(InvalidTypeException::class);

        factory(Extension::class)->create([
            'company_id' => factory(Company::class)->create()->id,
            'extendable_type' => 'number',
            'extendable_id' => 1
        ]);
    }
}
