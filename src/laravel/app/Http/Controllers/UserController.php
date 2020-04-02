<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
   	public function home(Request $request){
   		$user = User::find(auth()->user()->id);
   	}

   	private function hasRegistry(User $user):bool{
		return !is_null($user->registry);
   	}
}
