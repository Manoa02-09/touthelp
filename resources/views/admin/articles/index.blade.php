@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Articles</h2>
        <a href="{{ route('admin.articles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Nouvel article</a>
    </div>
    @if(session('success')) <div class="bg-green-100 p-3 rounded mb-4">{{ session('success') }}</div> @endif
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Titre</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Publié</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $a)
                <tr class="border-t">
                    <td class="px-6 py-4">{{ $a->titre }}</td>
                    <td class="px-6 py-4">{{ $a->date_publication->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ $a->publie ? 'Oui' : 'Non' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.articles.edit', $a) }}" class="text-blue-600">Modifier</a>
                        <form action="{{ route('admin.articles.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 ml-3">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection