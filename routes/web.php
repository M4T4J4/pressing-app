<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Utilisez Route::resource pour gérer toutes les routes CRUD pour les services
// Cela remplace Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
// et toutes les autres routes CRUD (create, store, show, edit, update, destroy)
Route::resource('services', ServiceController::class);

// Routes pour les Clients
Route::resource('clients', ClientController::class);
