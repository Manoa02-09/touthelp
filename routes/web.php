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

// Route pour récupérer les messages (AJAX)
Route::get('/api/messages', [ContactController::class, 'getMessages']);

// Routes admin protégées (UN SEUL GROUPE !)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/messages', [ContactController::class, 'adminIndex'])->name('messages');
    Route::post('/messages/{id}/repondre', [ContactController::class, 'repondre'])->name('messages.repondre');
    Route::resource('catalogues', CatalogueController::class);
    Route::resource('formations', FormationController::class);
});

// Routes temporaires pour le profil
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');
    
    Route::patch('/profile', function () {
        return redirect()->back()->with('success', 'Profil mis à jour');
    })->name('profile.update');
    
    Route::delete('/profile', function () {
        return redirect()->back()->with('success', 'Compte supprimé');
    })->name('profile.destroy');
});

// NOUVELLES ROUTES POUR L'API ADMIN
Route::get('/admin/messages/conversation/{email}', [ContactController::class, 'getConversation']);
Route::post('/admin/messages/reply', [ContactController::class, 'replyFromModal']);
Route::post('/admin/messages/toggle-close', [ContactController::class, 'toggleCloseConversation'])->middleware(['auth', 'admin']);
Route::post('/admin/messages/toggle-close', [ContactController::class, 'toggleCloseConversation'])->middleware(['auth', 'admin']);

require __DIR__.'/auth.php';