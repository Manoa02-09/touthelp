@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Modifier le catalogue</h2>

    <form action="{{ route('admin.catalogues.update', $catalogue) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block font-bold mb-2">Titre</label><input type="text" name="titre" value="{{ old('titre', $catalogue->titre) }}" class="w-full border rounded px-3 py-2" required></div>
            <div><label class="block font-bold mb-2">Ordre</label><input type="number" name="ordre" value="{{ old('ordre', $catalogue->ordre) }}" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block font-bold mb-2">Actif</label><input type="checkbox" name="actif" value="1" {{ $catalogue->actif ? 'checked' : '' }}> Oui</div>

            <!-- NOUVEAU : Champ Image avec aperçu -->
            <div>
                <label class="block font-bold mb-2">Image actuelle</label>
                @if($catalogue->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$catalogue->image) }}" alt="Image" class="w-32 h-32 object-cover rounded border">
                    </div>
                    <p class="text-sm text-gray-500 mb-2">Laissez vide pour conserver cette image.</p>
                @else
                    <p class="text-gray-500 mb-2">Aucune image actuellement.</p>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">Nouvelle image PNG, JPG, JPEG (remplacera l’ancienne).</p>
            </div>

            <div>
                <label class="block font-bold mb-2">Fichier actuel</label>
                @if($catalogue->fichier_pdf)
                    <p><a href="{{ asset('storage/'.$catalogue->fichier_pdf) }}" target="_blank" class="text-blue-600">Voir le fichier</a></p>
                @else
                    <p class="text-gray-500">Aucun fichier</p>
                @endif
                <input type="file" name="fichier_pdf" accept=".pdf,.doc,.docx" class="w-full border rounded px-3 py-2 mt-2">
                <p class="text-sm text-gray-500">Laissez vide pour conserver le fichier actuel.</p>
            </div>

            <div class="col-span-2"><label class="block font-bold mb-2">Description</label><textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $catalogue->description) }}</textarea></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Objectifs</label><textarea name="objectifs" rows="3" class="w-full border rounded px-3 py-2">{{ old('objectifs', $catalogue->objectifs) }}</textarea></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Public visé</label><textarea name="public_vise" rows="3" class="w-full border rounded px-3 py-2">{{ old('public_vise', $catalogue->public_vise) }}</textarea></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Programme (HTML)</label><textarea name="programme" rows="8" class="w-full border rounded px-3 py-2">{{ old('programme', $catalogue->programme) }}</textarea></div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.catalogues.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection