<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/user', [UserController::class, 'register']);
    Route::get('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [UserController::class, 'getUser'])->middleware('auth:sanctum');
});

Route::get('/unauthorized', function () {
    return response()->json(['error' => 'Não autorizado'], 401);
})->name('unauthorized');
