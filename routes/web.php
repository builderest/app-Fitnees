<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\GeneratorController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('dashboard', DashboardController::class)->middleware('verified')->name('dashboard');

    Route::get('exercises', [ExerciseController::class, 'index'])->name('exercises.index');
    Route::get('exercises/{exercise}', [ExerciseController::class, 'show'])->name('exercises.show');

    Route::get('generator', [GeneratorController::class, 'index'])->name('generator.index');
    Route::post('generator', [GeneratorController::class, 'generate'])->name('generator.generate');

    Route::get('routines', [RoutineController::class, 'index'])->name('routines.index');
    Route::get('routines/create', [RoutineController::class, 'create'])->name('routines.create');
    Route::post('routines', [RoutineController::class, 'store'])->name('routines.store');
    Route::post('routines/{program}/duplicate', [RoutineController::class, 'duplicate'])->name('routines.duplicate');

    Route::get('sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::post('sessions', [SessionController::class, 'store'])->name('sessions.store');

    Route::get('progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::post('progress', [ProgressController::class, 'store'])->name('progress.store');

    Route::get('pricing', [PricingController::class, 'index'])->name('pricing');
    Route::post('pricing/activate', [PricingController::class, 'activate'])->name('pricing.activate');

    Route::middleware(['role:admin,coach'])->group(function () {
        Route::get('admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('admin/exercises', [AdminController::class, 'storeExercise'])->name('admin.exercises.store');
    });
});
