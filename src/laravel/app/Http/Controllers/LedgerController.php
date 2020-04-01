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
        $this->middleware('auth', ['except' => ['validateFiscalCode']]);
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

        if($new_transaction->data == 'ok'){
            return view('car.search')->withErrors(['error' => 'La proprietà è stata trasferita con successo.']);
        } else {
            return view('car.search')->withErrors(['error' => 'C\'è stato un errore con la tua operazione.']);
        }
    }

    public function validateFiscalCode(Request $request){
        $fiscal_code = strtoupper(preg_replace('/\s+/', '', $request->get('fiscal_code')));

        if(!preg_match('/^[a-zA-Z]{6}[0-9]{2}[abcdehlmprstABCDEHLMPRST]{1}[0-9]{2}([a-zA-Z]{1}[0-9]{3})[a-zA-Z]{1}$/', $fiscal_code)){
            return response()->json(false);
        }

    	$months = ['-1','A','B','C','D','E','H','L','M','P','R','S','T'];

    	$first_name = preg_replace('/\s+/', '', $request->get('first_name'));
    	$last_name = preg_replace('/\s+/', '', $request->get('last_name'));
    	$birth_date = explode('-', $request->get('birth_date'));
    	array_push($birth_date,0,0,0);
    	$gender = $request->get('gender') != "Seleziona" ? intval($request->get('gender')) : -1;

    	$lname = '';

    	if(strlen($last_name) < 3){
    		$lname = $last_name;
    		while(strlen($lname) < 3){
    			$lname .= 'x';
    		}
    	} else {
    		$consonants = preg_replace('/[aeiou]/i', '', $last_name);
    		$vowels = preg_replace('/[^aeiou]/i', '', $last_name);
    		$lname = strlen($consonants) <= 3 ? substr($consonants.$vowels, 0, 3) : substr($consonants, 0, 3);
    	}

    	$fname = '';

    	if(strlen($first_name) < 3){
    		$fname = $first_name;
    		while(strlen($fname) < 3){
    			$fname += 'x';
    		}
    	} else {
    		$consonants = preg_replace('/[aeiou]/i', '', $first_name);
    		$vowels = preg_replace('/[^aeiou]/i', '', $first_name);
    		$fname = strlen($consonants) <= 3 ? substr($consonants.$vowels, 0, 3) : $consonants[0].$consonants[2].$consonants[3];
    	}

    	$day = substr('0'.(intval($birth_date[2])+intval($gender)),-2);
    	$month = $months[intval($birth_date[1])];
    	$year = substr($birth_date[0],-2);

    	$_fiscal_code = strtoupper("$lname$fname$year$month$day");

    	return response()->json($_fiscal_code == substr($fiscal_code,0,11));
    }
}
