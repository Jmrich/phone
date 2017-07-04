<?php

namespace App\Models\Observers;

use App\Models\Endpoint;

class EndpointObserver
{
    public function saving(Endpoint $endpoint)
    {
        if ($endpoint->user->endpoints()->count() >= 10) {
            throw new \Exception('Maximum endpoints per user is 10.');
        }
    }
}