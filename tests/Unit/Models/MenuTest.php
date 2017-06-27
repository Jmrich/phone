<?php

namespace Tests\Unit\Models;

use App\Models\Company;
use App\Models\Menu;
use App\Models\MenuItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MenuTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function can_get_menu_items()
    {
        $company = factory(Company::class)->create();

        $menu = $company->menus()->save(factory(Menu::class)->make());

        factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        $this->assertInstanceOf(MenuItem::class, $menu->fresh()->menuItems()->first());
    }

    /** @test */
    public function can_get_default_menu_item()
    {
        $company = factory(Company::class)->create();

        $menu = $company->menus()->save(factory(Menu::class)->make());

        $menuItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        $menu->update([
            'menu_item_id' => $menuItem->id
        ]);

        $this->assertEquals($menuItem->id, $menu->defaultItem()->id);
    }
}
