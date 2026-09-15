<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LabelSettingController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\HelpController;
use Illuminate\Support\Facades\Route;

// Public Guest Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Legal Pages (Public)
Route::get('/terms', [LegalController::class, 'terms'])->name('terms');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('privacy');

// Main Entry Point & Operations (Accessible to Guests & Authenticated Users)
Route::match(['get', 'head'], '/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/preview-csv', [DashboardController::class, 'previewCsv'])->name('dashboard.preview-csv');
Route::post('/dashboard/store-batch', [DashboardController::class, 'storeBatch'])->name('dashboard.store-batch');
Route::get('/dashboard/print-guest', [DashboardController::class, 'printJobGuest'])->name('dashboard.print-guest');

// Dedicated Settings Page (Accessible to Guests & Auth)
Route::get('/settings', [LabelSettingController::class, 'edit'])->name('settings.edit');
Route::post('/settings', [LabelSettingController::class, 'update'])->name('settings.update');

// Dedicated Help / Documentation Page (Public)
Route::get('/help', [HelpController::class, 'index'])->name('help.index');

// Authenticated Only Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/print/{printJob}', [DashboardController::class, 'printJob'])->name('dashboard.print');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');

    // History Routes
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/export-csv', [HistoryController::class, 'exportCsv'])->name('history.export-csv');
    Route::post('/history/delete-batch', [HistoryController::class, 'destroyBatch'])->name('history.delete-batch');
    Route::get('/history/{printJob}', [HistoryController::class, 'show'])->name('history.show');
    Route::delete('/history/{printJob}', [HistoryController::class, 'destroy'])->name('history.destroy');
});
