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
            return view('car.search')->withErrors(['error' => 'Non sono presenti automobili di tua proprietà con la targa inserita.']);
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

        if($new_transaction->data == 'ok'){
            return view('car.search')->withErrors(['error' => 'La proprietà è stata trasferita con successo.']);
        } else {
            return view('car.search')->withErrors(['error' => 'C\'è stato un errore con la tua operazione.']);
        }
    }
}
