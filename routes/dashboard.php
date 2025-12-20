<?php

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\Auth\AuthController;
use App\Http\Controllers\Dashboard\Auth\ForgotController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard\Auth\ResetPasswordController;






Route::group([
    'prefix' => LaravelLocalization::setLocale() . '/dashboard',
    'as' => 'dashboard.',
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });


    ############################### Auth Routes ############################################
    Route::get('login',       [AuthController::class, 'login'])->name('login');
    Route::post('login/post', [AuthController::class, 'loginPost'])->name('login.post');
    Route::post('logout',     [AuthController::class, 'logout'])->name('logout');

    ############################### Forgot Password Routes ############################################
    Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
        Route::get('email',          [ForgotController::class, 'showEmailForm'])->name('email');
        Route::post('email',         [ForgotController::class, 'sendOTP'])->name('sendOTP');
        Route::get('verify/{email}', [ForgotController::class, 'showOtpForm'])->name('showOtpForm');
        Route::post('verify',        [ForgotController::class, 'verifyOtp'])->name('verifyOtp');

        ############################### Reset Password Routes ############################################
        Route::get('reset/{email}',  [ResetPasswordController::class, 'showResetForm'])->name('resetForm');
        Route::post('reset',         [ResetPasswordController::class, 'resetPassword'])->name('reset');
    });

    ############################### Admin Routes ############################################
    Route::group(['middleware' => 'auth:admin'], function () {

        ############################### Auth Routes ############################################
        Route::get('home', [AuthController::class, 'home'])->name('home');
        Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
        Route::get('security', [ProfileController::class, 'security'])->name('security');
        Route::post('profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
        Route::post('profile/update/password', [ProfileController::class, 'profileUpdatePassword'])->name('profile.update.password');

        ############################### Role Routes ############################################
        Route::get('roles', function () {
            return view('dashboard.roles.index');
        })->middleware('can:roles')->name('roles.index');
        ############################### End Role Routes ############################################

        ############################### Admin Routes ############################################
        Route::get('admins', function () {
            return view('dashboard.admins.index');
        })->middleware('can:admins')->name('admins.index');
        ############################### End Amin Routes ############################################

        ############################### Users Routes ############################################
        // Route::get('users',                  [UserController::class, 'index'])->middleware('can:users')->name('users.index');
        // Route::get('user/profile/{id}',      [UserController::class, 'userProfile'])->middleware('can:users')->name('user.profile');
        // ############################### End Users Routes #########################################

        ############################### Locations Routes ############################################
        Route::get('locations', function () {
            return view('dashboard.locations.countries.index');
        })->name('locations.index');
        Route::get('governorates', function () {
            return view('dashboard.locations.governorates.index');
        })->name('governorates.index');
        Route::get('governorate-centers', function () {
            return view('dashboard.locations.center-governorate.index');
        })->name('centers.index');
        ############################### End Locations Routes ############################################ 

        ############################### Start of Subscription Plans Routes ############################################
        Route::get('plans', function () {
            return view('dashboard.plans.index');
        })->middleware('can:subscription-plans')->name('subscription-plans.index');
        ############################### End of Subscription Plans Routes ############################################

        ############################### Start of Properties Routes ############################################
        Route::get('properties', function () {
            return view('dashboard.properties.index');
        })->name('properties.index');
        ############################### End of Properties Routes ############################################

        ############################### Start of Buildings Routes ############################################
        Route::get('buildings', function () {
            return view('dashboard.buildings.index');
        })->name('buildings.index');
        ############################### End of Buildings Routes ############################################

        ############################### Start of Property Roles Routes ############################################
        Route::get('property-roles', function () {
            return view('dashboard.property-roles.index');
        })->name('property-roles.index');
        ############################### End of Property Roles Routes ############################################

        ############################### settings Routes ############################################
        Route::get('settings',            [SettingsController::class, 'genralSetting'])->middleware('can:settings')->name('settings');
        ############################### End settings Routes ############################################

    });
});
