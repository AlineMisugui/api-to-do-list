<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskGroupController;
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
    Route::put('/user/update', [UserController::class, 'update'])->middleware('auth:sanctum');
    Route::put('/change-password', [UserController::class, 'changePassword'])->middleware('auth:sanctum');
    Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
});

Route::controller(TaskGroupController::class)->group(function () {
    Route::post('/task-group', [TaskGroupController::class, 'createTaskGroup'])->middleware('auth:sanctum');
    Route::put('/task-group', [TaskGroupController::class, 'updateTaskGroup'])->middleware('auth:sanctum');
    Route::get('/task-group', [TaskGroupController::class, 'getAllTaskGroups'])->middleware('auth:sanctum');
    Route::get('/task-group/{id}', [TaskGroupController::class, 'findTaskGroup'])->middleware('auth:sanctum');
    Route::delete('/task-group/{id}', [TaskGroupController::class, 'deleteTaskGroup'])->middleware('auth:sanctum');
});

Route::controller(TaskController::class)->group(function () {
    Route::post('/task', [TaskController::class, 'createTask'])->middleware('auth:sanctum');
    Route::get('/task', [TaskController::class, 'getAllTasks'])->middleware('auth:sanctum');
    Route::get('/task/{id}', [TaskController::class, 'findTask'])->middleware('auth:sanctum');
});

Route::get('/unauthorized', function () {
    return response()->json(['error' => 'Não autorizado'], 401);
})->name('unauthorized');
