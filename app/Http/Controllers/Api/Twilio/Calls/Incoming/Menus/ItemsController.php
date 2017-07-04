<?php

namespace App\Http\Controllers\Api\Twilio\Calls\Incoming\Menus;

use App\Http\Controllers\Api\Twilio\Calls\BaseIncomingCallController;
use App\Models\Company;
use App\Models\Extension;
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

        $company = $this->getCompany($request);

        /** @var Extension $extension */
        $extension = $company->extensions()->where('number', $digits)->first();

        if ($this->extensionWasFound($extension)) {
            return $this->bypassGather($extension);
        }

        try {
            $item = $parent->children()->where('digits', $digits)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $twiml = $this->twiml->sayError(route('menu-item', [$parent]));
            return $this->respond($twiml);
        }

        return $this->handle($request, $item);
    }

    private function extensionWasFound($extension): bool
    {
        return !is_null($extension);
    }

    private function bypassGather($extension)
    {
        $twiml = $this->twiml->dial($extension->extendable->formatForDial());
        return $this->respond($twiml);
    }
}
