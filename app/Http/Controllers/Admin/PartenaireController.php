<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartenaireController extends Controller
{
    public function index()
    {
        $partenaires = Partenaire::orderBy('ordre_affichage')->get();
        return view('admin.partenaires.index', compact('partenaires'));
    }

    public function create()
    {
        return view('admin.partenaires.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_entreprise' => 'required|string|max:150',
            'logo' => 'nullable|image|max:2048',
            'site_web' => 'nullable|url',
            'description' => 'nullable|string',
            'ordre_affichage' => 'nullable|integer',
            'actif' => 'nullable|boolean',
        ]);

        $data = $request->only(['nom_entreprise', 'site_web', 'description', 'ordre_affichage']);
        $data['actif'] = $request->has('actif');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('partenaires', 'public');
            $data['logo'] = $path;
        }

        Partenaire::create($data);

        return redirect()->route('admin.partenaires.index')->with('success', 'Partenaire ajouté.');
    }

    public function edit(Partenaire $partenaire)
    {
        return view('admin.partenaires.edit', compact('partenaire'));
    }

    public function update(Request $request, Partenaire $partenaire)
    {
        $request->validate([
            'nom_entreprise' => 'required|string|max:150',
            'logo' => 'nullable|image|max:2048',
            'site_web' => 'nullable|url',
            'description' => 'nullable|string',
            'ordre_affichage' => 'nullable|integer',
            'actif' => 'nullable|boolean',
        ]);

        $data = $request->only(['nom_entreprise', 'site_web', 'description', 'ordre_affichage']);
        $data['actif'] = $request->has('actif');

        if ($request->hasFile('logo')) {
            if ($partenaire->logo) Storage::disk('public')->delete($partenaire->logo);
            $path = $request->file('logo')->store('partenaires', 'public');
            $data['logo'] = $path;
        }

        $partenaire->update($data);

        return redirect()->route('admin.partenaires.index')->with('success', 'Partenaire modifié.');
    }

    public function destroy(Partenaire $partenaire)
    {
        if ($partenaire->logo) Storage::disk('public')->delete($partenaire->logo);
        $partenaire->delete();
        return redirect()->route('admin.partenaires.index')->with('success', 'Partenaire supprimé.');
    }
}