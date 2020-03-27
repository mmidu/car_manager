<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistryController extends Controller
{
    public function create(Request $request){
    	return view('registry.create');
    }
}
