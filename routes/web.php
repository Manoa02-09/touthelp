<?php

use App\Http\Controllers\Admin\CatalogueController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ContactController;
use App\Models\Catalogue;
use App\Models\Formation;
use App\Models\Article;
use App\Models\Partenaire;
use App\Models\Avis;
use Illuminate\Support\Facades\Route;

// ==================== PAGE D'ACCUEIL ====================
Route::get('/', function () {
    $catalogues = Catalogue::where('actif', true)->orderBy('ordre', 'asc')->get();
    $articles = Article::where('publie', true)->orderBy('date_publication', 'desc')->limit(3)->get();
    $partenaires = Partenaire::where('actif', true)->orderBy('ordre_affichage', 'asc')->get();
    $avis = Avis::where('statut', 'publie')->orderBy('created_at', 'desc')->limit(10)->get();

    return view('welcome', compact('catalogues', 'articles', 'partenaires', 'avis'));
})->name('accueil');

// ==================== CALENDRIER ====================
Route::get('/calendrier', function () {
    $formations = Formation::where('actif', true)
        ->whereDate('date_debut', '>=', now()->toDateString())
        ->orderBy('date_debut', 'asc')
        ->get();
    return view('calendrier', compact('formations'));
})->name('calendrier');

// ==================== API / CONTACT ====================
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
Route::get('/api/messages', [ContactController::class, 'getMessages']);

// ==================== DASHBOARD ====================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== ADMIN ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/messages', [ContactController::class, 'adminIndex'])->name('messages');
    Route::post('/messages/{id}/repondre', [ContactController::class, 'repondre'])->name('messages.repondre');
    Route::resource('catalogues', CatalogueController::class);
    Route::resource('formations', FormationController::class);
});

// ==================== API ADMIN (MESSAGERIE) ====================
Route::get('/admin/messages/conversation/{email}', [ContactController::class, 'getConversation']);
Route::post('/admin/messages/reply', [ContactController::class, 'replyFromModal']);
Route::post('/admin/messages/toggle-close', [ContactController::class, 'toggleCloseConversation'])->middleware(['auth', 'admin']);

// ==================== PROFIL ====================
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

// ==================== API POUR LES CATALOGUES (MODALE) ====================
Route::get('/api/catalogue/{id}', function ($id) {
    $catalogue = App\Models\Catalogue::findOrFail($id);
    return response()->json([
        'id' => $catalogue->id,
        'titre' => $catalogue->titre,
        'image' => $catalogue->image ? asset('storage/' . $catalogue->image) : null,
        'image_url' => $catalogue->image ? asset('storage/' . $catalogue->image) : null,
        'description' => $catalogue->description,
        'objectifs' => $catalogue->objectifs,
        'public_vise' => $catalogue->public_vise,
        'programme' => $catalogue->programme,
        'fichier_pdf' => $catalogue->fichier_pdf,
        'fichier_url' => $catalogue->fichier_pdf ? asset('storage/' . $catalogue->fichier_pdf) : null,
    ]);
});

require __DIR__.'/auth.php';