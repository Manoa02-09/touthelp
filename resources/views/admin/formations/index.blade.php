@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Gestion des formations (calendrier)</h2>
        <a href="{{ route('admin.formations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Nouvelle formation
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date début</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lieu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actif</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formations as $formation)
                <tr>
                    <td class="px-6 py-4">{{ $formation->titre }}</td>
                    <td class="px-6 py-4">{{ $formation->date_debut ? $formation->date_debut->format('d/m/Y') : '-' }}</td>
                    <td class="px-6 py-4">{{ $formation->lieu }}</td>
                    <td class="px-6 py-4">{{ $formation->actif ? 'Oui' : 'Non' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.formations.edit', $formation) }}" class="text-blue-600 mr-3">Modifier</a>
                        <form action="{{ route('admin.formations.destroy', $formation) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette formation ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Aucune formation pour le moment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection