<?php

use App\Http\Controllers\Admin\CatalogueController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AvisController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\PartenaireController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AvisClientController;
use App\Models\Catalogue;
use App\Models\Formation;
use App\Models\Article;
use App\Models\Partenaire;
use App\Models\Avis;
use Illuminate\Support\Facades\Route;

// ==================== PAGE D'ACCUEIL ====================
Route::get('/', function () {
    $catalogues = Catalogue::where('actif', true)->orderBy('ordre', 'asc')->get();
    $articles = Article::where('publie', true)->orderBy('date_publication', 'desc')->get();
    $partenaires = Partenaire::where('actif', true)->orderBy('ordre_affichage', 'asc')->get();
    $avis = Avis::where('statut', 'publie')->orderBy('created_at', 'desc')->limit(10)->get();

    return view('welcome', compact('catalogues', 'articles', 'partenaires', 'avis'));
})->name('accueil');

// ==================== FORMULAIRE PUBLIC D'AVIS ====================
Route::get('/avis', [AvisClientController::class, 'create'])->name('avis.create');
Route::post('/avis', [AvisClientController::class, 'store'])->name('avis.store');

// ==================== DÉTAIL D'UN ARTICLE (BLOG) ====================
Route::get('/article/{slug}', function ($slug) {
    $article = Article::where('slug', $slug)->where('publie', true)->firstOrFail();
    return view('article.show', compact('article'));
})->name('blog.show');

// ==================== DÉTAIL D'UN CATALOGUE (PAGE DÉDIÉE) ====================
Route::get('/catalogue/{id}', function ($id) {
    $catalogue = Catalogue::findOrFail($id);
    return view('catalogue.show', compact('catalogue'));
})->name('catalogue.show');

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
    return redirect('/admin');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== ADMIN ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/messages', [ContactController::class, 'adminIndex'])->name('messages');
    Route::post('/messages/{id}/repondre', [ContactController::class, 'repondre'])->name('messages.repondre');
    Route::resource('catalogues', CatalogueController::class);
    Route::resource('formations', FormationController::class);
    Route::resource('avis', AvisController::class);
    Route::patch('/avis/{avi}/accept', [AvisController::class, 'accept'])->name('avis.accept');
    Route::resource('articles', ArticleController::class);
    Route::resource('partenaires', PartenaireController::class);

    // ========== PARAMÈTRES (Settings) ==========
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.update.general');
    Route::post('/settings/security', [SettingsController::class, 'updateSecurity'])->name('settings.update.security');
    Route::post('/settings/social', [SettingsController::class, 'updateSocial'])->name('settings.update.social');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update.profile');
    Route::delete('/settings/account', [SettingsController::class, 'destroyAccount'])->name('settings.account.destroy');
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

// ==================== API POUR LES CATALOGUES (MODALE – gardée pour compatibilité) ====================
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

// ==================== PAGE ADMIN DISCU ====================
Route::get('/admin/discu', [App\Http\Controllers\ContactController::class, 'adminIndex'])->name('admin.discu');

// ==================== PAGE À PROPOS ====================
Route::get('/apropos', function () {
    return view('apropos');
})->name('apropos');

// ==================== PAGES EXPERTISES DÉDIÉES ====================
Route::get('/expertise/inter-entreprises', function () {
    return view('expertises.inter');
})->name('expertise.inter');

Route::get('/expertise/intra-entreprise', function () {
    return view('expertises.intra');
})->name('expertise.intra');

Route::get('/expertise/accompagnement-audit', function () {
    return view('expertises.accompagnement');
})->name('expertise.accompagnement');

// ==================== API RECHERCHE GLOBALE ====================
Route::get('/api/admin/search', [SearchController::class, 'api'])->name('api.admin.search');

require __DIR__.'/auth.php';