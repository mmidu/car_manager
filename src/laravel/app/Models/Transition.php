<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Car;

class Transition extends Model {

	protected $fillable = [
        'old_owner_id', 'new_owner_id', 'car_id'
    ];

    public function old_owner(){
        return $this->belongsTo(User::class);
    }

    public function new_owner(){
        return $this->belongsTo(User::class);
    }

    public function car(){
    	return $this->belongsTo(Car::class)
    }
}
