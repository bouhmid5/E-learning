<?php

use App\Http\Controllers\Auth\SessionAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\CourseCatalogueController;
use App\Http\Controllers\Trainer\CourseController as TrainerCourseController;
use App\Http\Controllers\Trainer\LessonController as TrainerLessonController;
use App\Http\Controllers\Trainer\ResourceController as TrainerResourceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

Route::get('/courses', [CourseCatalogueController::class, 'index'])->name('courses.index');
Route::get('/courses/{cours}', [CourseCatalogueController::class, 'show'])->name('courses.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{categorie}/courses', [CategoryController::class, 'courses'])->name('categories.courses');

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

Route::middleware(['auth', 'role:formateur'])->prefix('trainer')->name('trainer.')->group(function (): void {
    Route::get('/courses', [TrainerCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [TrainerCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [TrainerCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{cours}', [TrainerCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{cours}/edit', [TrainerCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{cours}', [TrainerCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{cours}', [TrainerCourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{cours}/lessons', [TrainerLessonController::class, 'store'])->name('courses.lessons.store');
    Route::post('/courses/{cours}/submit', [TrainerCourseController::class, 'submit'])->name('courses.submit');
    Route::put('/lessons/{lecon}', [TrainerLessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lecon}', [TrainerLessonController::class, 'destroy'])->name('lessons.destroy');
    Route::post('/lessons/{lecon}/resources', [TrainerResourceController::class, 'store'])->name('lessons.resources.store');
    Route::put('/resources/{ressource}', [TrainerResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{ressource}', [TrainerResourceController::class, 'destroy'])->name('resources.destroy');
});

Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth:admin', 'role:admin'])
    ->name('admin.dashboard');
