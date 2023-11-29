<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use ApiException\Http\Middlewares\ApiExceptionMiddleware;

use TobiasFasanok\Users\Classes\Auth;

use Tobiasfasanok\Users\Http\Controllers\GetUserController;
use Tobiasfasanok\Users\Http\Controllers\CreateUserController;
use Tobiasfasanok\Users\Http\Controllers\UpdateUserAccountController;
use Tobiasfasanok\Users\Http\Controllers\UpdateUserPasswordController;
use Tobiasfasanok\Users\Http\Controllers\DeleteUserController;
use Tobiasfasanok\Users\Http\Controllers\LoginController;
use Tobiasfasanok\Users\Http\Controllers\LogoutController;

Route::prefix('api/v1')->middleware([ApiExceptionMiddleware::class])->group(function () {
    Route::post('/login', function (Request $request) {
        return LoginController::call($request);
    });

    Route::post('/createUser', function (Request $request) {
        return CreateUserController::call($request);
    });

    Route::post('/logout', ['middleware' => [Auth::class], function (Request $request) {
        return LogoutController::call($request);
    }]);

    Route::post('/getUser', ['middleware' => [Auth::class], function (Request $request) {
        return GetUserController::call($request);
    }]);

    Route::post('/updateUserAccount', ['middleware' => [Auth::class], function (Request $request) {
        return UpdateUserAccountController::call($request);
    }]);

    Route::post('/updateUserPassword', ['middleware' => [Auth::class], function (Request $request) {
        return UpdateUserPasswordController::call($request);
    }]);

    Route::post('/deleteUser', ['middleware' => [Auth::class], function (Request $request) {
        return DeleteUserController::call($request);
    }]);
});