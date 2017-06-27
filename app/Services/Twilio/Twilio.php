<?php


namespace App\Services\Twilio;


use Twilio\Rest\Client;

class Twilio
{
    /** @var  Client */
    protected $client;

    public function __construct(string $authId, string $authToken)
    {
        $this->client = new Client($authId, $authToken);
    }

    public function sendMessage($to, array $options)
    {
        return $this->client->messages->create($to, $options);
    }

    public function placeCall(string $to, string $from, array $options)
    {
        return $this->client->calls->create($to, $from, [
            'url' => $options['url']
        ]);
    }
}