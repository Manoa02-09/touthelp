@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Ajouter une formation</h2>

    <form action="{{ route('admin.formations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-2">Titre *</label>
                <input type="text" name="titre" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-bold mb-2">Image (optionnel)</label>
                <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-bold mb-2">Date début *</label>
                <input type="date" name="date_debut" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-bold mb-2">Date fin (optionnel)</label>
                <input type="date" name="date_fin" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-bold mb-2">Heure</label>
                <input type="time" name="heure" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-bold mb-2">Lieu *</label>
                <input type="text" name="lieu" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-bold mb-2">Prix (Ar)</label>
                <input type="number" name="prix" class="w-full border rounded px-3 py-2" step="1000">
            </div>
            <div>
                <label class="block font-bold mb-2">Places max</label>
                <input type="number" name="places_max" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-bold mb-2">Lien inscription externe</label>
                <input type="url" name="lien_inscription" class="w-full border rounded px-3 py-2" placeholder="https://...">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="actif" value="1" checked class="mr-2">
                <label>Actif</label>
            </div>
            <div class="col-span-2">
                <label class="block font-bold mb-2">Description courte (cartes)</label>
                <textarea name="description_courte" rows="3" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="col-span-2">
                <label class="block font-bold mb-2">Description détaillée</label>
                <textarea name="description" rows="5" class="w-full border rounded px-3 py-2"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.formations.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
        </div>
    </form>
</div>
@endsection