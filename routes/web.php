<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

// Route pour afficher le formulaire d'ajout d'un nouveau service
Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');

// Route pour soumettre le formulaire et sauvegarder le nouveau service
Route::post('/services', [ServiceController::class, 'store'])->name('services.store');