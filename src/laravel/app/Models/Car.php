<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model {

	protected $fillable = [
        'owner_id', 'plate', 'manufacturer', 'model', 'year', 'engine_displacement', 'hp'
    ];

    protected $casts = [
        'year' => 'datetime',
    ];

    public function owner(){
        return $this->belongsTo(User::class);
    }

    public static function findByPlate(string $plate):?Car{
    	return Car::where(['plate' => $plate])->first();
    }
}
