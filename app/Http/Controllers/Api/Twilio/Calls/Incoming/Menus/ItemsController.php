<?php

namespace App\Http\Controllers\Api\Twilio\Calls\Incoming\Menus;

use App\Http\Controllers\Api\Twilio\Calls\BaseIncomingCallController;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ItemsController extends BaseIncomingCallController
{
    public function handle(Request $request, MenuItem $item)
    {
        return $this->routeItemAction($item);
    }

    public function handleGather(Request $request, MenuItem $parent)
    {
        $digits = $request->Digits;

        try {
            $item = $parent->children()->where('digits', $digits)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $twiml = $this->twiml->sayError(route('menu-item', [$parent]));
            return $this->respond($twiml);
        }

        return $this->handle($request, $item);
    }
}
