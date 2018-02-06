<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('valid_keys', function ($attribute, $value, $parameters, $validator) {
            if (empty($parameters)) {
                throw new \InvalidArgumentException('Valid keys must be supplied.');
            }

            $parts = explode('.', $attribute);

            $key = $parts[count($parts)-1];

            return in_array($key, $parameters);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
