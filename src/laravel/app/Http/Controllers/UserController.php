<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
   	public function home(Request $request){
   		$user = User::find(auth()->user()->id);
   		/*if($this->hasRegistry($user)){
   			return redirect()->route('create_registry');
   		}else{
   			return response()->json([
   				'ko'
   			]);
   		}*/
   	}

   	private function hasRegistry(User $user):bool{
		return !is_null($user->registry);
   	}
}
