@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Modifier le partenaire</h2>

    <form action="{{ route('admin.partenaires.update', $partenaire) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-2">Nom de l'entreprise</label>
                <input type="text" name="nom_entreprise" value="{{ old('nom_entreprise', $partenaire->nom_entreprise) }}" class="w-full border rounded px-3 py-2" required>
                @error('nom_entreprise') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-bold mb-2">Logo actuel</label>
                @if($partenaire->logo)
                    <img src="{{ asset('storage/'.$partenaire->logo) }}" class="h-16 mb-2">
                @else
                    <p class="text-gray-500 text-sm mb-2">Aucun logo</p>
                @endif
                <input type="file" name="logo" accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">Laissez vide pour conserver l'image actuelle.</p>
                @error('logo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-bold mb-2">Site web</label>
                <input type="url" name="site_web" value="{{ old('site_web', $partenaire->site_web) }}" class="w-full border rounded px-3 py-2" placeholder="https://...">
                @error('site_web') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-bold mb-2">Ordre d'affichage</label>
                <input type="number" name="ordre_affichage" value="{{ old('ordre_affichage', $partenaire->ordre_affichage) }}" class="w-full border rounded px-3 py-2">
                @error('ordre_affichage') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <label class="block font-bold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $partenaire->description) }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="actif" value="1" {{ old('actif', $partenaire->actif) ? 'checked' : '' }} class="mr-2">
                    <span class="text-gray-700">Actif (visible sur le site)</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.partenaires.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 mr-2">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection