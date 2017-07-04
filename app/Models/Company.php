<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function extensions()
    {
        return $this->hasMany(Extension::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function defaultMenu() : Menu
    {
        return $this->menus()->where('is_active', 1)->firstOrFail();
    }
}
