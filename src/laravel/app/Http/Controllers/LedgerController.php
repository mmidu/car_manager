<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use App\Services\LedgerService;
use Illuminate\View\View;

class LedgerController extends Controller
{
    protected $ls;

    public function __construct()
    {
        $this->middleware('auth');
        $this->ls = new LedgerService();
    }

    public function home(Request $request): View{
        return view('car.search');
    }

    public function searchTransaction(Request $request){
        $user = auth()->user();

        $license_plate = strtoupper($request->get('license_plate'));
        $cars = $this->ls->getCar($license_plate);

        if(!$cars->status){
            $car = new \stdClass();
            $car->license_plate = $license_plate;
            return view('car.view', compact('car'));
        }

        $data = json_decode($cars->data);

        if($user->fiscal_code != $data->owner->fiscal_code){
            return view('car.search')->withErrors(['error' => 'Questa automobile non è di tua proprietà']);
        }

        $car = $data->car;
        $car->year = date_create_from_format('Y-m-d', $car->year);
        $user = $data->owner;
        $user->birth_date = date_create_from_format('Y-m-d', $user->birth_date);

        return view('car.view', compact('car', 'user'));
    }

    // public function transfer(Request $request):View{
    //     $new_user = User::getByFiscalCode($request->get('new_user_fiscal_code'));
    //     if(empty($new_user)){
    //         return view('car.search')->widhtErrors(['error' => 'Il nuovo proprietario non esiste']);
    //     }

    //     $old_user = User::getByFiscalCode($request->get('old_user_fiscal_code'));
    //     $car = Car::getByPlate($request->get('plate'));

    //     if(empty($car)){
    //         $car = new Car($request->get(['car_plate', 'car_blablabla', 'tutti gli inpout della macchina']));
    //         $car->save()
    //         Ledger::save($car->plate, $old_user->fiscal_code);
    //     }

    //     Ledger::save($car->plate, $new_user->fiscal_code);
    //     return view('user.data');
    // }
}
