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


Route::get('/', function () {
    return [
        'app' => 'CyberShark.rs',
        'version' => '0.1',
    ];
});


Route::group(['namespace' => 'Auth'], function () {

    Route::post('auth/login', ['as' => 'login', 'uses' => 'AuthController@login']);

    Route::post('auth/register', ['as' => 'register', 'uses' => 'RegisterController@register']);
    // Send reset password mail
    Route::post('auth/recovery', 'ForgotPasswordController@sendPasswordResetLink');
    // handle reset password form process
    Route::post('auth/reset', 'ResetPasswordController@callResetPassword');

});

Route::group(['middleware' => ['jwt', 'jwt.auth']], function () {

    Route::group(['namespace' => 'Profile'], function () {

        Route::get('profile', ['as' => 'profile', 'uses' => 'ProfileController@me']);

        Route::put('profile', ['as' => 'profile', 'uses' => 'ProfileController@update']);

        Route::put('profile/password', ['as' => 'profile', 'uses' => 'ProfileController@updatePassword']);

    });

    Route::group(['namespace' => 'Auth'], function () {

        Route::post('auth/logout', ['as' => 'logout', 'uses' => 'LogoutController@logout']);

    });


    Route::get('news', 'NewsController@index');

    Route::get('servers', 'ServerController@index');
    Route::get('server/{id}', 'ServerController@show');
    Route::post('server/start/{id}', 'ServerController@start');
    Route::post('server/stop/{id}', 'ServerController@stop');
    Route::get('server/restart/{id}', 'ServerController@restart');
    Route::get('server/{id}/webftp', 'ServerController@webftp');


    Route::get('support', 'SupportController@index');
    Route::post('support', 'SupportController@store');
    Route::get('support/{id}', 'SupportController@show');
    Route::post('support/answer/{id}', 'SupportController@storeAnswer');
     
    Route::get('orders', 'OrderController@index');
    Route::get('orders/{id}', 'OrderController@show');
    Route::post('orders/{ }', 'OrderController@update');



});

Route::post('orders', 'OrderController@store');

Route::post('cart', 'OrderController@cart');
Route::post('contact', 'ContactController@send');
Route::get('games', 'GamesController@index');
Route::get('game/{id}', 'GamesController@show');
Route::post('marketing', 'MarketingController@index');