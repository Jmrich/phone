<?php

/*
|--------------------------------------------------------------------------
| Calls Routes
|--------------------------------------------------------------------------
*/
Route::prefix('calls')
    ->namespace('Twilio\Calls\Incoming')
    ->group(function () {
        Route::get('incoming', 'CallsController@handle');
    });

Route::prefix('items')
    ->namespace('Twilio\Calls\Incoming\Menus')
    ->group(function () {
        Route::get('{item}', 'ItemsController@handle')->name('menu-item');
        Route::get('{parent}/gather', 'ItemsController@handleGather')->name('menu-item-gather');
    });
