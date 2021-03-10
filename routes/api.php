<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('Cors')->group(function () {
	Route::middleware('auth:api')->get('/user', function (Request $request) {
		return $request->user();
	});

	route::post('/login', 'Api\LoginController@login')->name('delivery.login');
	Route::middleware(['auth:api'])->group(function () {
		route::get('/delivery', 'Api\DeliveryController@index');
		route::put('/delivery/{id}', 'Api\DeliveryController@update');
		route::get('/delivery-people', 'Api\DeliveryPersonController@GetAllDeliveryPeople');
	});
});