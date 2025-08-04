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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clients', ClientController::class)->middleware('permission:manage clients');
    Route::resource('services', ServiceController::class)->middleware('permission:manage services');
    Route::resource('orders', OrderController::class)->middleware('permission:manage orders');

    // MODIFICATION ICI : Appliquez le middleware DIRECTEMENT sur la route ressource
    Route::resource('users', UserController::class)
        ->except(['create', 'show', 'store'])
        ->middleware('permission:manage users'); 

    // Nouvelle route pour la gestion des dépenses
    Route::resource('expenses', ExpenseController::class)
        ->only(['index', 'create', 'store', 'destroy']) // on n'utilise que ces 4 méthodes
        ->middleware('permission:manage expenses');
     // Route pour le journal d'activité
    Route::get('/activity-log', [ActivityLogController::class, 'index'])
        ->name('activity-log.index')
        ->middleware('role:admin');;

    // Route pour la gestion du profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';