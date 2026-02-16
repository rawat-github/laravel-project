<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TaskController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::apiResourceCustom('demo' , DemoController::class,['id']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/log-test', function() {
    throw new \Exception("Test error to generate laravel.log");
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('tasks', [TaskController::class, 'index']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::put('tasks/{task}', [TaskController::class, 'update']);
    Route::put('tasks/{task}/complete', [TaskController::class, 'complete']);
    Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
});
