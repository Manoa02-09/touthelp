@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Ajouter un avis</h2>
    <form action="{{ route('admin.avis.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block font-bold">Entreprise</label><input type="text" name="entreprise_nom" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Contact (optionnel)</label><input type="text" name="contact_nom" class="w-full border rounded p-2"></div>
            <div><label class="block font-bold">Fonction</label><input type="text" name="contact_fonction" class="w-full border rounded p-2"></div>
            <div><label class="block font-bold">Logo</label><input type="file" name="logo_entreprise" accept="image/*" class="w-full border rounded p-2"></div>
            <div><label class="block font-bold">Note /5</label><input type="number" name="note" min="1" max="5" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Statut</label>
                <select name="statut" class="w-full border rounded p-2">
                    <option value="en_attente">En attente</option>
                    <option value="publie">Publié</option>
                    <option value="rejete">Rejeté</option>
                </select>
            </div>
            <div class="col-span-2"><label class="block font-bold">Avis</label><textarea name="contenu" rows="4" class="w-full border rounded p-2" required></textarea></div>
            <div><label><input type="checkbox" name="mis_en_avant" value="1"> Mettre en avant</label></div>
        </div>
        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.avis.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
        </div>
    </form>
</div>
@endsection