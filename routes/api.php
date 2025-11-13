<?php

use App\Http\Controllers\V1\Api\ApiController;
use App\Http\Controllers\V1\Api\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->middleware(['throttle:api'])->group(function () {
    Route::get('/gettoken', [TokenController::class, 'getToken']);
});

Route::prefix('v1')->middleware(['throttle:api', 'auth:sanctum'])->group(function () {
    Route::apiResource('flights', ApiController::class);
});
