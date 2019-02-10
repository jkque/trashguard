<?php

use Illuminate\Http\Request;

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

// API for mobile
// Route::apiResource('/api/','ReoirtsController');
Route::post('/register','ReportsController@mRegister');
Route::post('/login','ReportsController@mLogin');
Route::post('/report/send','ReportsController@mReportSend');