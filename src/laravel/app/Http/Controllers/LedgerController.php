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

    public function searchTransaction(Request $request): View{
        $user = auth()->user();

        $license_plate = strtoupper($request->get('license_plate'));
        $transaction = $this->ls->getCar($license_plate);

        if(!$transaction->status){
            $car = new \stdClass();
            $car->license_plate = $license_plate;
            $car->empty = true;
            return view('car.view', compact('car'));
        }

        $data = json_decode($transaction->data);

        if($user->fiscal_code != $data->owner->fiscal_code){
            return view('car.search')->withErrors(['error' => 'Non sono presenti automobili di tua proprietà con la targa inserita.']);
        }

        $car = $data->car;
        $car->year = date_create_from_format('Y-m-d', $car->year);
        $car->empty = false;

        $user = $data->owner;
        $user->birth_date = date_create_from_format('Y-m-d', $user->birth_date);

        return view('car.view', compact('car', 'user'));
    }

    public function transfer(Request $request){
    	$user = auth()->user();

    	if($user->fiscal_code != $request->get('_fiscal_code')){
    		return view('car.search')->withErrors(['error' => 'Il codice fiscale inserito è errato']);
    	}

        if(empty(User::getByFiscalCode($request->get('fiscal_code')))){
            return view('car.search')->withErrors(['error' => 'Il nuovo proprietario non esiste']);
        }

        $license_plate = strtoupper($request->get('license_plate'));
        $transaction = $this->ls->getCar($license_plate);

        $data = json_decode($transaction->data,true);

        if($user->fiscal_code != $data['owner']['fiscal_code']){
            return view('car.search')->withErrors(['error' => 'Questa automobile non è di tua proprietà']);
        }
        
        $car = $data['car'];

        foreach($data['owner'] as $key => $owner_data){
            if($owner_data != $request->get('_'.$key)){

                $this->ls->postTransaction([
                    'car' => $car,
                    'owner' => [
                        'fiscal_code' => $request->get('_fiscal_code'),
                        'first_name' => $request->get('_first_name'),
                        'last_name' => $request->get('_last_name'),
                        'birth_date' => $request->get('_birth_date'),
                        'address' => $request->get('_address'),
                    ]
                ]);
            
                break;
            }
        }

        $new_owner = [
            'fiscal_code' => $request->get('fiscal_code'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'birth_date' => $request->get('birth_date'),
            'address' => $request->get('address'),
        ];

        $new_transaction = $this->ls->postTransaction([
            'car' => $car,
            'owner' => $new_owner
        ]);

        return $new_transaction->data;
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
