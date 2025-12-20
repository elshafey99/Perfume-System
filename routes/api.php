<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Category\CategoryController;
use Illuminate\Support\Facades\Route;

// Auth Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/resend-code', [AuthController::class, 'resendCode']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'profile']);
        Route::post('/', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
    });

    // Categories Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/parents', [CategoryController::class, 'parents']);
        Route::get('/parent/{parentId}', [CategoryController::class, 'byParent']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    // Roles Routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/permissions', [RoleController::class, 'permissions']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::patch('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });
});
