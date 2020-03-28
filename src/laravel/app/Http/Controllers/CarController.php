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

    public function search(Request $request):View{ // TODO: USARE CODICE FISCALE COME USERNAME
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
            return view('car.search')->withErrors(['error' => 'Questa automobile non è di tua proprietà']);
        }

        return view('car.transaction', compact('car', 'old_owner', 'new_owner'));
    }

    public function transfer(Request $request):View{
        $new_user = User::getByFiscalCode($request->get('new_user_fiscal_code'));
        if(empty($new_user)){
            return view('car.search')->widhtErrors(['error' => 'Il nuovo proprietario non esiste']);
        }

        $old_user = User::getByFiscalCode($request->get('old_user_fiscal_code'));
        $car = Car::getByPlate($request->get('plate'));

        if(empty($car)){
            $car = new Car($request->get(['car_plate', 'car_blablabla', 'tutti gli inpout della macchina']));
            $car->save()
            Ledger::save($car->plate, $old_user->fiscal_code);
        }

        Ledger::save($car->plate, $new_user->fiscal_code);
        return view('user.data');
    }
}
