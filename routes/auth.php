<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Console\View\Components\Confirm;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Email;

Route::middleware('guest')->group(function () {
    Route::get('/register',[RegisterController::class,'create'])->name('register');
    Route::post('/register',[RegisterController::class,'store']);

    Route::get('/login',[AuthenticatedController::class,'create'])->name('login');
    Route::post('/login',[AuthenticatedController::class,'store']);

    // reset password
    Route::get('/forgot-password', [ResetPasswordController::class,'requestPass'])->name('password.request');
    Route::post('/forgot-password',[ResetPasswordController::class,'sendEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetForm' ])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetHandler' ])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedController::class,'destroy'])->name('logout');

    // Email Vertification route
    Route::get('/email/verify',[EmailVerificationController::class,'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}',[EmailVerificationController::class,'handler'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class,'resend'])->middleware('throttle:6,1')->name('verification.send');

    // confirm password
    Route::get('/confirm-password', [ConfirmPasswordController::class, 'create'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmPasswordController::class, 'store'])->middleware('throttle:6,1');

});


