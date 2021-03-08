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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

route::post('/login','Api\LoginController@login')->name('delivery.login');
route::get('/delivery','Api\DeliveryController@index')->middleware('auth:api');
route::put('/delivery/{id}','Api\DeliveryController@update')->middleware('auth:api');
route::get('/delivery-people','Api\DeliveryPersonController@GetAllDeliveryPeople')->middleware('auth:api');
