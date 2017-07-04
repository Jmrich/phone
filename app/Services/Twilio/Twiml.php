<?php

namespace App\Services\Twilio;

use Twilio\Twiml as TwilioTwiml;

class Twiml
{
    /** @var TwilioTwiml  */
    protected $twiml;

    public function __construct()
    {
        $this->twiml = new TwilioTwiml();
    }

    public function say($message, array $attributes = [])
    {
        $this->twiml->say($message, $attributes);
        return $this->twiml;
    }

    public function gather($verb, $noun, $gatherOptions = [], $verbOptions = [])
    {
        $gather = $this->twiml->gather($gatherOptions);

        $gather->{$verb}($noun, $verbOptions);

        return $this->render();
    }

    public function render()
    {
        return (string) $this->twiml;
    }

    public function sayError($url, $message = null, $method = 'GET')
    {
        if (is_null($message)) {
            $message = 'That was not a valid selection. Please try again.';
        }

        $this->twiml->say($message);
        $this->twiml->redirect($url, ['method' => $method]);

        return $this->twiml;
    }

    public function dial(array $numbers)
    {
        if (count($numbers) > 10) {
            throw new \InvalidArgumentException('No more than 10 numbers can be specified');
        }

        $dial = $this->twiml->dial();

        foreach ($numbers as $number) {
            $dial->{$number['type']}($number['to'], $number['options'] ?? []);
        }

        return $this->render();
    }
}