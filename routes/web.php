<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// Assurez-vous que ces lignes sont bien présentes en haut de votre fichier routes/web.php
use App\Http\Controllers\ClientController; 
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;

// Route d'accueil (peut-être déjà là)
Route::get('/', function () {
    return view('welcome');
});

// Groupe de routes nécessitant une authentification et/ou vérification d'email
Route::middleware(['auth', 'verified'])->group(function () {
    // Redirige /dashboard vers /orders (ou votre page d'accueil principale)
    // C'est une bonne pratique pour votre application de pressing
    Route::redirect('/dashboard', '/orders')->name('dashboard');

    // Définition des routes de ressources avec leurs middlewares de permission
    // Le middleware 'permission:...' est appliqué ici directement sur la ressource
    Route::resource('clients', ClientController::class)->middleware('permission:manage clients');
    Route::resource('services', ServiceController::class)->middleware('permission:manage services');
    Route::resource('orders', OrderController::class)->middleware('permission:manage orders');

    // Vos routes de profil existantes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// N'oubliez pas d'inclure les routes d'authentification de Breeze
require __DIR__.'/auth.php';