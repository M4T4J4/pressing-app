<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HistoryController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes avec permissions pour les rôles 'employe' et 'admin'
    Route::middleware(['permission:manage clients'])->group(function () {
        Route::resource('clients', ClientController::class);
    });

    Route::middleware(['permission:manage orders'])->group(function () {
        Route::resource('orders', OrderController::class);
    });
    
    // Routes pour la caisse, la route 'caisses.index' est maintenant définie
    Route::middleware(['permission:manage caisse'])->group(function () {
        Route::get('/caisses', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/caisses/pay/{order}', [CheckoutController::class, 'pay'])->name('checkout.pay');
        Route::get('/caisses/{order}', [CheckoutController::class, 'show'])->name('checkout.show');
    });

    // Routes avec permissions pour le rôle 'admin' uniquement
    Route::middleware(['permission:manage services'])->group(function () {
        Route::resource('services', ServiceController::class);
    });

    Route::middleware(['permission:manage users'])->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'show', 'store']);
    });
    
    Route::middleware(['permission:manage expenses'])->group(function () {
        Route::resource('expenses', ExpenseController::class)->only(['index', 'create', 'store', 'destroy']);
    });

    Route::middleware(['permission:purge history'])->group(function () {
        Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
        Route::post('/history/clear', [HistoryController::class, 'clear'])->name('history.clear');
    });

    Route::middleware(['permission:view activity log'])->group(function () {
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    });

    // Routes du profil utilisateur (pas de permission spécifique requise)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';