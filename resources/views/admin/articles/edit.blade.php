@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Modifier l'article</h2>

    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-2">Titre</label>
                <input type="text" name="titre" value="{{ old('titre', $article->titre) }}" class="w-full border rounded px-3 py-2" required>
                @error('titre') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-bold mb-2">Date de publication</label>
                <input type="date" name="date_publication" value="{{ old('date_publication', $article->date_publication ? $article->date_publication->format('Y-m-d') : '') }}" class="w-full border rounded px-3 py-2" required>
                @error('date_publication') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-bold mb-2">Image à la une</label>
                @if($article->image_une)
                    <img src="{{ asset('storage/'.$article->image_une) }}" class="h-24 mb-2">
                @endif
                <input type="file" name="image_une" accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">Laissez vide pour conserver l'image actuelle.</p>
                @error('image_une') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <label class="block font-bold mb-2">Extrait (résumé)</label>
                <textarea name="extrait" rows="3" class="w-full border rounded px-3 py-2">{{ old('extrait', $article->extrait) }}</textarea>
                @error('extrait') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <label class="block font-bold mb-2">Contenu complet</label>
                <textarea name="contenu" rows="10" class="w-full border rounded px-3 py-2" required>{{ old('contenu', $article->contenu) }}</textarea>
                @error('contenu') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="publie" value="1" {{ old('publie', $article->publie) ? 'checked' : '' }} class="mr-2">
                    <span class="text-gray-700">Publié (visible sur le site)</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.articles.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 mr-2">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection