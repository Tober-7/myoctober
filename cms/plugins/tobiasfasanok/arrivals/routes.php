<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use ApiException\Http\Middlewares\ApiExceptionMiddleware;

use TobiasFasanok\Users\Classes\Auth;

use Tobiasfasanok\Arrivals\Http\Controllers\GetArrivalsController;
use Tobiasfasanok\Arrivals\Http\Controllers\CreateArrivalController;
use Tobiasfasanok\Arrivals\Http\Controllers\DeleteArrivalController;

Route::prefix('api/v1')->middleware([ApiExceptionMiddleware::class, Auth::class])->group(function () {
    Route::post('/getArrivals', function (Request $request) {
        return GetArrivalsController::call($request);
    });

    Route::post('/createArrival', function (Request $request) {
        return CreateArrivalController::call($request);
    });

    Route::post('/deleteArrival/{arrival}', function (int $id, Request $request) {
        return DeleteArrivalController::call($id, $request);
    });
});