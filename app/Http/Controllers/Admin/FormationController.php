<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Catalogue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::orderBy('date_debut', 'desc')->get();
        return view('admin.formations.index', compact('formations'));
    }

    public function create()
    {
        $catalogues = Catalogue::orderBy('titre')->get();
        return view('admin.formations.create', compact('catalogues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'catalogue_id' => 'required|exists:catalogues,id',
            'titre' => 'required|string|max:200',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'description_courte' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure' => 'nullable',
            'lieu' => 'required|string|max:255',
            'prix' => 'nullable|numeric',
            'places_max' => 'nullable|integer|min:1',
            'lien_inscription' => 'nullable|url',
            'actif' => 'nullable|boolean',
        ]);

        $formation = Formation::create([
            'catalogue_id' => $request->catalogue_id,
            'titre' => $request->titre,
            'description' => $request->description,
            'description_courte' => $request->description_courte,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'heure' => $request->heure,
            'lieu' => $request->lieu,
            'prix' => $request->prix,
            'places_max' => $request->places_max,
            'lien_inscription' => $request->lien_inscription,
            'actif' => $request->has('actif'),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('formations', 'public');
            $formation->image = $path;
            $formation->save();
        }

        return redirect()->route('admin.formations.index')->with('success', 'Formation créée avec succès.');
    }

    public function edit(Formation $formation)
    {
        $catalogues = Catalogue::orderBy('titre')->get();
        return view('admin.formations.edit', compact('formation', 'catalogues'));
    }

    public function update(Request $request, Formation $formation)
    {
        $request->validate([
            'catalogue_id' => 'required|exists:catalogues,id',
            'titre' => 'required|string|max:200',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'description_courte' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure' => 'nullable',
            'lieu' => 'required|string|max:255',
            'prix' => 'nullable|numeric',
            'places_max' => 'nullable|integer|min:1',
            'lien_inscription' => 'nullable|url',
            'actif' => 'nullable|boolean',
        ]);

        $formation->update([
            'catalogue_id' => $request->catalogue_id,
            'titre' => $request->titre,
            'description' => $request->description,
            'description_courte' => $request->description_courte,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'heure' => $request->heure,
            'lieu' => $request->lieu,
            'prix' => $request->prix,
            'places_max' => $request->places_max,
            'lien_inscription' => $request->lien_inscription,
            'actif' => $request->has('actif'),
        ]);

        if ($request->hasFile('image')) {
            if ($formation->image) Storage::disk('public')->delete($formation->image);
            $path = $request->file('image')->store('formations', 'public');
            $formation->image = $path;
            $formation->save();
        }

        if ($request->has('delete_image')) {
            if ($formation->image) Storage::disk('public')->delete($formation->image);
            $formation->image = null;
            $formation->save();
        }

        return redirect()->route('admin.formations.index')->with('success', 'Formation mise à jour.');
    }

    // ✅ MÉTHODE SHOW AJOUTÉE
    public function show(Formation $formation)
    {
        return redirect()->route('admin.formations.edit', $formation);
    }

    public function destroy(Formation $formation)
    {
        if ($formation->image) Storage::disk('public')->delete($formation->image);
        $formation->delete();
        return redirect()->route('admin.formations.index')->with('success', 'Formation supprimée.');
    }
}