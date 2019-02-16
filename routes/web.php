<?php

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

Route::get('/','ReportsController@index');
Route::get('/',function(){
    return redirect('/login');
});


Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home','ReportsController@index');
});

Route::get('/getReports/{type?}','ReportsController@getReports')->name('report.getAll');
Route::post('/addReport','ReportsController@addReport')->name('report.add');
Route::post('/report/{action}','ReportsController@actionReport')->name('report.action');
Route::get('/getUsers','ReportsController@getUsers')->name('user.get');

// API for mobile
Route::post('/m/api/register','ReportsController@mRegister');
Route::post('/m/api/login','ReportsController@mLogin');
Route::post('/m/api/report/send','ReportsController@mReportSend');
Route::post('/m/api/user/upload','ReportsController@mUploadProfilePicture');
Route::post('/m/api/user/getProfilePicture','ReportsController@mGetProfilePicture');
Route::post('/m/api/report/getReports','ReportsController@mGetReports');
Route::post('/m/api/report/getReportsCount','ReportsController@mGetReportsCount');
// Route::get('/m/sendNotification','ReportsController@mSendNotification');
Route::post('/m/api/logout','ReportsController@mLogout');
