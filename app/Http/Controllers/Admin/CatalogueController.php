<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatalogueController extends Controller
{
    public function index()
    {
        $catalogues = Catalogue::orderBy('ordre')->get();
        return view('admin.catalogues.index', compact('catalogues'));
    }

    public function create()
    {
        return view('admin.catalogues.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'objectifs' => 'nullable|string',
            'public_vise' => 'nullable|string',
            'programme' => 'nullable|string',
            'fichier_pdf' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'actif' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);

        $catalogue = Catalogue::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'objectifs' => $request->objectifs,
            'public_vise' => $request->public_vise,
            'programme' => $request->programme,
            'actif' => $request->has('actif'),
            'ordre' => $request->ordre ?? 0,
        ]);

        if ($request->hasFile('fichier_pdf')) {
            $path = $request->file('fichier_pdf')->store('catalogues', 'public');
            $catalogue->fichier_pdf = $path;
            $catalogue->save();
        }

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue ajouté.');
    }

    public function edit(Catalogue $catalogue)
    {
        return view('admin.catalogues.edit', compact('catalogue'));
    }

    public function update(Request $request, Catalogue $catalogue)
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'objectifs' => 'nullable|string',
            'public_vise' => 'nullable|string',
            'programme' => 'nullable|string',
            'fichier_pdf' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'actif' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);

        $catalogue->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'objectifs' => $request->objectifs,
            'public_vise' => $request->public_vise,
            'programme' => $request->programme,
            'actif' => $request->has('actif'),
            'ordre' => $request->ordre ?? 0,
        ]);

        if ($request->hasFile('fichier_pdf')) {
            if ($catalogue->fichier_pdf) Storage::disk('public')->delete($catalogue->fichier_pdf);
            $path = $request->file('fichier_pdf')->store('catalogues', 'public');
            $catalogue->fichier_pdf = $path;
            $catalogue->save();
        }

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue mis à jour.');
    }

    // ✅ AJOUTE CETTE MÉTHODE
    public function show(Catalogue $catalogue)
    {
        // Soit tu rediriges vers l'édition (solution simple)
        return redirect()->route('admin.catalogues.edit', $catalogue);
        
        // Ou si tu veux une vraie page show, crée la vue :
        // return view('admin.catalogues.show', compact('catalogue'));
    }

    public function destroy(Catalogue $catalogue)
    {
        if ($catalogue->fichier_pdf) Storage::disk('public')->delete($catalogue->fichier_pdf);
        $catalogue->delete();
        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue supprimé.');
    }
}