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

Route::namespace('App\Http\Controllers')->group(function () {
    Route::post('getOTP', 'AuthController@sendOTP');
    Route::post('login', 'AuthController@login');
    Route::middleware('auth:api')->group(function () {
        Route::post('students', 'StudentController@bulkUpload');
        Route::get('students', 'StudentController@index');
    });
});

