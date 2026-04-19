@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Partenaires</h2>
        <a href="{{ route('admin.partenaires.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Nouveau partenaire</a>
    </div>
    @if(session('success'))<div class="bg-green-100 p-3 rounded mb-4">{{ session('success') }}</div>@endif
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Nom</th>
                    <th class="px-6 py-3">Logo</th>
                    <th class="px-6 py-3">Site web</th>
                    <th class="px-6 py-3">Ordre</th>
                    <th class="px-6 py-3">Actif</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partenaires as $p)
                <tr class="border-t">
                    <td class="px-6 py-4">{{ $p->nom_entreprise }}</td>
                    <td class="px-6 py-4">@if($p->logo)<img src="{{ asset('storage/'.$p->logo) }}" class="h-10">@endif</td>
                    <td class="px-6 py-4">@if($p->site_web)<a href="{{ $p->site_web }}" target="_blank">Lien</a>@endif</td>
                    <td class="px-6 py-4">{{ $p->ordre_affichage }}</td>
                    <td class="px-6 py-4">{{ $p->actif ? 'Oui' : 'Non' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.partenaires.edit', $p) }}" class="text-blue-600">Modifier</a>
                        <form action="{{ route('admin.partenaires.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
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