@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Ajouter un catalogue</h2>

    <form action="{{ route('admin.catalogues.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block font-bold mb-2">Titre</label><input type="text" name="titre" class="w-full border rounded px-3 py-2" required></div>
            <div><label class="block font-bold mb-2">Ordre</label><input type="number" name="ordre" value="0" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block font-bold mb-2">Actif</label><input type="checkbox" name="actif" value="1" checked> Oui</div>
            <div><label class="block font-bold mb-2">Fichier (PDF/DOC/DOCX)</label><input type="file" name="fichier_pdf" accept=".pdf,.doc,.docx" class="w-full border rounded px-3 py-2"></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Description</label><textarea name="description" rows="3" class="w-full border rounded px-3 py-2"></textarea></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Objectifs</label><textarea name="objectifs" rows="3" class="w-full border rounded px-3 py-2"></textarea></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Public visé</label><textarea name="public_vise" rows="3" class="w-full border rounded px-3 py-2"></textarea></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Programme (HTML autorisé)</label><textarea name="programme" rows="8" class="w-full border rounded px-3 py-2"></textarea></div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.catalogues.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
        </div>
    </form>
</div>
@endsection