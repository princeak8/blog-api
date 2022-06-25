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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// // });
// use Auth;

// Route::middleware('auth:api')->get('/user', function () {
//     return Auth::user();
// });

Route::group([
    'prefix' => 'v1/{db}',
    'middleware' => 'setDB'

], function () {
    Route::get('/posts', 'PostController@posts');
    Route::get('/post/{post_id}', 'PostController@post');
});


//Admin Routes
Route::group([
    'prefix' => 'v1/{db}/admin/',
    'namespace' => 'Admin',
    'middleware' => 'setDB'

], function () {
    //Auth Routes
    // Route::post('/login', 'AuthController@login');

    //Post Routes
    Route::group([
        'prefix' => 'profile'
    ], function () {
        Route::get('/{user_id}', 'UserController@getProfile');
        Route::post('/create', 'UserController@create_profile');
    });
        

    //Post Routes
    Route::group([
        'prefix' => 'post'
    ], function () {
        Route::get('/public', 'PostController@public_posts');
        Route::get('/unpublished', 'PostController@unpublished_posts');
        Route::get('/hidden', 'PostController@hidden_posts');
        Route::post('/save', 'PostController@save');
        Route::post('/update_post', 'PostController@update');
        Route::get('/{post_id}', 'PostController@post');
        
    }); 

    //File Routes
    Route::group([
        'prefix' => 'file'
    ], function () {
        Route::post('/save', 'FileController@save');
    });
});
