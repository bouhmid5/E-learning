<?php

use App\Http\Controllers\Auth\SessionAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [SessionAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [SessionAuthController::class, 'login'])->name('login.store');

    Route::get('/register/candidate', [SessionAuthController::class, 'showCandidateRegistration'])->name('register.candidate');
    Route::post('/register/candidate', [SessionAuthController::class, 'registerCandidate'])->name('register.candidate.store');

    Route::get('/register/trainer', [SessionAuthController::class, 'showTrainerRegistration'])->name('register.trainer');
    Route::post('/register/trainer', [SessionAuthController::class, 'registerTrainer'])->name('register.trainer.store');
});

Route::post('/logout', [SessionAuthController::class, 'logout'])
    ->middleware('auth:web,admin')
    ->name('logout');

Route::get('/dashboard', [DashboardController::class, 'general'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/candidate/dashboard', [DashboardController::class, 'candidate'])
    ->middleware(['auth', 'role:candidat'])
    ->name('candidate.dashboard');

Route::get('/trainer/dashboard', [DashboardController::class, 'trainer'])
    ->middleware(['auth', 'role:formateur'])
    ->name('trainer.dashboard');

Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth:admin', 'role:admin'])
    ->name('admin.dashboard');
