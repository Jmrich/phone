<?php

namespace Tests\Feature\Api\Calls\Incoming\Menus;

use App\Models\Company;
use App\Models\Gather;
use App\Models\Say;
use App\Models\Menu;
use App\Models\MenuItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ItemsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function can_handle_item_action()
    {
        $company = factory(Company::class)->create();

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

        $menu->update(['menu_item_id' => $menuItem->id]);

        $route = route('menu-item', [$menuItem]);
        $response = $this->get($route);

        $itemUrl = route('menu-item-gather', [$menuItem]);

        $noun = $menuItem->actionable->gatherable->noun;

        $expected = "<Response><Gather action=\"$itemUrl\" method=\"GET\"><Say>$noun</Say></Gather></Response>";

        $this->assertXmlStringEqualsXmlString($expected, $response->content());
    }

    /** @test */
    public function can_handle_item_say_action()
    {
        $company = factory(Company::class)->create();

        $menu = $company->menus()->save(factory(Menu::class)->make([
            'is_active' => true,
        ]));

        $menuItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        /** @var MenuItem $childItem */
        $childItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
            'digits' => 1
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

        $secondGreeting = Say::create([
            'name' => 'second greeting',
            'noun' => 'My second greeting',
            'company_id' => $company->id,
        ]);

        $menuItem->actionable()->associate($gather)->save();
        $childItem->actionable()->associate($secondGreeting)->save();

        $childItem->makeChildOf($menuItem);

        $menu->update([
            'menu_item_id' => $menuItem->id
        ]);

        $route = route('menu-item-gather', [$menuItem, 'Digits' => 1]);
        $response = $this->get($route);

        $itemUrl = route('menu-item-gather', [$childItem]);

        $noun = $childItem->actionable->noun;

        $expected = "<Response><Say>$noun</Say></Response>";

        $this->assertXmlStringEqualsXmlString($expected, $response->content());
    }

    /** @test */
    public function can_handle_item_gather_error()
    {
        $company = factory(Company::class)->create();

        $menu = $company->menus()->save(factory(Menu::class)->make([
            'is_active' => true,
        ]));

        $menuItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        $greeting = Say::create([
            'name' => 'first greeting',
            'noun' => 'My first greeting',
            'company_id' => $company->id,
        ]);

        $menuItem->actionable()->associate($greeting)->save();

        $menu->update([
            'menu_item_id' => $menuItem->id
        ]);

        $route = route('menu-item-gather', [$menuItem, 'Digits' => 1]);
        $response = $this->get($route);

        $url = route('menu-item', [$menuItem]);

        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                    <Response>
                        <Say>That was not a valid selection. Please try again.</Say>
                        <Redirect method=\"GET\">$url</Redirect>
                    </Response>";

        $this->assertXmlStringEqualsXmlString($expected, $response->content());
    }
}
