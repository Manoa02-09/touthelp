@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Paramètres</h2>
    <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded shadow p-6 max-w-md">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block font-bold mb-2">Email de contact (affiché dans le footer)</label>
            <input type="email" name="contact_email" value="{{ $email }}" class="w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
    </form>
</div>
@endsection