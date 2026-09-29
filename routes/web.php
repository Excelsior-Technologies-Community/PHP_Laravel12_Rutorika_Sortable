<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use Rutorika\Sortable\SortableController;

/*
|--------------------------------------------------------------------------
| Task Manager Routes
|--------------------------------------------------------------------------
*/

// Task manager
Route::get('/', [TaskController::class, 'index'])
    ->name('tasks.index');

// Store new task
Route::post('/tasks', [TaskController::class, 'store'])
    ->name('tasks.store');

// Update task priority and status
Route::patch('/tasks/{task}', [TaskController::class, 'update'])
    ->name('tasks.update');

// Task analytics dashboard
Route::get('/dashboard', [TaskController::class, 'dashboard'])
    ->name('tasks.dashboard');

// Rutorika drag & drop sorting
Route::post('/sort', [SortableController::class, 'sort'])
    ->name('sort');