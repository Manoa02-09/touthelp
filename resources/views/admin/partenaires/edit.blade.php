@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Modifier le partenaire</h2>
    <form action="{{ route('admin.partenaires.update', $partenaire) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block font-bold">Nom entreprise</label><input type="text" name="nom_entreprise" value="{{ old('nom_entreprise', $partenaire->nom_entreprise) }}" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Logo actuel</label>@if($partenaire->logo)<img src="{{ asset('storage/'.$partenaire->logo) }}" class="h-16 mb-2">@endif<input type="file" name="logo" accept="image/*" class="w-full border rounded p-2"></div>
            <div><label class="block font-bold">Site web</label><input type="url" name="site_web" value="{{ old('site_web', $partenaire->site_web) }}" class="w-full border rounded p-2"></div>
            <div><label class="block font-bold">Ordre affichage</label><input type="number" name="ordre_affichage" value="{{ old('ordre_affichage', $partenaire->ordre_affichage) }}" class="w-full border rounded p-2"></div>
            <div class="col-span-2"><label class="block font-bold">Description</label><textarea name="description" rows="3" class="w-full border rounded p-2">{{ old('description', $partenaire->description) }}</textarea></div>
            <div><label><input type="checkbox" name="actif" value="1" {{ $partenaire->actif ? 'checked' : '' }}> Actif</label></div>
        </div>
        <div class="mt-6 flex justify-end"><a href="{{ route('admin.partenaires.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Annuler</a><button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Mettre à jour</button></div>
    </form>
</div>
@endsection