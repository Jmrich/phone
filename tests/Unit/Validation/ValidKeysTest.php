<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;

class ValidKeysTest extends TestCase
{
    /** @test */
    public function can_validate_valid_keys()
    {
        $data = [
            'gather' => [
                'children' => [],
                'options' => []
            ]
        ];

        $validator = \Validator::make($data, [
            'gather.*' => 'valid_keys:children,options'
        ]);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function valid_keys_must_be_supplied()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Valid keys must be supplied.');

        $data = [
            'gather' => [
                'children' => [],
                'options' => []
            ]
        ];

        $validator = \Validator::make($data, [
            'gather' => 'valid_keys'
        ]);

        $validator->passes();
    }

    /** @test */
    public function fails_on_invalid_keys()
    {
        $data = [
            'gather' => [
                'children' => [],
                'options' => [],
                'invalid',
                'more_invalid' => 'value'
            ]
        ];

        $validator = \Validator::make($data, [
            'gather.*' => 'valid_keys:children,options',
        ]);

        $this->assertFalse($validator->passes());
    }
}
