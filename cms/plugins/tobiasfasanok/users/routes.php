<?php

use Illuminate\Support\Facades\Route;

use ApiException\Http\Middlewares\ApiExceptionMiddleware;

use TobiasFasanok\Users\Classes\Auth;

use Tobiasfasanok\Users\Http\Controllers\UserController;

Route::prefix('api/v1')->middleware([ApiExceptionMiddleware::class])->group(function () {
    Route::post('/login', [UserController::class, 'login']);

    Route::post('/createUser', [UserController::class, 'create']);

    Route::middleware([Auth::class])->group(function () {
        Route::post('/getUser', [UserController::class, 'get']);
    
        Route::post('/logout', [UserController::class, 'logout']);
    
        Route::post('/updateUserAccount', [UserController::class, 'updateAccount']);
    
        Route::post('/updateUserPassword', [UserController::class, 'updatePassword']);
    
        Route::post('/deleteUser', [UserController::class, 'delete']);
    });
});