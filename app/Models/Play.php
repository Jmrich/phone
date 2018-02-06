<?php

namespace App\Models;

use App\Models\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;

class Play extends Model
{
    use HasMedia;

    protected $guarded = ['id'];

    public function getNounAttribute()
    {
        return $this->media->url ?? null;
    }
}
