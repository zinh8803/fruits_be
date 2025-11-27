<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('admin')->group(function () {
    Route::get('/cards', [App\Http\Controllers\Api\Admin\CardController::class, 'index']);
    Route::post('/cards', [App\Http\Controllers\Api\Admin\CardController::class, 'store']);
});