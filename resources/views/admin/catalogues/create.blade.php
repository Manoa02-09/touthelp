@extends('layouts.admin')

@section('content')
<div style="padding: 20px;">
    <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">Ajouter un catalogue</h2>

    <form action="{{ url('/admin/catalogues') }}" method="POST" enctype="multipart/form-data" style="background-color: white; padding: 20px; border-radius: 8px;">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Titre du catalogue</label>
            <input type="text" name="titre" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Description</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Expertises liées</label>
            @foreach($expertises as $expertise)
                <label style="display: inline-block; margin-right: 15px;">
                    <input type="checkbox" name="expertises[]" value="{{ $expertise->id }}">
                    {{ $expertise->nom }}
                </label>
            @endforeach
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Fichier PDF</label>
            <input type="file" name="fichier_pdf" accept=".pdf" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: inline-flex; align-items: center;">
                <input type="checkbox" name="actif" value="1" checked>
                <span style="margin-left: 8px;">Actif</span>
            </label>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <a href="{{ url('/admin/catalogues') }}" style="background-color: gray; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">Annuler</a>
            <button type="submit" style="background-color: blue; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Enregistrer</button>
        </div>
    </form>
</div>
@endsection