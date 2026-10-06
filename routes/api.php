<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\TaskCheckItemController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 誰でもアクセスできるAPI
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// ログインが必要なAPI
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
  
    Route::put('/projects/{project}', [ProjectController::class, 'update']);

    Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);

    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle']);
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    Route::get('/tasks/{task}/check-items', [TaskCheckItemController::class, 'index']);
    Route::patch('/tasks/{task}/check-items/{checkItem}', [TaskCheckItemController::class, 'update']);
    Route::post('/tasks/{task}/check-items', [TaskCheckItemController::class, 'store']);
    Route::delete('/tasks/{task}/check-items/{checkItem}', [TaskCheckItemController::class, 'destroy']);
});




