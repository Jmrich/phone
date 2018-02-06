<?php

namespace Tests\Feature\Api\Calls\Incoming\Menus;

use App\Models\Company;
use App\Models\Endpoint;
use App\Models\Extension;
use App\Models\Gather;
use App\Models\Media;
use App\Models\PhoneNumber;
use App\Models\Play;
use App\Models\Say;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Http\File;
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
            'company_id' => $company->id,
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
            'company_id' => $company->id,
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

        $route = route('menu-item-gather', [
            $menuItem,
            'To' => $company->phone_number,
            'From' => '+13104918163',
            'Digits' => 1,
        ]);
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

        $route = route('menu-item-gather', [
            $menuItem,
            'To' => $company->phone_number,
            'From' => '+13104918163',
            'Digits' => 1,
        ]);
        $response = $this->get($route);

        $url = route('menu-item', [$menuItem]);

        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                    <Response>
                        <Say>That was not a valid selection. Please try again.</Say>
                        <Redirect method=\"GET\">$url</Redirect>
                    </Response>";

        $this->assertXmlStringEqualsXmlString($expected, $response->content());
    }

    /** @test */
    public function can_handle_item_user_extension()
    {
        $company = $this->createCompany();

        $user = factory(User::class)->create([
            'company_id' => $company->id,
        ]);

        $menu = $company->menus()->save(factory(Menu::class)->make([
            'is_active' => true,
        ]));

        $menuItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        $extension = factory(Extension::class)->create([
            'company_id' => $company->id,
            'extendable_type' => 'user',
            'extendable_id' => $user->id
        ]);

        $menuItem->actionable()->associate($extension)->save();

        $menu->update([
            'menu_item_id' => $menuItem->id
        ]);

        /** @var PhoneNumber $number */
        $number = factory(PhoneNumber::class)->create([
            'company_id' => $company->id,
            'number' => '+15005550006',
        ]);

        $endpoint = factory(Endpoint::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'pointable_type' => 'number',
            'pointable_id' => $number->id,
        ]);

        /** @var PhoneNumber $number */
        $number2 = factory(PhoneNumber::class)->create([
            'company_id' => $company->id,
            'number' => '+15005550007',
        ]);

        $endpoint2 = factory(Endpoint::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'pointable_type' => 'number',
            'pointable_id' => $number2->id,
        ]);

        $route = route('menu-item', [$menuItem]);
        $response = $this->get($route);

        $expected = "<Response>
                        <Dial>
                            <Number>$number->number</Number>
                            <Number>$number2->number</Number>
                        </Dial>
                    </Response>";

        $this->assertXmlStringEqualsXmlString($expected, $response->content());
    }

    /** @test */
    public function can_bypass_gather_to_reach_extension()
    {
        $company = factory(Company::class)->create();

        $user = factory(User::class)->create([
            'company_id' => $company->id,
        ]);

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
            'company_id' => $company->id,
        ]);

        $menuItem->actionable()->associate($gather)->save();

        $menu->update([
            'menu_item_id' => $menuItem->id
        ]);

        $extension = factory(Extension::class)->create([
            'company_id' => $company->id,
            'extendable_type' => 'user',
            'extendable_id' => $user->id
        ]);

        /** @var PhoneNumber $number */
        $number = factory(PhoneNumber::class)->create([
            'company_id' => $company->id,
            'number' => '+15005550006',
        ]);

        $endpoint = factory(Endpoint::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'pointable_type' => 'number',
            'pointable_id' => $number->id,
        ]);

        $route = route('menu-item-gather', [
            $menuItem,
            'To' => $company->phone_number,
            'From' => '+13104918163',
            'Digits' => $extension->number,
        ]);

        $response = $this->get($route);

        $expected = "<Response>
                        <Dial>
                            <Number>$number->number</Number>
                        </Dial>
                    </Response>";

        $actual = $response->content();

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /** @test */
    public function can_handle_item_play_action()
    {
        $company = factory(Company::class)->create();

        $menu = $company->menus()->save(factory(Menu::class)->make([
            'is_active' => true,
        ]));

        $menuItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        /** @var Play $play */
        $play = Play::create([
            'name' => 'first greeting',
            'company_id' => $company->id,
        ]);

        $play->media()->save(factory(Media::class)->make(['company_id' => $company->id]));

        $gather = Gather::create([
            'gatherable_type' => 'play',
            'gatherable_id' => $play->id,
            'company_id' => $company->id,
        ]);

        $menuItem->actionable()->associate($gather)->save();

        $menu->update([
            'menu_item_id' => $menuItem->id
        ]);

        $route = route('incoming-call', [
            'To' => $company->phone_number,
            'From' => '+13104918163',
            'Digits' => 1,
        ]);
        $response = $this->get($route);

        $noun = $play->noun;

        $itemUrl = route('menu-item-gather', [$menuItem]);

        $expected = "<Response>
                        <Gather action=\"$itemUrl\" method=\"GET\">
                            <Play>$noun</Play>
                        </Gather>
                    </Response>";

        $this->assertXmlStringEqualsXmlString($expected, $response->content());
    }
}
