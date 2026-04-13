@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Tableau de bord administrateur</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Catalogues</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalCatalogues }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Formations</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalFormations }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Expertises</h3>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalExpertises }}</p>
        </div>
    </div>
</div>
@endsection