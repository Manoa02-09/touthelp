@extends('layouts.app')

@section('title', 'Donnez votre avis - Tout Help')

@section('content')
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 max-w-2xl">
        <h1 class="text-3xl font-bold text-center mb-2">Donnez votre avis</h1>
        <p class="text-center text-gray-600 mb-8">Votre témoignage nous aide à nous améliorer</p>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('avis.store') }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
            @csrf
            <div class="mb-4">
                <label class="block font-bold mb-2">Nom de l’entreprise <span class="text-red-500">*</span></label>
                <input type="text" name="entreprise_nom" value="{{ old('entreprise_nom') }}" class="w-full border rounded-lg px-4 py-2" required>
                @error('entreprise_nom') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-2">Votre nom (optionnel)</label>
                <input type="text" name="contact_nom" value="{{ old('contact_nom') }}" class="w-full border rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-2">Votre fonction (optionnel)</label>
                <input type="text" name="contact_fonction" value="{{ old('contact_fonction') }}" class="w-full border rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-2">Note <span class="text-red-500">*</span></label>
                <select name="note" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">-- Sélectionnez une note --</option>
                    @for($i=5; $i>=1; $i--)
                        <option value="{{ $i }}" {{ old('note') == $i ? 'selected' : '' }}>{{ $i }} étoile{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
                @error('note') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-2">Votre avis <span class="text-red-500">*</span></label>
                <textarea name="contenu" rows="5" class="w-full border rounded-lg px-4 py-2" required>{{ old('contenu') }}</textarea>
                @error('contenu') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="text-center">
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-6 rounded-lg transition">
                    Envoyer mon avis
                </button>
            </div>
        </form>
    </div>
</section>
@endsection