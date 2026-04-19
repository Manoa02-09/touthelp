@extends('layouts.admin')

@section('content')
<div class="p-6 min-h-screen bg-gradient-to-br from-[#f9fafc] to-[#f0f2f8]">
    {{-- Titre avec la typographie moderne de l'image --}}
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-800">Tableau de bord administrateur</h2>
        <div class="h-1 w-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mt-2"></div>
    </div>

    {{-- Grille des 3 cartes — design épuré, ombres douces, coins très arrondis --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Carte Catalogues (bleu) --}}
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-7 border border-gray-100/80 backdrop-blur-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
                        Catalogues
                    </h3>
                    <p class="text-4xl font-extrabold text-blue-600 mt-3 tracking-tight">{{ $totalCatalogues }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-2xl group-hover:scale-105 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400 uppercase tracking-wider flex items-center gap-1">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-400"></span> ressources actives
            </div>
        </div>

        {{-- Carte Formations (vert) --}}
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-7 border border-gray-100/80">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                        Formations
                    </h3>
                    <p class="text-4xl font-extrabold text-green-600 mt-3 tracking-tight">{{ $totalFormations }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-2xl group-hover:scale-105 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400 uppercase tracking-wider flex items-center gap-1">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-400"></span> parcours disponibles
            </div>
        </div>

        {{-- Carte Expertises (violet) --}}
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-7 border border-gray-100/80">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-500 inline-block"></span>
                        Expertises
                    </h3>
                    <p class="text-4xl font-extrabold text-purple-600 mt-3 tracking-tight">{{ $totalExpertises }}</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-2xl group-hover:scale-105 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400 uppercase tracking-wider flex items-center gap-1">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-purple-400"></span> certifications
            </div>
        </div>
    </div>

    {{-- Optionnel : fine ligne de séparation inspirée du design original --}}
    <div class="mt-12 border-t border-gray-200/60 pt-6 text-right">
        <span class="text-xs text-gray-400 italic">✨ interface inspirée du dashboard moderne</span>
    </div>
</div>
@endsection