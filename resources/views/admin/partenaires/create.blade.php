@extends('layouts.admin')
@section('content')
<div class="p-6"><h2 class="text-2xl font-bold mb-6">Ajouter un partenaire</h2>
<form action="{{ route('admin.partenaires.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label>Nom entreprise</label><input type="text" name="nom_entreprise" class="w-full border rounded p-2" required></div>
        <div><label>Logo</label><input type="file" name="logo" accept="image/*" class="w-full border rounded p-2"></div>
        <div><label>Site web</label><input type="url" name="site_web" class="w-full border rounded p-2" placeholder="https://"></div>
        <div><label>Ordre affichage</label><input type="number" name="ordre_affichage" value="0" class="w-full border rounded p-2"></div>
        <div class="col-span-2"><label>Description</label><textarea name="description" rows="3" class="w-full border rounded p-2"></textarea></div>
        <div><label><input type="checkbox" name="actif" value="1" checked> Actif</label></div>
    </div>
    <div class="mt-6 flex justify-end"><a href="{{ route('admin.partenaires.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Annuler</a><button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button></div>
</form></div>
@endsection