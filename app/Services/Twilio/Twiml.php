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

        return $this->prepareResponse($gather);
    }

    private function prepareResponse(TwilioTwiml $twiml)
    {
        return '<Response>' . (string) $twiml . '</Response>';
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
}