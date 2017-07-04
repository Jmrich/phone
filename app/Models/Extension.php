<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    protected $guarded = ['id'];

    public function endpoints()
    {
        return $this->user->endpoints();
    }

    public function extendable()
    {
        return $this->morphTo();
    }
}
