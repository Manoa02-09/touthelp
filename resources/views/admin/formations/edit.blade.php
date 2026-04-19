@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Modifier la formation</h2>

    <form action="{{ route('admin.formations.update', $formation) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-2">Titre *</label>
                <input type="text" name="titre" value="{{ old('titre', $formation->titre) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-bold mb-2">Image actuelle</label>
                @if($formation->image)
                    <img src="{{ asset('storage/'.$formation->image) }}" class="h-20 mb-2">
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500">Laissez vide pour conserver l'image actuelle.</p>
            </div>
            <div>
                <label class="block font-bold mb-2">Date début *</label>
                <input type="date" name="date_debut" value="{{ old('date_debut', $formation->date_debut ? $formation->date_debut->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-bold mb-2">Date fin</label>
                <input type="date" name="date_fin" value="{{ old('date_fin', $formation->date_fin ? $formation->date_fin->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-bold mb-2">Heure</label>
                <input type="time" name="heure" value="{{ old('heure', $formation->heure) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-bold mb-2">Lieu *</label>
                <input type="text" name="lieu" value="{{ old('lieu', $formation->lieu) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-bold mb-2">Prix</label>
                <input type="number" name="prix" value="{{ old('prix', $formation->prix) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-bold mb-2">Places max</label>
                <input type="number" name="places_max" value="{{ old('places_max', $formation->places_max) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-bold mb-2">Lien inscription externe</label>
                <input type="url" name="lien_inscription" value="{{ old('lien_inscription', $formation->lien_inscription) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="actif" value="1" {{ $formation->actif ? 'checked' : '' }} class="mr-2">
                <label>Actif</label>
            </div>
            <div class="col-span-2">
                <label class="block font-bold mb-2">Description courte</label>
                <textarea name="description_courte" rows="3" class="w-full border rounded px-3 py-2">{{ old('description_courte', $formation->description_courte) }}</textarea>
            </div>
            <div class="col-span-2">
                <label class="block font-bold mb-2">Description détaillée</label>
                <textarea name="description" rows="5" class="w-full border rounded px-3 py-2">{{ old('description', $formation->description) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.formations.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection