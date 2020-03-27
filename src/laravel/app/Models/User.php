<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Registry;
use App\Models\Car;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'email', 'password', 'registry_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function registry() {
        return $this->hasOne(Registry::class); //TODO: FIX TO LATEST
    }

    public function cars() {
        return $this->hasMany(Car::class);
    }
}
