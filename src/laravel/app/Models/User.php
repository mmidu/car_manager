<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\LedgerService;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'fiscal_code', 'password'
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

    public static function getLatestByFiscalCode(string $fiscal_code):?object{
        $lt = new LedgerService();

        $user = $lt->getLatestUserData($fiscal_code);

        return $user->status ? json_decode($user->data) : NULL;
    }
}
