<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function () {
    Route::post('/user', [UserController::class, 'register']);
});

Route::controller(UserController::class)->group(function () {
    Route::post('/login', [UserController::class, 'login']);
})->middleware(['verified']);
