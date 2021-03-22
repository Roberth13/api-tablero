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

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::get('/companies', 'Api\CompanyController@show');

Route::get('/user', 'Api\AuthController@getCurrent');

Route::group(['prefix'=>'projects'], function(){
    Route::get('', 'Api\ProjectController@show')->middleware('auth:api');
    Route::get('/{id}', 'Api\ProjectController@get')->middleware('auth:api');
    Route::post('', 'Api\ProjectController@store')->middleware('auth:api');
});

Route::group(['prefix'=>'tasks'], function(){
    Route::get('', 'Api\TaskController@show')->middleware('auth:api');
    Route::get('my/{id}', 'Api\TaskController@getMyTasks')->middleware('auth:api');
    Route::get('history/{id}', 'Api\TaskController@getHistorial')->middleware('auth:api');
    Route::post('', 'Api\TaskController@store')->middleware('auth:api');
    Route::put('', 'Api\TaskController@update')->middleware('auth:api');
    Route::delete('my/{id}', 'Api\TaskController@delete')->middleware('auth:api');
});

Route::group(['prefix'=>'state'], function(){
    Route::get('', 'Api\StateController@show')->middleware('auth:api');
});