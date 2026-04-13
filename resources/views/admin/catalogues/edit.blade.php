@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Modifier le catalogue</h2>

    <form action="{{ route('admin.catalogues.update', $catalogue) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Titre du catalogue</label>
            <input type="text" name="titre" value="{{ old('titre', $catalogue->titre) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
            @error('titre') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea name="description" rows="4" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('description', $catalogue->description) }}</textarea>
            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Expertises liées</label>
            <div class="space-y-2">
                @foreach($expertises as $expertise)
                    <label class="inline-flex items-center mr-4">
                        <input type="checkbox" name="expertises[]" value="{{ $expertise->id }}" 
                            {{ $catalogue->expertises->contains($expertise->id) ? 'checked' : '' }} class="mr-2">
                        {{ $expertise->nom }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Fichier PDF actuel</label>
            @if($catalogue->fichier_pdf)
                <p class="text-sm text-gray-600 mb-2">
                    Fichier : <a href="{{ asset('storage/' . $catalogue->fichier_pdf) }}" target="_blank" class="text-blue-600">Voir le PDF</a>
                </p>
            @else
                <p class="text-sm text-gray-500 mb-2">Aucun fichier</p>
            @endif
            <input type="file" name="fichier_pdf" accept=".pdf" class="w-full">
            <p class="text-sm text-gray-500 mt-1">Laissez vide pour conserver le fichier actuel.</p>
            @error('fichier_pdf') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="actif" value="1" {{ $catalogue->actif ? 'checked' : '' }} class="mr-2">
                <span class="text-gray-700">Actif</span>
            </label>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.catalogues.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 mr-2">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection