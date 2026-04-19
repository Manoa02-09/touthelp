<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
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
        return view('admin.formations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
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
        return view('admin.formations.edit', compact('formation'));
    }

    public function update(Request $request, Formation $formation)
    {
        $request->validate([
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

        return redirect()->route('admin.formations.index')->with('success', 'Formation mise à jour.');
    }

    public function destroy(Formation $formation)
    {
        if ($formation->image) Storage::disk('public')->delete($formation->image);
        $formation->delete();
        return redirect()->route('admin.formations.index')->with('success', 'Formation supprimée.');
    }
}