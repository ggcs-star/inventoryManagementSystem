<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailOtpController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Default Entry
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Guest Routes (ONLY login/register)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Forgot Password (ALLOW BOTH guest + logged-in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmail']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
        ->middleware('throttle:3,10');

    Route::get('/reset-password', [ForgotPasswordController::class, 'showReset']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
        ->middleware('throttle:5,10');
});

/*
|--------------------------------------------------------------------------
| OTP Verification (AUTH but NOT verified users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/email/verify-otp', [EmailOtpController::class, 'show'])->name('otp.notice');
    Route::post('/email/verify-otp', [EmailOtpController::class, 'verify'])->name('otp.verify');
    Route::post('/email/resend-otp', [EmailOtpController::class, 'resend'])->name('otp.resend');
});

/*
|--------------------------------------------------------------------------
| Verified + Role Based Dashboards
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', fn () => view('admin.dashboard'));
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', fn () => view('user.dashboard'));
    });
});
