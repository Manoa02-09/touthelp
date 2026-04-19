<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier des formations - Tout Help</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .card-formation {
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
            max-width: 900px; /* Largeur très large pour un format rectangulaire horizontal */
            margin: 0 auto;
            border-radius: 1.25rem;
            display: flex;
            flex-direction: column;
        }
        .card-formation:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 35px -12px rgba(0,0,0,0.25);
        }
        .grid-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }
        /* Optionnel : sur très grands écrans, on peut encore centrer */
        @media (min-width: 1200px) {
            .grid-container {
                max-width: 1000px;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Logo" class="h-12">
                <span class="text-xl font-bold text-green-900">TOUT HELP</span>
            </div>
            <nav class="hidden md:flex space-x-6">
                <a href="{{ route('accueil') }}" class="text-gray-700 hover:text-green-800">ACCUEIL</a>
                <a href="{{ route('accueil') }}#expertise" class="text-gray-700 hover:text-green-800">EXPERTISE</a>
                <a href="{{ route('accueil') }}#catalogue" class="text-gray-700 hover:text-green-800">CATALOGUE</a>
                <a href="{{ route('accueil') }}#blog" class="text-gray-700 hover:text-green-800">BLOG</a>
                <a href="{{ route('accueil') }}#contact" class="text-gray-700 hover:text-green-800">CONTACT</a>
            </nav>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-center mb-4">Calendrier des formations</h1>
        <p class="text-center text-gray-600 mb-12">Retrouvez toutes nos formations programmées</p>

        @if(isset($formations) && $formations->count())
            <div class="grid-container">
                @foreach($formations as $formation)
                <div class="card-formation bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition flex flex-col">
                    @if($formation->image)
                        <img src="{{ asset('storage/' . $formation->image) }}" class="w-full h-60 object-cover" alt="{{ $formation->titre }}">
                    @else
                        <div class="w-full h-60 bg-gradient-to-r from-green-700 to-green-500 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-6xl text-white"></i>
                        </div>
                    @endif
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $formation->titre }}</h3>
                        <p class="text-gray-600 mb-4 text-base">
                            {{ $formation->description_courte ?? Str::limit($formation->description, 120) }}
                        </p>
                        <div class="space-y-2 text-sm text-gray-500 mb-6">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar-day w-5 text-green-600"></i>
                                <span>{{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}</span>
                            </div>
                            @if($formation->lieu)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt w-5 text-green-600"></i>
                                <span>{{ $formation->lieu }}</span>
                            </div>
                            @endif
                            @if($formation->prix)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-tag w-5 text-green-600"></i>
                                <span>{{ number_format($formation->prix, 0, ',', ' ') }} Ar</span>
                            </div>
                            @endif
                        </div>
                        <!-- Bouton d'inscription vers Google Forms -->
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSfFI14se99Hhy8WlNKhEJYU2niSJRXlxOmNT7zj1w9Ng5I_DA/viewform?usp=header" 
                           target="_blank" 
                           class="mt-auto inline-block bg-green-700 hover:bg-green-800 text-white text-center font-bold py-3 px-4 rounded-xl transition text-lg">
                            S'inscrire <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <i class="fas fa-calendar-alt text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Aucune formation programmée pour le moment.</p>
            </div>
        @endif

        <div class="text-center mt-12">
            <a href="{{ route('accueil') }}" class="text-green-700 hover:underline text-lg">← Retour à l'accueil</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Tout Help. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html> 