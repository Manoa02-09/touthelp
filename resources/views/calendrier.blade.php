<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Calendrier des formations - Tout Help</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body { overflow-x: hidden; padding-top: 92px; background-color: #f3f4f6; }

        /* Doubles barres fixes (exactement comme l’accueil) */
        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #0a2e5a;
            color: white;
            font-size: 0.75rem;
            padding: 0.375rem 0;
            z-index: 60;
        }

        .main-header {
            position: fixed;
            top: 28px;
            left: 0;
            width: 100%;
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            z-index: 50;
            padding: 0.75rem 0;
            border-bottom: 4px solid #0a2e5a;
        }

        /* Navigation style (identique à l’accueil) */
        .nav-link {
            position: relative;
            padding: 10px 0;
            transition: color 0.3s ease;
            font-weight: 800 !important;
            letter-spacing: 0.05em;
            color: #374151;
            text-decoration: none;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #1e3a8a);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: #1e3a8a !important;
        }

        /* Style spécifique pour le bouton "Retour à l’accueil" (peut être un simple lien) */
        .retour-link {
            font-size: 0.9rem;
        }

        /* CARTES FORMATION – inchangé */
        .card-formation {
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 1.25rem;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .card-formation:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 35px -12px rgba(0,0,0,0.25);
        }
        .grid-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3rem;
        }
        .img-full-display {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- DOUBLE HEADER (copie conforme de l’accueil) -->
    <div class="top-bar">
        <div class="container mx-auto px-4 md:px-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-envelope text-white/70"></i>
                <span class="font-bold uppercase tracking-tighter">touthelp.com</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="tel:+261344839743" class="hover:text-yellow-300 transition flex items-center gap-1">
                    <i class="fas fa-phone-alt text-[10px]"></i>
                    <span>+261 34 48 397 43</span>
                </a>
            </div>
        </div>
    </div>

    <header class="main-header">
        <div class="container mx-auto px-4 md:px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 md:space-x-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 md:h-12 lg:h-14">
                <span class="text-xl md:text-2xl font-bold text-green-900">TOUT HELP</span>
            </div>
            <nav class="hidden md:flex space-x-6 lg:space-x-10">
                <!-- Seul lien : retour à l'accueil -->
                <a href="{{ route('accueil') }}" class="nav-link retour-link text-gray-700 text-sm lg:text-base">RETOUR À L'ACCUEIL</a>
            </nav>
        </div>
    </header>

    <!-- CONTENU PRINCIPAL (inchangé) -->
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-center mb-4">Calendrier des formations</h1>
        <p class="text-center text-gray-600 mb-12">Retrouvez toutes nos formations programmées</p>

        @if(isset($formations) && $formations->count())
            <div class="grid-container">
                @foreach($formations as $formation)
                <div class="card-formation bg-white rounded-2xl shadow-xl flex flex-col">
                    
                    @if($formation->image)
                        <div class="w-full bg-white">
                            <img src="{{ asset('storage/' . $formation->image) }}" 
                                 class="img-full-display" 
                                 alt="{{ $formation->titre }}">
                        </div>
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-green-700 to-green-500 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-6xl text-white"></i>
                        </div>
                    @endif

                    <div class="p-8 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $formation->titre }}</h3>
                        
                        @if($formation->description_courte || $formation->description)
                        <p class="text-gray-600 mb-6 text-base leading-relaxed">
                            {{ $formation->description_courte ?? Str::limit($formation->description, 150) }}
                        </p>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-500 mb-8 border-t border-gray-100 pt-6">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-day text-green-600 text-lg"></i>
                                <div>
                                    <p class="font-semibold text-gray-800">Date</p>
                                    <span>{{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            
                            @if($formation->lieu)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-map-marker-alt text-green-600 text-lg"></i>
                                <div>
                                    <p class="font-semibold text-gray-800">Lieu</p>
                                    <span>{{ $formation->lieu }}</span>
                                </div>
                            </div>
                            @endif

                            @if($formation->prix)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-tag text-green-600 text-lg"></i>
                                <div>
                                    <p class="font-semibold text-gray-800">Investissement</p>
                                    <span>{{ number_format($formation->prix, 0, ',', ' ') }} Ar</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSfFI14se99Hhy8WlNKhEJYU2niSJRXlxOmNT7zj1w9Ng5I_DA/viewform?usp=header" 
                           target="_blank" 
                           class="inline-block bg-green-700 hover:bg-green-800 text-white text-center font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 text-lg shadow-lg">
                            S'inscrire à cette formation <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-white shadow-inner rounded-3xl">
                <i class="fas fa-calendar-alt text-6xl text-gray-200 mb-4"></i>
                <p class="text-gray-500 text-xl">Aucune formation programmée pour le moment.</p>
            </div>
        @endif

        <div class="text-center mt-16">
            <a href="{{ route('accueil') }}" class="inline-flex items-center text-green-700 hover:text-green-900 font-medium text-lg transition">
                <i class="fas fa-chevron-left mr-2"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    <footer class="bg-gray-800 text-gray-300 py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Tout Help. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>