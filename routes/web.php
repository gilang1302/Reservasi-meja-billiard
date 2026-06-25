<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Guest Route
Route::get('/', function () {
    if (Auth::check()) {
        if (in_array(Auth::user()->role, ['admin', 'owner', 'kasir'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('reservations.index');
    }
    return redirect()->route('login');
});

// Authenticated Customer Routes
Route::middleware(['auth'])->group(function () {
    // Profile Management (Breeze defaults)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking & Calendar
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/calendar', [ReservationController::class, 'calendar'])->name('reservations.calendar');
    Route::get('/reservations/calendar/data', [ReservationController::class, 'calendarData'])->name('reservations.calendar.data');
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Payments Strategy Checkout & Confirmation
    Route::get('/payments/{id}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
    Route::post('/payments/{id}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
});

// Authenticated Admin/Staff Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/reservations/{id}/status', [AdminController::class, 'updateReservationStatus'])->name('admin.reservations.status');
    Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
    Route::post('/payments/{id}/approve', [AdminController::class, 'approvePayment'])->name('admin.payments.approve');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('admin.notifications');
    
    // Table Management
    Route::get('/tables', [AdminController::class, 'tables'])->name('admin.tables');
    Route::post('/tables/{id}/status', [AdminController::class, 'updateTableStatus'])->name('admin.tables.status');
});

require __DIR__.'/auth.php';