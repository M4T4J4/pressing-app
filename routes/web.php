<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController; // AJOUTEZ CETTE LIGNE
use App\Http\Controllers\ClientController;  // AJOUTEZ CETTE LIGNE
use App\Http\Controllers\OrderController;   // AJOUTEZ CETTE LIGNE

Route::get('/', function () {
    return view('welcome');
});

// Inclut les routes d'authentification générées par Breeze (login, register, logout, etc.)
require __DIR__.'/auth.php';

// Grouper les routes qui nécessitent une authentification
// Le middleware 'auth' s'assure que l'utilisateur est connecté.
// Le middleware 'verified' (optionnel, si vous l'utilisez) s'assure que l'e-mail de l'utilisateur a été vérifié.
Route::middleware(['auth', 'verified'])->group(function () {
    // Redirige le tableau de bord vers la liste des commandes après connexion
    Route::redirect('/dashboard', '/orders')->name('dashboard');

    // Routes pour les Services
    Route::resource('services', ServiceController::class); // AJOUTEZ CETTE LIGNE

    // Routes pour les Clients
    Route::resource('clients', ClientController::class);   // AJOUTEZ CETTE LIGNE

    // Routes pour les Commandes
    Route::resource('orders', OrderController::class);     // AJOUTEZ CETTE LIGNE
    
    // Routes de profil générées par Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});