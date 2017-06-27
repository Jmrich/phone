<?php

namespace App\Http\Controllers\Api\Twilio\Calls;

use App\Services\Twilio\Twiml;
use Illuminate\Routing\Controller;

class BaseIncomingCallController extends Controller
{
    /** @var  Twiml */
    protected $twiml;

    public function __construct(Twiml $twiml)
    {
        $this->twiml = $twiml;
    }

    protected function gatherResponse($item, $actionUrl = null)
    {
        if (is_null($actionUrl)) {
            $actionUrl = route('menu-item-gather', $item);
        }

        $twiml = $this->twiml->gather($item->actionable->gatherable_type, $item->actionable->gatherable->noun, [
            'action' => $actionUrl,
            'method' => 'GET'
        ]);

        return $this->respond($twiml);
    }

    protected function sayResponse($item)
    {
        $twiml = $this->twiml->say($item->actionable->noun);
        return $this->respond($twiml);
    }

    protected function respond($content)
    {
        return response($content, 200, [
            'Content-Type' => 'text/xml'
        ]);
    }

    protected function routeItemAction($item)
    {
        switch ($item->actionable_type) {
            case 'gather':
                return $this->gatherResponse($item);
            case 'say':
                return $this->sayResponse($item);
        }
    }
}
