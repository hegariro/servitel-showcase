<?php

use App\Http\Controllers\DogController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {

    Route::apiResource('products', ProductController::class);

    Route::prefix('partner')->group(function () {
        Route::get('/dogs', [DogController::class, 'getRandomDogImage']);
    });

});
