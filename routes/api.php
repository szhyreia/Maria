<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PostController; // Example controller

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // User profile routes (available to all authenticated users)
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
    // Routes that require specific roles/permissions
    
    // Admin only routes
    Route::middleware(['role:super-admin|admin'])->group(function () {
        // User management
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:delete users');
        
        // Role management
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles/{role}', [RoleController::class, 'show']);
        Route::put('/roles/{role}', [RoleController::class, 'update']);
        Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
        Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions']);
    });
    
    // Content routes with specific permissions
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']); // All authenticated users can view
        Route::get('/{post}', [PostController::class, 'show']); // All authenticated users can view
        
        // Routes requiring specific permissions
        Route::middleware(['permission:create posts'])->post('/', [PostController::class, 'store']);
        Route::middleware(['permission:edit posts'])->put('/{post}', [PostController::class, 'update']);
        Route::middleware(['permission:delete posts'])->delete('/{post}', [PostController::class, 'destroy']);
        Route::middleware(['permission:publish posts'])->post('/{post}/publish', [PostController::class, 'publish']);
    });
});