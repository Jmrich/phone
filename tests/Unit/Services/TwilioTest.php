<?php

namespace Tests\Unit\Services;

use App\Services\Twilio\Twilio;
use Tests\TestCase;

class TwilioTest extends TestCase
{
    /** @var  Twilio */
    private $twilio;

    protected function setUp()
    {
        parent::setUp();

        $this->initializeTwilioTestInstance();

        $this->twilio = $this->app->make(Twilio::class);
    }

    /** @test */
    public function can_make_phone_call()
    {
        $callResponse = $this->twilio->placeCall('+13104918163', '+15005550006', [
            'url' => 'http://phone.dev/twilio/callback'
        ]);

        $expectedKeys = collect([
            'accountSid',
            'answeredBy',
            'callerName',
            'dateCreated',
            'dateUpdated',
            'duration',
            'endTime',
            'forwardedFrom',
            'from',
            'fromFormatted',
            'groupSid',
            'parentCallSid',
            'phoneNumberSid',
            'sid',
            'startTime',
            'status',
            'to',
            'toFormatted',
            'uri'
        ]);

        $callResponse = collect($callResponse->toArray());

        $expectedKeys->each(function ($key) use ($callResponse) {
            $this->assertTrue($callResponse->has($key));
        });
    }

    /** @test */
    public function can_send_sms()
    {
        $smsResponse = $this->twilio->sendMessage('+13104918163', [
            'from' => '+15005550006',
            'body' => 'Test message'
        ]);

        $expectedKeys = collect([
            'accountSid',
            'body',
            'dateCreated',
            'dateUpdated',
            'dateSent',
            'from',
            'sid',
            'status',
            'to',
            'uri'
        ]);

        $smsResponse = collect($smsResponse->toArray());

        $expectedKeys->each(function ($key) use ($smsResponse) {
            $this->assertTrue($smsResponse->has($key));
        });
    }

    /** @test */
    public function can_send_mms()
    {
        $smsResponse = $this->twilio->sendMessage('+13104918163', [
            'from' => '+15005550006',
            'body' => 'Test message',
            'mediaUrl' => 'http://phone.dev/twilio/mediaUrl'
        ]);

        $expectedKeys = collect([
            'accountSid',
            'body',
            'dateCreated',
            'dateUpdated',
            'dateSent',
            'from',
            'numMedia',
            'numSegments',
            'sid',
            'status',
            'to',
            'uri'
        ]);

        $smsResponse = collect($smsResponse->toArray());

        $expectedKeys->each(function ($key) use ($smsResponse) {
            $this->assertTrue($smsResponse->has($key));
        });
    }
}
