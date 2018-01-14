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

Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1'], function () {
    // These routes are only available for guest users
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@authenticate');
        Route::post('/check', 'AuthController@check');
        Route::post('/logout', 'AuthController@logout');
    });

    // These routes are only available for auth users
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::get('/auth/user', 'AuthController@getAuthUser');
        Route::post('/user/change-password', 'UserController@changePassword');
        Route::patch('/user/update-profile', 'UserController@updateProfile');

        Route::get('/meal', 'MealController@index');
        Route::post('/meal', 'MealController@store');
        Route::patch('/meal/{id}', 'MealController@update');
        Route::delete('/meal/{id}', 'MealController@destroy');

        // These routes are only available for Admins and Managers
        Route::group(['prefix' => 'manage'], function () {
            Route::group(['middleware' => 'isAdmin'], function () {
                Route::get('/meal', 'MealController@getAll');
                Route::post('/meal', 'MealController@manageStore');
                Route::patch('/meal/{id}', 'MealController@manageUpdate');
                Route::delete('/meal/{id}', 'MealController@manageDestroy');
            });

            Route::group(['middleware' => 'isAdminOrManager'], function () {
                Route::get('/user', 'UserController@getAll');
                Route::get('/user/autocomplete', 'UserController@getAllAutocomplete');
                Route::post('/user', 'UserController@manageStore');
                Route::patch('/user/{id}', 'UserController@manageUpdate');
                Route::delete('/user/{id}', 'UserController@manageDestroy');
            });
        });
    });
});
