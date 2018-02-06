<?php

namespace Tests\Unit\Validators;

use App\Exceptions\InvalidTypeException;
use App\Validators\Factory;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    /** @test */
    public function exception_thrown_for_invalid_validator()
    {
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage('Invalid validator type');

        Factory::make('unknown', []);
    }

    /** @test */
    public function can_get_validator_for_valid_types()
    {
        $validTypes = [
            'say',
            'gather',
        ];

        collect($validTypes)->each(function ($type) {
            $validator = Factory::make($type, ['data']);
            $type = ucfirst($type);
            $instance = 'App\Validators\\' . $type . 'Validator';
            $this->assertInstanceOf($instance, $validator);
        });
    }
}
