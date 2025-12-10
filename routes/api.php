<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('admin')->group(function () {
    Route::get('/cards', [App\Http\Controllers\Api\Admin\CardController::class, 'index']);
    Route::get('/cards/{id}', [App\Http\Controllers\Api\Admin\CardController::class, 'show']);
    Route::post('/cards', [App\Http\Controllers\Api\Admin\CardController::class, 'store']);
});



Route::prefix('auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\Api\Admin\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Api\Admin\AuthController::class, 'login']);
    //Route::post('/logout', [App\Http\Controllers\Api\Admin\AuthController::class, 'logout'])->middleware('auth:api');
});


Route::middleware('check.role:user')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/cards/random', [App\Http\Controllers\Api\User\CardController::class, 'randomCard']);
        Route::get('/cards', [App\Http\Controllers\Api\User\CardController::class, 'userCards']);
    });
    Route::prefix('auth')->group(function () {
        Route::get('/me', [App\Http\Controllers\Api\User\AuthController::class, 'me']);
        Route::post('/logout', [App\Http\Controllers\Api\Admin\AuthController::class, 'logout']);
    });
});
