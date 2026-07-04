<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('employee.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/process-scan', [AttendanceController::class, 'processScan'])
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/scan', function () {
        return view('employee.scan');
    })->name('scan');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/employee/dashboard', function () {
        return view('employee.dashboard');
    })->name('employee.dashboard');

    Route::get('/employee/profile', [\App\Http\Controllers\ProfileController::class, 'index'])
        ->name('employee.profile');

    Route::post('/employee/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('employee.profile.update');
});

Route::get('/employee/history', function () {
    $attendances = \App\Models\Attendance::where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('employee.history', compact('attendances'));
})->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/employee/leave', [LeaveController::class, 'index'])
        ->name('leave.index');

    Route::post('/employee/leave', [LeaveController::class, 'store'])
        ->name('leave.store');
});

Route::get('/qr-monitor', [AttendanceController::class, 'showQrGenerator'])
    ->name('qr.monitor');
Route::get('/generate-qr', [AttendanceController::class, 'generateNewToken'])
    ->name('generate.qr');

require __DIR__ . '/auth.php';
