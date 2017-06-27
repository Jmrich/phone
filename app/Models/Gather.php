<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gather extends Model
{
    protected $guarded = ['id'];

    public function gatherable()
    {
        return $this->morphTo();
    }
}
