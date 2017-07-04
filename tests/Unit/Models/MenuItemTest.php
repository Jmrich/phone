<?php

namespace Tests\Unit\Models;

use App\Models\Company;
use App\Models\Say;
use App\Models\Menu;
use App\Models\MenuItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MenuItemTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function can_create_root_node()
    {
        $company = $this->createCompany();

        $menu = $company->menus()->save(factory(Menu::class)->make());

        $menuItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        $this->assertTrue($menuItem->isRoot());
        $this->assertFalse($menuItem->isChild());
    }

    /** @test */
    public function can_save_action()
    {
        $company = $this->createCompany();

        $greeting = Say::create([
            'name' => 'first greeting',
            'noun' => 'My first greeting',
            'company_id' => $company->id,
        ]);

        $menu = $company->menus()->save(factory(Menu::class)->make());

        $menuItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        $menuItem->actionable()->associate($greeting)->save();

        $this->assertInstanceOf(Say::class, $menuItem->fresh()->actionable);
    }
}
