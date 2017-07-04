<?php

namespace App\Models;

use App\Contracts\Pointable;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model implements Pointable
{
    protected $guarded = ['id'];

    public function formatForDial() : array
    {
        return [
            [
                'type' => $this->getType(),
                'to' => $this->getTo(),
            ]
        ];
    }

    public function getType()
    {
        return 'number';
    }

    public function getTo()
    {
        return $this->number;
    }
}
