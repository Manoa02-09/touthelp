@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Catalogues</h2>
        <a href="{{ route('admin.catalogues.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Nouveau</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Image</th>               <!-- NOUVEAU -->
                    <th class="px-6 py-3">Titre</th>
                    <th class="px-6 py-3">Fichier</th>
                    <th class="px-6 py-3">Actif</th>
                    <th class="px-6 py-3">Ordre</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($catalogues as $catalogue)
                <tr class="border-t">
                    <!-- NOUVEAU : Image miniature -->
                    <td class="px-6 py-4">
                        @if($catalogue->image)
                            <img src="{{ asset('storage/'.$catalogue->image) }}" alt="Image" class="w-12 h-12 object-cover rounded">
                        @else
                            <span class="text-gray-400 text-sm">Aucune</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $catalogue->titre }}</td>
                    <td class="px-6 py-4">
                        @if($catalogue->fichier_pdf)
                            <a href="{{ asset('storage/'.$catalogue->fichier_pdf) }}" target="_blank">Télécharger</a>
                        @else
                            <span class="text-gray-400">Aucun</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $catalogue->actif ? 'Oui' : 'Non' }}</td>
                    <td class="px-6 py-4">{{ $catalogue->ordre }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.catalogues.edit', $catalogue) }}" class="text-blue-600 mr-3">Modifier</a>
                        <form action="{{ route('admin.catalogues.destroy', $catalogue) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection