<?php

namespace Tests\Feature\Api\Menus;

use App\Models\Company;
use App\Models\Gather;
use App\Models\Say;
use App\Models\Menu;
use App\Models\MenuItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MenusControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function can_handle_action_for_default_item()
    {
        $company = factory(Company::class)->create();

        $url = "/api/calls/incoming";

        $menu = $company->menus()->save(factory(Menu::class)->make([
            'is_active' => true,
        ]));

        $menuItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        $say = Say::create([
            'name' => 'first greeting',
            'noun' => 'My first greeting',
            'company_id' => $company->id,
            ]);

        $gather = Gather::create([
            'gatherable_type' => 'say',
            'gatherable_id' => $say->id,
        ]);

        $menuItem->actionable()->associate($gather)->save();

        $menu->update([
            'menu_item_id' => $menuItem->id
        ]);

        $q = http_build_query([
            'To' => $company->phone_number,
            'From' => '+13104918163'
        ]);

        $response = $this->get($url . "?$q");

        $noun = $menuItem->actionable->gatherable->noun;

        $itemUrl = route('menu-item-gather', [$menuItem]);

        $expected = "<Response><Gather action=\"$itemUrl\" method=\"GET\"><Say>$noun</Say></Gather></Response>";

        $actual = $response->content();

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }
}
