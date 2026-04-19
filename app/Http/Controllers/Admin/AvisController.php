<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvisController extends Controller
{
    public function index()
    {
        $avis = Avis::orderBy('created_at', 'desc')->get();
        return view('admin.avis.index', compact('avis'));
    }

    public function create()
    {
        return view('admin.avis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'entreprise_nom' => 'required|string|max:150',
            'contact_nom' => 'nullable|string|max:150',
            'contact_fonction' => 'nullable|string|max:100',
            'logo_entreprise' => 'nullable|image|max:2048',
            'note' => 'required|integer|min:1|max:5',
            'contenu' => 'required|string|min:10',
            'statut' => 'required|in:en_attente,publie,rejete',
            'mis_en_avant' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'entreprise_nom', 'contact_nom', 'contact_fonction',
            'note', 'contenu', 'statut'
        ]);
        $data['mis_en_avant'] = $request->has('mis_en_avant');
        $data['date_soumission'] = now();
        if ($request->statut == 'publie') {
            $data['date_publication'] = now();
        }
        if ($request->hasFile('logo_entreprise')) {
            $path = $request->file('logo_entreprise')->store('avis', 'public');
            $data['logo_entreprise'] = $path;
        }

        Avis::create($data);
        return redirect()->route('admin.avis.index')->with('success', 'Avis ajouté.');
    }

    public function edit(Avis $avi)
    {
        return view('admin.avis.edit', compact('avi'));
    }

    public function accept(Avis $avi)
    {
        $avi->update([
            'statut' => 'publie',
            'date_publication' => now(),
        ]);

        return redirect()->route('admin.avis.index')
            ->with('success', 'Avis publié avec succès.');
    }

    public function update(Request $request, Avis $avi)
    {
        $request->validate([
            'entreprise_nom' => 'required|string|max:150',
            'contact_nom' => 'nullable|string|max:150',
            'contact_fonction' => 'nullable|string|max:100',
            'logo_entreprise' => 'nullable|image|max:2048',
            'note' => 'required|integer|min:1|max:5',
            'contenu' => 'required|string|min:10',
            'statut' => 'required|in:en_attente,publie,rejete',
            'mis_en_avant' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'entreprise_nom', 'contact_nom', 'contact_fonction',
            'note', 'contenu', 'statut'
        ]);
        $data['mis_en_avant'] = $request->has('mis_en_avant');
        if ($request->statut == 'publie' && !$avi->date_publication) {
            $data['date_publication'] = now();
        }
        if ($request->hasFile('logo_entreprise')) {
            if ($avi->logo_entreprise) Storage::disk('public')->delete($avi->logo_entreprise);
            $path = $request->file('logo_entreprise')->store('avis', 'public');
            $data['logo_entreprise'] = $path;
        }

        $avi->update($data);
        return redirect()->route('admin.avis.index')->with('success', 'Avis modifié.');
    }

    public function destroy(Avis $avi)
    {
        if ($avi->logo_entreprise) Storage::disk('public')->delete($avi->logo_entreprise);
        $avi->delete();
        return redirect()->route('admin.avis.index')->with('success', 'Avis supprimé.');
    }
}