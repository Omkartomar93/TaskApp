<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::get('view', [TaskController::class, 'view']);
Route::get('tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
