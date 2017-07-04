<?php

namespace App\Models\Observers;

use App\Exceptions\InvalidTypeException;
use App\Models\Extension;

class ExtensionObserver
{
    public function saving(Extension $extension)
    {
        $validTypes = collect([
            'user',
            'say',
            'play'
        ]);

        if (! $validTypes->contains($extension->extendable_type)) {
            throw new InvalidTypeException('Invalid extendable_type');
        }
    }
}