<?php

use App\Http\Controllers\Admin\CatalogueController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard Breeze par défaut
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route pour le formulaire de contact (sans middleware admin)
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// Routes admin protégées
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('catalogues', CatalogueController::class);
    Route::resource('formations', FormationController::class);
});

require __DIR__.'/auth.php';