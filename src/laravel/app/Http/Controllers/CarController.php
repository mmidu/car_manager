<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use App\Models\Registry;
use Illuminate\View\View;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request):View{
        if($request->isMethod('get')){
            return view('car.search');
        }
        
        $plate = $request->get('plate');

        $car = Car::findByPlate($plate);

        $user = User::find(auth()->user()->id);

        $old_owner = $user->registry;
        $new_owner = new Registry();

        if(empty($car)){
            $car = new Car();
            $car->plate = $plate;
            $car->year = '2022-02-22';
        } else if($car->owner->id != $user->id) {
            return view('car.search')->withErrors(['plate' => 'Questa automobile non è di tua proprietà']);
        }

        return view('car.transaction', compact('car', 'old_owner', 'new_owner'));
    }

    public function transfer(Request $request):View{



        return view('user.data');
    }
}
