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

Route::prefix('v1')->group(function() {
    Route::group(['middleware' => ['guest:api']], function () {
        Route::post('login', 'api\AuthController@login');
        Route::get('guest-view-roles', [
            'as' => 'roles.getRoles',
            'uses' => 'api\RoleController@getRoles'
        ]);
    });
    Route::group(['middleware' => 'auth:api'], function() {
        // For shared hosting
        Route::post('users/{id}/update', 'api\UserController@update');
        Route::post('users/{id}/delete', 'api\UserController@destroy');

        Route::resource('users', 'api\UserController', [
            'except' => [
                'updatePassword',
                'update',
                'destroy'
            ]
        ]);
        Route::put('users/{id}/update-password', [
            'as' => 'user.updatePassword',
            'uses' => 'api\UserController@updatePassword'
        ]);

        // For shared hosting
        Route::get('role-options', [
            'as' => 'roles.getRoles',
            'uses' => 'api\RoleController@getRoles'
        ]);
        Route::post('roles/{id}/update', 'api\RoleController@update');
        Route::post('roles/{id}/delete', 'api\RoleController@destroy');

        Route::resource('roles', 'api\RoleController', [
            'except' => [
                'getRoles',
                'update',
                'destroy'
            ]
        ]);

        // For shared hosting
        Route::post('categories/{id}/update', 'api\CategoryController@update');
        Route::post('categories/{id}/delete', 'api\CategoryController@destroy');

        Route::resource('categories', 'api\CategoryController', [
            'except' => [
                'update',
                'destroy'
            ]
        ]);

        // For shared hosting
        Route::post('expenses/{id}/update', 'api\ExpensesController@update');
        Route::post('expenses/{id}/delete', 'api\ExpensesController@destroy');

        Route::resource('expenses', 'api\ExpensesController', [
            'except' => [
                'expensesCount',
                'update',
                'destroy'
            ]
        ]);

        
        Route::get('expenses-count', [
            'as' => 'expenses.count',
            'uses' => 'api\ExpensesController@expensesCount'
        ]);
    });
});
