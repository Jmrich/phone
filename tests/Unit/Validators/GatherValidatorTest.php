<?php

namespace Tests\Unit\Validators;

use App\Validators\GatherValidator;
use Tests\TestCase;

class GatherValidatorTest extends TestCase
{
    /** @test */
    public function can_validate_valid_structure()
    {
        $data = [
            'gather' => [
                'children' => [
                    [
                        'type' => 'play',
                        'noun' => 'Some text to say',
                        'options' => []
                    ]
                ],
                'options' => [
                    'timeout' => 2
                ]
            ]
        ];

        $validator = new GatherValidator($data);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function gather_must_be_array()
    {
        $data = [
            'gather' => 'string',
        ];

        $validator = (new GatherValidator($data))->getValidator();

        $this->assertFalse($validator->passes());

        $this->assertEquals('The gather must be an array.', $validator->messages()->first());
    }

    /** @test */
    public function gather_cannot_have_more_than_one_element()
    {
        $data = [
            'gather' => [
                'play',
                'say',
            ],
        ];

        $validator = (new GatherValidator($data))->getValidator();

        $this->assertFalse($validator->passes());

        $this->assertEquals('The gather must contain 1 items.', $validator->messages()->first());
    }

    /** @test */
    public function must_be_say_or_play()
    {
        $data = [
            'gather' => [
                'yeah',
            ],
        ];

        $validator = (new GatherValidator($data))->getValidator();

        $this->assertFalse($validator->passes());

        $this->assertEquals('Only say or play can be nested within a gather.', $validator->messages()->first());
    }
}
