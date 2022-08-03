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
    return view('welcome');
});

Route::get('/api-doc', 'SwaggerController@index');
Route::get('/test-mail', 'MailController@test');
Route::get('/test-mail-view', 'MailController@test_mail_view');

Route::group([
    'prefix' => 'v1/{db}/',
    'middleware' => ['setDB']

], function () {

    //Auth Routes
    Route::group([
    ], function () {
        Route::get('/verify_email/{email}/{signature}', 'RegisterController@login');
    });
});
