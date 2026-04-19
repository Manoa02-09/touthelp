@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Avis clients</h2>
        <a href="{{ route('admin.avis.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Nouvel avis</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Entreprise</th>
                    <th class="px-6 py-3 text-left">Note</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($avis as $a)
                <tr class="border-t">
                    <td class="px-6 py-4 max-w-xs truncate">{{ Str::limit($a->entreprise_nom, 40) }}</td>
                    <td class="px-6 py-4">{{ $a->note }}/5</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded 
                            {{ $a->statut == 'publie' ? 'bg-green-100 text-green-800' : ($a->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $a->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.avis.edit', $a) }}" class="text-blue-600 mr-3">Modifier</a>
                        @if($a->statut == 'en_attente')
                            <form action="{{ route('admin.avis.accept', $a) }}" method="POST" class="inline" onsubmit="return confirm('Accepter cet avis ?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-green-600 mr-3">Accepter</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.avis.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet avis ?')">
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