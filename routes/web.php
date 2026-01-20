<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingsController;

// Redirect base URL to registration
Route::get('/', function () {
    return redirect()->route('register');
});

// Guest routes (Authentication)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/payment/create-order', [RegisterController::class, 'createPaymentOrder'])->name('payment.create-order');
    Route::get('/payment/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Payment callback routes (no auth required, CSRF excluded)
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin routes
    Route::get('/registrations', [AdminController::class, 'registrations'])->name('registrations');
    Route::get('/registrations/{user}', [AdminController::class, 'registrationDetails'])->name('registration.details');
    Route::post('/registrations/{user}/accept', [AdminController::class, 'acceptApplication'])->name('registration.accept');
    Route::post('/registrations/{user}/reject', [AdminController::class, 'rejectApplication'])->name('registration.reject');
    Route::post('/registrations/{user}/reaccept', [AdminController::class, 'reacceptApplication'])->name('registration.reaccept');

    // Settings routes (Admin only)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

});
