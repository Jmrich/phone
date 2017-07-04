<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function endpoints()
    {
        return $this->hasMany(Endpoint::class);
    }

    public function extension()
    {
        return $this->morphOne(Extension::class, 'extendable');
    }

    public function formatForDial() : array
    {
        return $this->endpoints
            ->map(function ($endpoint) {
                return [
                    'type' => $endpoint->pointable->getType(),
                    'to' => $endpoint->pointable->getTo(),
                ];
            })->toArray();
    }
}
