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
Route::get('/', [
    TaskController::class,
    'index'
])->name('tasks.index');


// Store task
Route::post('/tasks', [
    TaskController::class,
    'store'
])->name('tasks.store');


// Update task
Route::patch('/tasks/{task}', [
    TaskController::class,
    'update'
])->name('tasks.update');


// Delete task
Route::delete('/tasks/{task}', [
    TaskController::class,
    'destroy'
])->name('tasks.destroy');


// Duplicate task
Route::post('/tasks/{task}/duplicate', [
    TaskController::class,
    'duplicate'
])->name('tasks.duplicate');


// Bulk delete
Route::post('/tasks/bulk-delete', [
    TaskController::class,
    'bulkDelete'
])->name('tasks.bulk-delete');


// Bulk status update
Route::post('/tasks/bulk-status', [
    TaskController::class,
    'bulkStatus'
])->name('tasks.bulk-status');


// Bulk priority update
Route::post('/tasks/bulk-priority', [
    TaskController::class,
    'bulkPriority'
])->name('tasks.bulk-priority');


// CSV export
Route::get('/tasks/export/csv', [
    TaskController::class,
    'export'
])->name('tasks.export');


// Analytics dashboard
Route::get('/dashboard', [
    TaskController::class,
    'dashboard'
])->name('tasks.dashboard');


// Rutorika drag & drop sorting
Route::post('/sort', [
    SortableController::class,
    'sort'
])->name('sort');