<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// 1. Welcome / Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// 2. Auth Routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard redirector
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications (pelanggan / kasir / owner)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Leaderboard (Shared but mainly for Pelanggan)
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // ==========================================
    // ROLE: PELANGGAN (CUSTOMER)
    // ==========================================
    Route::middleware(['role:pelanggan'])->group(function () {
        // Reservasi Meja
        Route::get('/booking/meja', [BookingController::class, 'index'])->name('booking.meja');
        Route::get('/booking/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.check-availability');
        Route::get('/booking/calculate-price', [BookingController::class, 'calculatePrice'])->name('booking.calculate-price');
        Route::get('/booking/history', [BookingController::class, 'history'])->name('booking.history');
        Route::get('/booking/create/{table_id}', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
        Route::post('/booking/{id}/extend', [BookingController::class, 'extend'])->name('booking.extend');

        // Payment
        Route::get('/transaction/{id}/payment', [TransactionController::class, 'create'])->name('transaction.payment');
        Route::post('/transaction/{id}/pay', [TransactionController::class, 'store'])->name('transaction.pay');

        // F&B Ordering
        Route::get('/booking/{booking_id}/menu', [OrderController::class, 'menu'])->name('order.menu');
        Route::post('/booking/{booking_id}/order', [OrderController::class, 'store'])->name('order.store');

        // Rating & Feedback
        Route::get('/booking/{booking_id}/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
        Route::post('/booking/{booking_id}/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    });

    // ==========================================
    // ROLE: KASIR (OPERATOR)
    // ==========================================
    Route::middleware(['role:kasir'])->group(function () {
        // Panel Meja & Lampu
        Route::get('/kasir/tables', [TableController::class, 'kasirIndex'])->name('kasir.tables');
        Route::post('/kasir/tables/{id}/toggle-lamp', [TableController::class, 'toggleLamp'])->name('kasir.tables.toggle-lamp');
        Route::post('/kasir/tables/{id}/update-status', [TableController::class, 'updateStatus'])->name('kasir.tables.update-status');
        Route::post('/kasir/tables/{id}/activate-session', [TableController::class, 'activateSession'])->name('kasir.tables.activate-session');

        // Reservasi Masuk
        Route::get('/kasir/bookings', [BookingController::class, 'kasirIndex'])->name('kasir.bookings');
        Route::post('/kasir/bookings/{id}/confirm', [BookingController::class, 'confirmBooking'])->name('kasir.bookings.confirm');

        // Transaksi & Pembayaran
        Route::get('/kasir/transactions', [TransactionController::class, 'kasirIndex'])->name('kasir.transactions');
        Route::post('/kasir/transactions/{id}/verify', [TransactionController::class, 'verifyPayment'])->name('kasir.transactions.verify');

        // Pesanan F&B
        Route::get('/kasir/orders', [OrderController::class, 'kasirIndex'])->name('kasir.orders');
        Route::post('/kasir/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('kasir.orders.update-status');
    });

    // ==========================================
    // ROLE: OWNER
    // ==========================================
    Route::middleware(['role:owner'])->group(function () {
        // Laporan Pendapatan & Grafik
        Route::get('/owner/reports', [ReportController::class, 'index'])->name('owner.reports');

        // Heatmap Penggunaan Meja
        Route::get('/owner/tables/heatmap', [TableController::class, 'heatmap'])->name('owner.tables.heatmap');

        // CRUD Inventaris
        Route::resource('/owner/inventory', InventoryController::class)->names([
            'index' => 'owner.inventory.index',
            'create' => 'owner.inventory.create',
            'store' => 'owner.inventory.store',
            'show' => 'owner.inventory.show',
            'edit' => 'owner.inventory.edit',
            'update' => 'owner.inventory.update',
            'destroy' => 'owner.inventory.destroy',
        ]);

        // CRUD Meja (Pengaturan denah/posisi & harga)
        Route::get('/owner/tables', [TableController::class, 'ownerIndex'])->name('owner.tables.index');
        Route::resource('/owner/tables', TableController::class)->except(['show', 'index'])->names([
            'create' => 'owner.tables.create',
            'store' => 'owner.tables.store',
            'edit' => 'owner.tables.edit',
            'update' => 'owner.tables.update',
            'destroy' => 'owner.tables.destroy',
        ]);

        // Lihat Umpan Balik / Feedback Pelanggan
        Route::get('/owner/feedback', [FeedbackController::class, 'ownerIndex'])->name('owner.feedback.index');
    });

});

require __DIR__.'/auth.php';
