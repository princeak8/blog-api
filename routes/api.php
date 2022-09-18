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

//Admin Routes
Route::group([
    'prefix' => 'v1/{db}/admin/',
    'namespace' => 'Admin',
    'middleware' => ['setDB', 'ignoreRouteParam']

], function () {
    //Auth Routes
    Route::post('/login', 'AuthController@login');

    //Profile Routes
    Route::group([
        'prefix' => 'profile'
    ], function () {
        Route::get('/{user_id}', 'UserController@getProfile');
        Route::post('/create', 'UserController@create_profile');
        //Changing the reader password for the admin
        Route::post('/change_password', 'UserController@change_password');
    });
        

    //Post Routes
    Route::group([
        'prefix' => 'post'
    ], function () {
        Route::get('/all', 'PostController@all');
        Route::get('/public', 'PostController@public_posts');
        Route::get('/published', 'PostController@published_posts');
        Route::get('/unpublished', 'PostController@unpublished_posts');
        Route::get('/hidden', 'PostController@hidden_posts');
        Route::post('/save', 'PostController@save');
        Route::post('/update', 'PostController@update');
        Route::delete('/delete/{post_id}', 'PostController@delete');
        Route::get('/toggle_publish/{post_id}', 'PostController@toggle_publish');
        Route::get('/toggle_visibility/{post_id}', 'PostController@toggle_visibility');
        Route::get('/{post_id}', 'PostController@post');
        
    }); 

    //Tag Routes
    Route::group([
        'prefix' => 'tag'
    ], function () {
        Route::get('/all', 'TagController@tags');
        Route::post('/save', 'TagController@save');
        Route::post('/update', 'TagController@update');
        Route::get('/delete/{id}', 'TagController@delete_tag');
    }); 

    //File Routes
    Route::group([
        'prefix' => 'file'
    ], function () {
        Route::post('/save_cover_photo', 'FileController@save_cover_photo');
        Route::post('/save_content_photo', 'FileController@save_content_photo');
        Route::post('/save_profile_photo', 'FileController@save_profile_photo');
    });
});



//Public Routes
Route::group([
    'prefix' => 'v1/{db}/',
    'middleware' => ['setDB', 'ignoreRouteParam']

], function () {

    //Auth Routes
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('/login', 'LoginController@login');
        Route::post('/register', 'RegisterController@register');
        Route::post('/verify_email_link', 'RegisterController@verify_email');
        Route::get('google/login/url', 'GoogleController@getAuthUrl');
        Route::post('google/login', 'GoogleController@postLogin');

        //test
        Route::post('/test_email_verification', 'RegisterController@test_email_verification');
    });

    //Settings Routes
    Route::group([
    ], function () {
        Route::get('/settings', 'ProfileController@get_profile');
    });
    
    // Route::group([
    //     'prefix' => 'v1/{db}',
    //     'middleware' => 'setDB'
    
    // ], function () {
    //     Route::get('/posts', 'PostController@posts');
    //     Route::get('/post/{post_id}', 'PostController@post');
    // });
    //Post Routes
    Route::group([
        'prefix' => 'post'
    ], function () {
        Route::get('/posts', 'PostController@posts');
        Route::get('/posts/{page}', 'PostController@posts');
        Route::get('/latest_posts', 'PostController@latestPosts');
        Route::get('/show/{title}', 'PostController@post');
        Route::get('/increase_views/{post_id}', 'PostController@increase_view_count');
    });

    //Comment Routes
    Route::group([
        'prefix' => 'comment'
    ], function () {
        Route::post('/save', 'CommentController@save');
        Route::post('/reply', 'CommentController@saveReply');
    });

    //Contact Message
    Route::group([
    ], function () {
        Route::post('/send_message', 'MessageController@save');
    });

    //Test
    Route::get('/send_email', 'MailController@test');
    
});
