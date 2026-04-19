@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Ajouter un article</h2>
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block font-bold">Titre</label><input type="text" name="titre" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Date publication</label><input type="date" name="date_publication" class="w-full border rounded p-2" required></div>
            <div><label class="block font-bold">Image à la une</label><input type="file" name="image_une" accept="image/*" class="w-full border rounded p-2"></div>
            <div class="col-span-2"><label class="block font-bold">Extrait (résumé)</label><textarea name="extrait" rows="3" class="w-full border rounded p-2"></textarea></div>
            <div class="col-span-2"><label class="block font-bold">Contenu</label><textarea name="contenu" rows="8" class="w-full border rounded p-2" required></textarea></div>
            <div><label><input type="checkbox" name="publie" value="1"> Publié</label></div>
        </div>
        <div class="mt-6 flex justify-end"><a href="{{ route('admin.articles.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Annuler</a><button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button></div>
    </form>
</div>
@endsection