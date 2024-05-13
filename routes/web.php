<?php

use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelAuthorisationController;
use App\Http\Controllers\UserController;
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

    Route::resource('/users', UserController::class);

    Route::get('/leave-applications', [LeaveApplicationController::class, 'index'])->name('leaveApplications.index');
    Route::get('/leave-applications/create', [LeaveApplicationController::class, 'create'])->name('leaveApplications.create');
    Route::get('/leave-applications/{id}', [LeaveApplicationController::class, 'show'])->name('leaveApplications.detail');
    Route::post('/leave-applications', [LeaveApplicationController::class, 'store'])->name('leaveApplications.store');
    Route::get('/leave-applications/{id}/reject', [LeaveApplicationController::class, 'rejectPage'])->name('leaveApplications.rejectPage');
    Route::post('/leave-applications/{id}/unreject', [LeaveApplicationController::class, 'unreject'])->name('leaveApplications.unreject');
    Route::post('/leave-applications/{id}/reject', [LeaveApplicationController::class, 'reject'])->name('leaveApplications.reject');
    Route::post('/leave-applications/{id}/approve-by-supervisor', [LeaveApplicationController::class, 'approveBySupervisor'])->name('leaveApplications.approveBySupervisor');
    Route::post('/leave-applications/{id}/disapprove-by-supervisor', [LeaveApplicationController::class, 'disapproveBySupervisor'])->name('leaveApplications.disapproveBySupervisor');
    Route::post('/leave-applications/{id}/approve-by-manager', [LeaveApplicationController::class, 'approveByManager'])->name('leaveApplications.approveByManager');
    Route::post('/leave-applications/{id}/disapprove-by-manager', [LeaveApplicationController::class, 'disapproveByManager'])->name('leaveApplications.disapproveByManager');

    Route::get('/travel-authorisations', [TravelAuthorisationController::class, 'index'])->name('travelAuthorisations.index');
    Route::get('/travel-authorisations/create', [TravelAuthorisationController::class, 'create'])->name('travelAuthorisations.create');
    Route::get('/travel-authorisations/{id}', [TravelAuthorisationController::class, 'show'])->name('travelAuthorisations.detail');
    Route::post('/travel-authorisations', [TravelAuthorisationController::class, 'store'])->name('travelAuthorisations.store');
    Route::get('/travel-authorisations/{id}/reject', [TravelAuthorisationController::class, 'reject'])->name('travelAuthorisations.reject');
    Route::post('/travel-authorisations/{id}/unreject', [TravelAuthorisationController::class, 'unreject'])->name('travelAuthorisations.unreject');
    Route::post('/travel-authorisations/{id}/reject', [TravelAuthorisationController::class, 'ignore'])->name('travelAuthorisations.ignore');
    Route::post('/travel-authorisations/{id}/approve-by-supervisor', [TravelAuthorisationController::class, 'approveBySupervisor'])->name('travelAuthorisations.approveBySupervisor');
    Route::post('/travel-authorisations/{id}/disapprove-by-supervisor', [TravelAuthorisationController::class, 'disapproveBySupervisor'])->name('travelAuthorisations.disapproveBySupervisor');
    Route::post('/travel-authorisations/{id}/approve-by-manager', [TravelAuthorisationController::class, 'approveByManager'])->name('travelAuthorisations.approveByManager');
    Route::post('/travel-authorisations/{id}/disapprove-by-manager', [TravelAuthorisationController::class, 'disapproveByManager'])->name('travelAuthorisations.disapproveByManager');
});



require __DIR__ . '/auth.php';