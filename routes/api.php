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
        Route::resource('users', 'api\UserController', [
            'except' => [
                'updatePassword'
            ]
        ]);
        Route::put('users/{id}/update-password', [
            'as' => 'user.updatePassword',
            'uses' => 'api\UserController@updatePassword'
        ]);
        Route::resource('roles', 'api\RoleController', [
            'except' => [
                'getRoles'
            ]
        ]);
        Route::resource('categories', 'api\CategoryController');
        Route::resource('expenses', 'api\ExpensesController', [
            'except' => [
                'expensesCount'
            ]
        ]);
        Route::get('expenses-count', [
            'as' => 'expenses.count',
            'uses' => 'api\ExpensesController@expensesCount'
        ]);
    });
});
