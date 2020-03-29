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
        'fiscal_code', 'password', 'first_name', 'last_name', 'birth_date', 'address'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at', 'id'
    ];

    protected $casts = [
        'email_verified_at' => 'timestamp',
        'address' => 'timestamp'
    ];

    public function registry() {
        return $this->hasOne(Registry::class); //TODO: FIX TO LATEST
    }

    public function cars() {
        return $this->hasMany(Car::class);
    }

    public function getUsernameAttribute(){
        return $this->fiscal_code;
    }
}
