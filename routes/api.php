<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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
//======================
//=====   users   =====
//=====================
Route::group([
    'middleware' => 'api',
    'throttle:30,1',
], function ($router) {
    Route::post('/users', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
});
Route::middleware(['auth:api', 'role:admin', 'throttle:30,1'])->group(function () {
    Route::post('/users/{id}/assign-role', [UserController::class, 'assignRole']);
});


//=====================
//=====   Tasks   =====
//=====================
Route::middleware(['auth:api', 'role:admin,manager', 'throttle:30,1'])->group(function () {
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::post('/update-task/{task}', [TaskController::class, 'update'])->name('api.update-task');
});
Route::middleware(['auth:api', 'role:admin,manager', 'throttle:30,1'])->group(function () {
    Route::post('/tasks/{task}/reassign', [TaskController::class, 'reassignTask']);
});
Route::middleware(['auth:api', 'role:admin,manager,developer', 'throttle:30,1'])->group(function () {
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateTaskStatus']);
});
Route::middleware(['auth:api', 'role:admin,manager,developer', 'throttle:30,1'])->group(function () {
    Route::post('/tasks/{id}/comments', [TaskController::class, 'addComment']);
});
Route::middleware(['auth:api', 'role:admin,manager,developer', 'throttle:30,1'])->group(function () {
    Route::post('tasks/{taskId}/attachments', [AttachmentController::class, 'store']);
});


// Add a dependency between tasks (POST request)
Route::post('tasks/{taskId}/dependencies', [TaskController::class, 'addDependency']);
// Route to remove a single dependency from a task
Route::post('tasks/{taskId}/delete-dependencies', [TaskController::class, 'removeDependency']);
