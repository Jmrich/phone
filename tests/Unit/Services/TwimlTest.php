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
        $actual = $this->twiml->gather('say', 'Please leave a message after the tone.');
        $this->assertEquals($expected, $actual);
    }
}
