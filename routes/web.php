<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OfflineTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingLabelController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // KHUSUS ADMIN (Master Data & Transaksi)
    // ==========================================
    Route::middleware('admin')->group(function () {

        // Products
        Route::resource('products', ProductController::class)->except(['show', 'destroy']);
        Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
            ->name('products.toggle-status');

        // Shipping Labels — URUTAN PENTING:
        // Route /preview dan /confirm harus didaftarkan SEBELUM resource
        // supaya tidak tertangkap oleh route {shippingLabel} (wildcard)
        Route::get('shipping-labels/preview', [ShippingLabelController::class, 'showPreview'])
            ->name('shipping-labels.preview');
        Route::post('shipping-labels/confirm', [ShippingLabelController::class, 'confirmStore'])
            ->name('shipping-labels.confirm');
        Route::resource('shipping-labels', ShippingLabelController::class)->except(['show']);

        // Offline transactions & stock entries
        Route::resource('offline-transactions', OfflineTransactionController::class)->except(['show']);
        Route::resource('stock-entries', StockEntryController::class)->except(['show']);

        // Riwayat transaksi (admin only)
        Route::get('/laporan/riwayat', [LaporanController::class, 'riwayat'])->name('laporan.riwayat');
    });

    // ==========================================
    // BISA DIAKSES OLEH ADMIN & PEMILIK
    // ==========================================
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');

    // ==========================================
    // KHUSUS PEMILIK (Manajemen User)
    // ==========================================
    Route::middleware('pemilik')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});