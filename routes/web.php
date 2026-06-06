<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\FeedbackController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $tables = \App\Models\Table::all();

    return view('dashboard', compact('tables'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/booking', [BookingController::class, 'index']);
Route::get('/booking/create', [BookingController::class, 'create']);
Route::post('/booking', [BookingController::class, 'store']);
Route::get('/booking/{id}/edit', [BookingController::class, 'edit']);
Route::put('/booking/{id}', [BookingController::class, 'update']);
Route::delete('/booking/{id}', [BookingController::class, 'destroy']);

Route::get('/table', [TableController::class, 'index']);
Route::get('/table/create', [TableController::class, 'create']);
Route::post('/table', [TableController::class, 'store']);
Route::get('/table/status/{id}', [TableController::class, 'updateStatus']);

Route::get('/transaction', [TransactionController::class, 'index']);
Route::get('/transaction/{id}', [TransactionController::class, 'show']);
Route::get('/transaction/approve/{id}', [TransactionController::class, 'approve']);

Route::get('/inventory', [InventoryController::class, 'index']);
Route::post('/inventory', [InventoryController::class, 'store']);
Route::put('/inventory/{id}', [InventoryController::class, 'update']);

Route::get('/feedback', [FeedbackController::class, 'index']);
Route::post('/feedback', [FeedbackController::class, 'store']);

require __DIR__.'/auth.php';