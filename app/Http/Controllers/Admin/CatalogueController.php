<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\Expertise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatalogueController extends Controller
{
    /**
     * Afficher la liste des catalogues.
     */
    public function index()
    {
        $catalogues = Catalogue::with('expertises')->get();
        return view('admin.catalogues.index', compact('catalogues'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        $expertises = Expertise::all();
        return view('admin.catalogues.create', compact('expertises'));
    }

    /**
     * Enregistrer un nouveau catalogue.
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'fichier_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'expertises' => 'nullable|array',
            'expertises.*' => 'exists:expertises,id',
            'actif' => 'nullable|boolean',
        ]);

        // Création du catalogue
        $catalogue = Catalogue::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'actif' => $request->has('actif') ? true : false,
            'ordre' => 0,
        ]);

        // Gestion du fichier PDF
        if ($request->hasFile('fichier_pdf')) {
            $path = $request->file('fichier_pdf')->store('catalogues', 'public');
            $catalogue->update(['fichier_pdf' => $path]);
        }

        // Attacher les expertises
        if ($request->has('expertises')) {
            $catalogue->expertises()->attach($request->expertises);
        }

        // Redirection avec message de succès
        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue créé avec succès.');
    }

    /**
     * Afficher un catalogue spécifique.
     */
    public function show(Catalogue $catalogue)
    {
        return view('admin.catalogues.show', compact('catalogue'));
    }

    /**
     * Afficher le formulaire d'édition.
     */
    public function edit(Catalogue $catalogue)
    {
        $expertises = Expertise::all();
        return view('admin.catalogues.edit', compact('catalogue', 'expertises'));
    }

    /**
     * Mettre à jour un catalogue.
     */
    public function update(Request $request, Catalogue $catalogue)
    {
        // Validation des données
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'fichier_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'expertises' => 'nullable|array',
            'expertises.*' => 'exists:expertises,id',
            'actif' => 'nullable|boolean',
        ]);

        // Mise à jour du catalogue
        $catalogue->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'actif' => $request->has('actif') ? true : false,
        ]);

        // Gestion du fichier PDF
        if ($request->hasFile('fichier_pdf')) {
            // Supprimer l'ancien fichier
            if ($catalogue->fichier_pdf && Storage::disk('public')->exists($catalogue->fichier_pdf)) {
                Storage::disk('public')->delete($catalogue->fichier_pdf);
            }
            $path = $request->file('fichier_pdf')->store('catalogues', 'public');
            $catalogue->update(['fichier_pdf' => $path]);
        }

        // Synchroniser les expertises
        $catalogue->expertises()->sync($request->expertises ?? []);

        // Redirection avec message de succès
        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue mis à jour avec succès.');
    }

    /**
     * Supprimer un catalogue.
     */
    public function destroy(Catalogue $catalogue)
    {
        // Supprimer le fichier PDF associé
        if ($catalogue->fichier_pdf && Storage::disk('public')->exists($catalogue->fichier_pdf)) {
            Storage::disk('public')->delete($catalogue->fichier_pdf);
        }

        // Supprimer les relations avec les expertises
        $catalogue->expertises()->detach();

        // Supprimer le catalogue
        $catalogue->delete();

        // Redirection avec message de succès
        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue supprimé avec succès.');
    }
}