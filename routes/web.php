<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TravelAuthorisationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleCheck;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::patch('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/leave-applications', [LeaveApplicationController::class, 'index'])->name('leaveApplications.index');
    Route::get('/leave-applications/create', [LeaveApplicationController::class, 'create'])->name('leaveApplications.create');
    Route::get('/leave-applications/{id}', [LeaveApplicationController::class, 'show'])->name('leaveApplications.detail');
    Route::post('/leave-applications', [LeaveApplicationController::class, 'store'])->name('leaveApplications.store');

    Route::post('/leave-applications/{id}/approve', [LeaveApplicationController::class, 'approve'])->name('leaveApplications.approve');
    Route::post('/leave-applications/{id}/disapprove', [LeaveApplicationController::class, 'disapprove'])->name('leaveApplications.disapprove');

    Route::get('/leave-applications/{id}/reject', [LeaveApplicationController::class, 'rejectPage'])->name('leaveApplications.rejectPage');
    Route::post('/leave-applications/{id}/reject', [LeaveApplicationController::class, 'reject'])->name('leaveApplications.reject');
    Route::post('/leave-applications/{id}/unreject', [LeaveApplicationController::class, 'unreject'])->name('leaveApplications.unreject');

    Route::get('/travel-authorisations', [TravelAuthorisationController::class, 'index'])->name('travelAuthorisations.index');
    Route::get('/travel-authorisations/create', [TravelAuthorisationController::class, 'create'])->name('travelAuthorisations.create');
    Route::get('/travel-authorisations/{id}', [TravelAuthorisationController::class, 'show'])->name('travelAuthorisations.detail');
    Route::post('/travel-authorisations', [TravelAuthorisationController::class, 'store'])->name('travelAuthorisations.store');

    Route::post('/travel-authorisations/{id}/approve', [TravelAuthorisationController::class, 'approve'])->name('travelAuthorisations.approve');
    Route::post('/travel-authorisations/{id}/disapprove', [TravelAuthorisationController::class, 'disapprove'])->name('travelAuthorisations.disapprove');

    Route::get('/travel-authorisations/{id}/reject', [TravelAuthorisationController::class, 'rejectPage'])->name('travelAuthorisations.rejectPage');
    Route::post('/travel-authorisations/{id}/reject', [TravelAuthorisationController::class, 'reject'])->name('travelAuthorisations.reject');
    Route::post('/travel-authorisations/{id}/unreject', [TravelAuthorisationController::class, 'unreject'])->name('travelAuthorisations.unreject');

    Route::group(['middleware' => RoleCheck::class . ':' . RoleEnum::ADMIN], function () {
        Route::resource('/users', UserController::class);
    });
});

require __DIR__ . '/auth.php';