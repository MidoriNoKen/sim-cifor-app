<?php

use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\ProfileController;
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
    Route::post('/leave-applications/{id}/approve-by-supervisor', [LeaveApplicationController::class, 'approveBySupervisor'])->name('leaveApplications.approveBySupervisor');
    Route::post('/leave-applications/{id}/disapprove-by-supervisor', [LeaveApplicationController::class, 'disapproveBySupervisor'])->name('leaveApplications.disapproveBySupervisor');

    Route::resource('/travel-authorisations', null);

});



require __DIR__ . '/auth.php';