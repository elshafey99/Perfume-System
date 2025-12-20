<?php

use App\Http\Controllers\Api\BookingDateController;
use App\Http\Controllers\Api\ClassRoomController;
use App\Http\Controllers\Api\DeleteAccountController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FloorController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\HallController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StudyMaterialController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\StageController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\LectureController;
use App\Http\Controllers\Api\SmsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['language']], function () {

    //login
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/role', [RoleController::class, 'index']);
    Route::get('/permissions', [RoleController::class, 'getPermission']);

    Route::post('/store_role', [RoleController::class, 'store']);

    Route::get('/role/{id}', [RoleController::class, 'show']);

    Route::post('/role/{id}', [RoleController::class, 'update']);

    Route::delete('/role/{id}', [RoleController::class, 'destroy']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        // profile
        Route::get('/profile', [ProfileController::class, 'profile']);
        Route::post('/profile', [ProfileController::class, 'updateProfile']);
        Route::post('/change-password', [PasswordController::class, 'changePassword']);
        Route::get('/logout', [LogoutController::class, 'logout']);
        //delete_account
        Route::get('delete_account', [DeleteAccountController::class, 'deleteAccount']);

        // Route::resource('teachers', TeacherController::class);
        Route::resource('class_rooms', ClassRoomController::class);
        Route::resource('study_materials', StudyMaterialController::class);

        Route::prefix('stages')->group(function () {
            Route::get('/', [StageController::class, 'index']);
            Route::post('/', [StageController::class, 'store']);
            Route::post('/{id}', [StageController::class, 'update']);
            Route::delete('/{id}', [StageController::class, 'destroy']);
        });

        Route::prefix('schools')->group(function () {
            Route::get('/', [SchoolController::class, 'index']);
            Route::post('/', [SchoolController::class, 'store']);
            Route::post('/{id}', [SchoolController::class, 'update']);
            Route::delete('/{id}', [SchoolController::class, 'destroy']);
        });

        Route::prefix('languages')->group(function () {
            Route::get('/', [LanguageController::class, 'index']);
            Route::post('/', [LanguageController::class, 'store']);
            Route::post('/{id}', [LanguageController::class, 'update']);
            Route::delete('/{id}', [LanguageController::class, 'destroy']);
        });

        Route::prefix('departments')->group(function () {
            Route::get('/', [DepartmentController::class, 'index']);
            Route::post('/', [DepartmentController::class, 'store']);
            Route::post('/{id}', [DepartmentController::class, 'update']);
            Route::delete('/{id}', [DepartmentController::class, 'destroy']);
        });

        Route::prefix('groups')->group(function () {
            Route::get('/', [GroupController::class, 'index']);
            Route::post('/', [GroupController::class, 'store']);
            Route::post('/{id}', [GroupController::class, 'update']);
            Route::delete('/{id}', [GroupController::class, 'destroy']);
        });

        Route::prefix('floors')->group(function () {
            Route::get('/', [FloorController::class, 'index']);
            Route::post('/', [FloorController::class, 'store']);
            Route::post('/{id}', [FloorController::class, 'update']);
            Route::delete('/{id}', [FloorController::class, 'destroy']);
        });

        Route::prefix('halls')->group(function () {
            Route::get('/floors', [HallController::class, 'getAllFloors']);
            Route::get('/', [HallController::class, 'index']);
            Route::post('/', [HallController::class, 'store']);
            Route::post('/{id}', [HallController::class, 'update']);
            Route::delete('/{id}', [HallController::class, 'destroy']);
        });

        Route::prefix('teachers')->group(function () {
            Route::get('/', [TeacherController::class, 'index']);
            Route::get('/all_teachers', [TeacherController::class, 'allTeachers']);
            Route::post('/', [TeacherController::class, 'store']);
            Route::get('/{id}', [TeacherController::class, 'show']);
            Route::post('/{id}', [TeacherController::class, 'update']);
            Route::delete('/{id}', [TeacherController::class, 'destroy']);
        });

        Route::prefix('booking_dates')->group(function () {
            Route::get('/{id}', [BookingDateController::class, 'index']);
            Route::post('/', [BookingDateController::class, 'store']);
            Route::post('/{id}', [BookingDateController::class, 'update']);
            Route::delete('/{id}', [BookingDateController::class, 'destroy']);
        });

        Route::prefix('students')->group(function () {
            Route::get('/', [StudentController::class, 'index']);
            Route::post('/', [StudentController::class, 'store']);
            Route::post('/assign_teacher', [StudentController::class, 'assignTeacher']);
            Route::get('/{id}', [StudentController::class, 'show']);
            Route::post('/{id}', [StudentController::class, 'update']);
            Route::delete('/{id}', [StudentController::class, 'destroy']);
        });

        Route::prefix('packages')->group(function () {
            Route::get('/all_materials', [PackageController::class, 'AllMaterials']);
            Route::get('/', [PackageController::class, 'index']);
            Route::post('/', [PackageController::class, 'store']);
            Route::get('/{package}', [PackageController::class, 'show']);
            Route::post('/{package}', [PackageController::class, 'update']);
            Route::delete('/{package}', [PackageController::class, 'destroy']);
        });

        Route::prefix('lectures')->group(function () {
            Route::get('/', [LectureController::class, 'index']);
            Route::post('/', [LectureController::class, 'store']);
            Route::post('/{id}', [LectureController::class, 'update']);
            Route::delete('/{id}', [LectureController::class, 'destroy']);
        });
        Route::get('/recipients', [SmsController::class, 'getRecipients']);
    });
});