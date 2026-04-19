<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\Request;

class AvisClientController extends Controller
{
    public function create()
    {
        return view('site.avis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'entreprise_nom' => 'required|string|max:150',
            'contact_nom'    => 'nullable|string|max:150',
            'contact_fonction'=> 'nullable|string|max:100',
            'note'           => 'required|integer|min:1|max:5',
            'contenu'        => 'required|string|min:10|max:2000',
        ]);

        Avis::create([
            'entreprise_nom'   => $request->entreprise_nom,
            'contact_nom'      => $request->contact_nom,
            'contact_fonction' => $request->contact_fonction,
            'note'             => $request->note,
            'contenu'          => $request->contenu,
            'statut'           => 'en_attente',
            'date_soumission'  => now(),
        ]);

        return redirect()->route('avis.create')
            ->with('success', 'Merci pour votre avis ! Il sera publié après validation.');
    }
}