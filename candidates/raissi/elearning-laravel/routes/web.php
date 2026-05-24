<?php

use App\Http\Controllers\Auth\SessionAuthController;
use App\Http\Controllers\PublicCertificateVerificationController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CourseValidationController;
use App\Http\Controllers\Admin\TrainerValidationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Candidate\EnrollmentController;
use App\Http\Controllers\Candidate\EvaluationSubmissionController;
use App\Http\Controllers\Candidate\CertificateController;
use App\Http\Controllers\Candidate\ProgressionController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\CourseCatalogueController;
use App\Http\Controllers\Trainer\CourseController as TrainerCourseController;
use App\Http\Controllers\Trainer\EvaluationController as TrainerEvaluationController;
use App\Http\Controllers\Trainer\LessonController as TrainerLessonController;
use App\Http\Controllers\Trainer\OptionReponseController as TrainerOptionReponseController;
use App\Http\Controllers\Trainer\QuestionController as TrainerQuestionController;
use App\Http\Controllers\Trainer\ResourceController as TrainerResourceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

Route::get('/courses', [CourseCatalogueController::class, 'index'])->name('courses.index');
Route::get('/courses/{cours}', [CourseCatalogueController::class, 'show'])->name('courses.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{categorie}/courses', [CategoryController::class, 'courses'])->name('categories.courses');
Route::get('/certificates/verify/{codeVerification}', [PublicCertificateVerificationController::class, 'show'])->name('certificates.verify');

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
    Route::get('/courses/{cours}/evaluations', [TrainerEvaluationController::class, 'index'])->name('courses.evaluations.index');
    Route::get('/courses/{cours}/evaluations/create', [TrainerEvaluationController::class, 'create'])->name('courses.evaluations.create');
    Route::post('/courses/{cours}/evaluations', [TrainerEvaluationController::class, 'store'])->name('courses.evaluations.store');
    Route::get('/courses/{cours}', [TrainerCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{cours}/edit', [TrainerCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{cours}', [TrainerCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{cours}', [TrainerCourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{cours}/lessons', [TrainerLessonController::class, 'store'])->name('courses.lessons.store');
    Route::post('/courses/{cours}/submit', [TrainerCourseController::class, 'submit'])->name('courses.submit');
    Route::get('/evaluations/{evaluation}/edit', [TrainerEvaluationController::class, 'edit'])->name('evaluations.edit');
    Route::put('/evaluations/{evaluation}', [TrainerEvaluationController::class, 'update'])->name('evaluations.update');
    Route::delete('/evaluations/{evaluation}', [TrainerEvaluationController::class, 'destroy'])->name('evaluations.destroy');
    Route::post('/evaluations/{evaluation}/questions', [TrainerQuestionController::class, 'store'])->name('evaluations.questions.store');
    Route::put('/questions/{question}', [TrainerQuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [TrainerQuestionController::class, 'destroy'])->name('questions.destroy');
    Route::post('/questions/{question}/options', [TrainerOptionReponseController::class, 'store'])->name('questions.options.store');
    Route::put('/options/{option}', [TrainerOptionReponseController::class, 'update'])->name('options.update');
    Route::delete('/options/{option}', [TrainerOptionReponseController::class, 'destroy'])->name('options.destroy');
    Route::put('/lessons/{lecon}', [TrainerLessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lecon}', [TrainerLessonController::class, 'destroy'])->name('lessons.destroy');
    Route::post('/lessons/{lecon}/resources', [TrainerResourceController::class, 'store'])->name('lessons.resources.store');
    Route::put('/resources/{ressource}', [TrainerResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{ressource}', [TrainerResourceController::class, 'destroy'])->name('resources.destroy');
});

Route::post('/courses/{cours}/enroll', [EnrollmentController::class, 'enroll'])
    ->middleware(['auth', 'role:candidat'])
    ->name('courses.enroll');

Route::middleware(['auth', 'role:candidat'])->prefix('candidate')->name('candidate.')->group(function (): void {
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{inscription}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::get('/enrollments/{inscription}/lessons', [EnrollmentController::class, 'lessons'])->name('enrollments.lessons');
    Route::post('/enrollments/{inscription}/lessons/{lecon}/complete', [ProgressionController::class, 'complete'])->name('enrollments.lessons.complete');
    Route::get('/enrollments/{inscription}/progress', [ProgressionController::class, 'progress'])->name('enrollments.progress');
    Route::get('/enrollments/{inscription}/resources/{ressource}/download', [EnrollmentController::class, 'download'])->name('enrollments.resources.download');
    Route::get('/enrollments/{inscription}/certificate/eligibility', [CertificateController::class, 'eligibility'])->name('enrollments.certificate.eligibility');
    Route::post('/enrollments/{inscription}/certificate', [CertificateController::class, 'generate'])->name('enrollments.certificate.generate');
    Route::get('/evaluations/{evaluation}', [EvaluationSubmissionController::class, 'show'])->name('evaluations.show');
    Route::post('/evaluations/{evaluation}/start', [EvaluationSubmissionController::class, 'start'])->name('evaluations.start');
    Route::post('/evaluations/{evaluation}/submit', [EvaluationSubmissionController::class, 'submit'])->name('evaluations.submit');
    Route::get('/submissions/{soumission}', [EvaluationSubmissionController::class, 'submission'])->name('submissions.show');
    Route::get('/results', [EvaluationSubmissionController::class, 'results'])->name('results');
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificat}/download', [CertificateController::class, 'download'])->name('certificates.download');
});

Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth:admin', 'role:admin'])
    ->name('admin.dashboard');

Route::middleware(['auth:admin', 'role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.status');

    Route::get('/trainers/pending', [TrainerValidationController::class, 'pending'])->name('trainers.pending');
    Route::post('/trainers/{formateur}/validate', [TrainerValidationController::class, 'validateTrainer'])->name('trainers.validate');
    Route::post('/trainers/{formateur}/reject', [TrainerValidationController::class, 'rejectTrainer'])->name('trainers.reject');
    Route::post('/justificatifs/{justificatif}/validate', [TrainerValidationController::class, 'validateJustificatif'])->name('justificatifs.validate');
    Route::post('/justificatifs/{justificatif}/reject', [TrainerValidationController::class, 'rejectJustificatif'])->name('justificatifs.reject');

    Route::get('/courses/pending', [CourseValidationController::class, 'pending'])->name('courses.pending');
    Route::post('/courses/{cours}/validate', [CourseValidationController::class, 'validateCourse'])->name('courses.validate');
    Route::post('/courses/{cours}/reject', [CourseValidationController::class, 'rejectCourse'])->name('courses.reject');

    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{categorie}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{categorie}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{categorie}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
});
