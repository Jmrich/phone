<?php

namespace App\Providers;

use App\Models\Endpoint;
use App\Models\Extension;
use App\Models\Gather;
use App\Models\PhoneNumber;
use App\Models\Say;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191);

        Relation::morphMap([
            'endpoint' => Endpoint::class,
            'extension' => Extension::class,
            'say' => Say::class,
            'gather' => Gather::class,
            'number' => PhoneNumber::class,
            'user' => User::class,
        ]);

//        $this->logDbQueries();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function logDbQueries(): void
    {
        \DB::listen(function ($query) {
            \Log::info('Query: ' . $query->sql);
            \Log::info('Bindings: ' . implode(',', $query->bindings));
            \Log::info('Time: ' . $query->time . ' microseconds');
        });
    }
}
