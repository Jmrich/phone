<?php

namespace Tests\Unit\Services;

use App\Services\Twilio\Twiml;
use Tests\TestCase;

class TwimlTest extends TestCase
{
    /** @var  Twiml */
    private $twiml;

    protected function setUp()
    {
        parent::setUp();

        $this->twiml = new Twiml;
    }

    /** @test */
    public function can_say_message()
    {
        $expected = '<Response><Say>Please leave a message after the tone.</Say></Response>';
        $actual = (string) $this->twiml->say('Please leave a message after the tone.');
        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /** @test */
    public function can_gather_and_say()
    {
        $expected = '<Response><Gather><Say>Please leave a message after the tone.</Say></Gather></Response>';
        $actual = (string) $this->twiml->gather('say', 'Please leave a message after the tone.');
        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /** @test */
    public function can_dial_phone_number()
    {
        $data = [
            [
                'to' => '310-824-3432',
                'type' => 'number',
            ]
        ];

        $expected = '<Response><Dial><Number>310-824-3432</Number></Dial></Response>';
        $actual = (string) $this->twiml->dial($data);
        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /** @test */
    public function can_dial_multiple_phone_numbers()
    {
        $data = [
            [
                'to' => '310-824-3432',
                'type' => 'number',
            ],
            [
                'to' => '310-555-3432',
                'type' => 'number',
            ]
        ];

        $expected = '<Response>
                        <Dial>
                            <Number>310-824-3432</Number>
                            <Number>310-555-3432</Number>
                        </Dial>
                    </Response>';
        $actual = (string) $this->twiml->dial($data);
        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /** @test */
    public function can_dial_phone_number_with_options()
    {
        $data = [
            [
                'to' => '310-824-3432',
                'type' => 'number',
                'options' => [
                    'url' => 'http://phone.dev'
                ]
            ]
        ];

        $expected = '<Response>
                        <Dial>
                            <Number url="http://phone.dev">310-824-3432</Number>
                        </Dial>
                    </Response>';
        $actual = (string) $this->twiml->dial($data);
        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /** @test */
    public function can_dial_client()
    {
        $data = [
            [
                'to' => 'jane',
                'type' => 'client',
            ]
        ];

        $expected = '<Response><Dial><Client>jane</Client></Dial></Response>';
        $actual = (string) $this->twiml->dial($data);
        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }
}
