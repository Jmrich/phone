<?php

namespace App\Validators;

use App\Exceptions\InvalidTypeException;

class Factory
{
    public static function make($type, array $data)
    {
        switch ($type) {
            case 'say':
                return new SayValidator($data);
            case 'gather':
                return new GatherValidator($data);
            default:
                throw new InvalidTypeException('Invalid validator type');
        }
    }
}
