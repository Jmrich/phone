<?php

namespace App\Models;

use Baum\Node;

class MenuItem extends Node
{
    protected $scoped = [
        'menu_id',
        'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function actionable()
    {
        return $this->morphTo();
    }
}
