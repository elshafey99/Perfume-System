<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Supplier\SupplierController;
use App\Http\Controllers\Api\UnitType\UnitTypeController;
use App\Http\Controllers\Api\ProductType\ProductTypeController;
use App\Http\Controllers\Api\Product\ProductController;
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

    // Users Routes (Employees & Admins)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/{id}', [UserController::class, 'update']);
        Route::post('/{id}/status', [UserController::class, 'changeStatus']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Suppliers Routes
    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/{id}', [SupplierController::class, 'show']);
        Route::put('/{id}', [SupplierController::class, 'update']);
        Route::patch('/{id}', [SupplierController::class, 'update']);
        Route::delete('/{id}', [SupplierController::class, 'destroy']);
    });

    // Unit Types Routes
    Route::prefix('unit-types')->group(function () {
        Route::get('/', [UnitTypeController::class, 'index']);
        Route::post('/', [UnitTypeController::class, 'store']);
        Route::get('/{id}', [UnitTypeController::class, 'show']);
        Route::put('/{id}', [UnitTypeController::class, 'update']);
        Route::patch('/{id}', [UnitTypeController::class, 'update']);
        Route::delete('/{id}', [UnitTypeController::class, 'destroy']);
    });

    // Product Types Routes
    Route::prefix('product-types')->group(function () {
        Route::get('/', [ProductTypeController::class, 'index']);
        Route::post('/', [ProductTypeController::class, 'store']);
        Route::get('/{id}', [ProductTypeController::class, 'show']);
        Route::put('/{id}', [ProductTypeController::class, 'update']);
        Route::patch('/{id}', [ProductTypeController::class, 'update']);
        Route::delete('/{id}', [ProductTypeController::class, 'destroy']);
    });

    // Products Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/low-stock', [ProductController::class, 'getLowStock']);
        Route::get('/barcode/{barcode}', [ProductController::class, 'getByBarcode']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::patch('/{id}', [ProductController::class, 'update']);
        Route::put('/{id}/stock', [ProductController::class, 'updateStock']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
});