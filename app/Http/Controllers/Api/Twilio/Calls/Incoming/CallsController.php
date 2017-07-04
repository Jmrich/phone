<?php

namespace App\Http\Controllers\Api\Twilio\Calls\Incoming;

use App\Http\Controllers\Api\Twilio\Calls\BaseIncomingCallController;
use App\Models\Company;
use Illuminate\Http\Request;

class CallsController extends BaseIncomingCallController
{
    public function handle(Request $request)
    {
        $company = $this->getCompany($request);
        $menu = $company->defaultMenu();
        $menuItem = $menu->defaultItem();

        return $this->routeItemAction($menuItem);
    }
}
