<?php

use App\Models\Company;
use App\Models\Endpoint;
use App\Models\Extension;
use App\Models\Gather;
use App\Models\PhoneNumber;
use App\Models\Say;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = factory(Company::class)->create([
            'phone_number' => '+14242958894'
        ]);

        $user = factory(User::class)->create(['company_id' => $company->id]);

        $say = Say::create([
            'name' => 'first greeting',
            'noun' => 'Press 1 for something, press 2 to dial yourself',
            'company_id' => $company->id,
        ]);

        $gather = Gather::create([
            'gatherable_type' => 'say',
            'gatherable_id' => $say->id,
        ]);

        $menu = $company->menus()->save(factory(Menu::class)->make([
            'is_active' => true,
        ]));

        $menuItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
        ]);

        $menuItem->actionable()->associate($gather)->save();

        $menu->update([
            'menu_item_id' => $menuItem->id
        ]);

        /** @var MenuItem $childItem */
        $childItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
            'digits' => 1
        ]);

        $childItem->makeChildOf($menuItem);

        $secondGreeting = Say::create([
            'name' => 'second greeting',
            'noun' => 'My second greeting',
            'company_id' => $company->id,
        ]);

        $childItem->actionable()->associate($secondGreeting)->save();

        $extensionItem = factory(MenuItem::class)->create([
            'company_id' => $company->id,
            'menu_id' => $menu->id,
            'digits' => 2,
        ]);

        $number = PhoneNumber::create([
            'company_id' => $company->id,
            'number' => '+13104918163',
            'user_id' => $user->id
        ]);

        $endpoint = factory(Endpoint::class)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'pointable_type' => 'number',
            'pointable_id' => $number->id,
        ]);

        $extension = factory(Extension::class)->create([
            'company_id' => $company->id,
            'extendable_type' => 'user',
            'extendable_id' => $user->id,
        ]);

        $extensionItem->actionable()->associate($extension)->save();
        $extensionItem->makeChildOf($menuItem);
    }
}
