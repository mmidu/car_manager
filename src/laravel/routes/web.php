<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->guest(route('login'));
});

Auth::routes();

Route::get('/home', 'UserController@home');

Route::get('/car/search', 'LedgerController@home');

Route::post('/car/search', 'LedgerController@searchTransaction')->name('car_search');

Route::post('/car/transfer', function(){
	return 'ok';
})->name('car_transfer');