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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::namespace('Api\v1')->group(function(){
    Route::prefix('v1')->group(function(){
        Route::middleware(['throttle:10,1'])->group(function(){
            // login
            Route::post('auth/login', 'AuthController@login')->name('api.auth.login');
            // register
            Route::post('auth/register', 'AuthController@register')->name('api.auth.register');
            // refresh token
            Route::put('auth/current', 'AuthController@refresh')->name('api.auth.refresh');
            // generate captcha
            Route::post('captcha', 'CaptchaController@store')->name('api.captcha.store');
        });

        Route::middleware(['auth:api'])->group(function(){
            // logout
            Route::delete('auth/current', 'AuthController@logout')->name('api.auth.logout');
            // get user info
            Route::get('users/{user}/userinfo', 'UserController@userInfo')->name('api.user.info');
        });
    });
});
