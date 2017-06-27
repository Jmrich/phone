<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = ['id'];

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function defaultItem() : MenuItem
    {
        return $this->menuItems()->where('id', $this->menu_item_id)->firstOrFail();
    }
}
