<?php

use Illuminate\Support\Facades\Route;

use ApiException\Http\Middlewares\ApiExceptionMiddleware;

use TobiasFasanok\Users\Classes\Auth;

use Tobiasfasanok\Arrivals\Http\Controllers\ArrivalController;

Route::prefix('api/v1')->middleware([ApiExceptionMiddleware::class, Auth::class])->group(function () {
    Route::post('/getArrivals', [ArrivalController::class, 'get']);

    Route::post('/createArrival', [ArrivalController::class, 'create']);

    Route::post('/deleteArrival/{arrival}', [ArrivalController::class, 'delete']);
});