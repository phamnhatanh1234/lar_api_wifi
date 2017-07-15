<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::get('test', function(){
	echo "Chào";
});


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['prefix' => 'api/v1'], function(){
    Route::resource('roles', 'RolesController',['only' => ['index', 'show']]);
		Route::resource('devices', 'DeviceController',['only' => ['index', 'show']]);
		Route::resource('usertypes', 'UserTypeController');
		Route::resource('students', 'StudentController');
		Route::resource('price', 'PriceUserTypesController',['only' => ['index', 'show']]);
		Route::resource('managers', 'ManagerController');
		Route::resource('userwifi', 'UserWifiController');
		Route::get('userwifiuse', 'UserWifiController@getFree');
		Route::resource('bills', 'BillController');
		Route::get('5a370f2521edf178a5021895cee2381d', 'BillController@showBillFree');
});


Route::group(['prefix' => 'api/v1'], function()
{
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
});
