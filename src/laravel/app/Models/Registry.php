<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Registry extends Model {

	protected $fillable = [
        'user_id', 'first_name', 'last_name', 'birth_date', 'fiscal_code', 'address',
    ];

    protected $casts = [
        'birth_date' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
