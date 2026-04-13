@extends('layouts.admin')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-size: 24px; font-weight: bold;">Gestion des catalogues</h2>
        <a href="{{ url('/admin/catalogues/create') }}" style="background-color: blue; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px;">
            + Nouveau catalogue
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: lightgreen; padding: 10px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; background-color: white; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f3f4f6;">
                <th style="padding: 12px; text-align: left;">Titre</th>
                <th style="padding: 12px; text-align: left;">Expertises</th>
                <th style="padding: 12px; text-align: left;">Fichier</th>
                <th style="padding: 12px; text-align: left;">Actif</th>
                <th style="padding: 12px; text-align: left;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($catalogues as $catalogue)
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 12px;">{{ $catalogue->titre }}</td>
                <td style="padding: 12px;">
                    @foreach($catalogue->expertises as $expertise)
                        <span style="background-color: #e5e7eb; padding: 2px 8px; border-radius: 12px; font-size: 12px; margin-right: 5px;">
                            {{ $expertise->nom }}
                        </span>
                    @endforeach
                </td>
                <td style="padding: 12px;">
                    @if($catalogue->fichier_pdf)
                        <a href="{{ asset('storage/' . $catalogue->fichier_pdf) }}" target="_blank" style="color: blue;">Voir PDF</a>
                    @else
                        <span style="color: gray;">Aucun</span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    @if($catalogue->actif)
                        <span style="background-color: lightgreen; padding: 2px 8px; border-radius: 12px;">Actif</span>
                    @else
                        <span style="background-color: lightcoral; padding: 2px 8px; border-radius: 12px;">Inactif</span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    <a href="{{ url('/admin/catalogues/' . $catalogue->id . '/edit') }}" style="color: blue; margin-right: 10px;">Modifier</a>
                    <form action="{{ url('/admin/catalogues/' . $catalogue->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color: red;" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection