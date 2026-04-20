<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - Tout Help</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-white">

    <!-- En-tête (identique à welcome) -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Logo" class="h-12">
                <span class="text-xl font-bold text-green-900">TOUT HELP</span>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-green-800 font-medium">ACCUEIL</a>
                <a href="{{ url('/#expertise') }}" class="text-gray-700 hover:text-green-800 font-medium">EXPERTISE</a>
                <a href="{{ url('/#catalogue') }}" class="text-gray-700 hover:text-green-800 font-medium">CATALOGUE</a>
                <a href="{{ url('/#blog') }}" class="text-gray-700 hover:text-green-800 font-medium">BLOG</a>
                <a href="{{ url('/apropos') }}" class="text-gray-700 hover:text-green-800 font-medium font-bold border-b-2 border-green-600">À PROPOS</a>
                <a href="{{ url('/#contact') }}" class="text-gray-700 hover:text-green-800 font-medium">CONTACT</a>
            </nav>
        </div>
    </header>

    <!-- Section principale À propos -->
    <main class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">À propos de nous</h1>
                <div class="w-24 h-1 bg-green-600 mx-auto rounded-full"></div>
                <p class="text-gray-500 mt-4">Découvrez qui nous sommes et notre mission</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8 items-center">
                        <div class="md:w-1/2">
                            <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Tout Help" class="w-full max-w-xs mx-auto">
                        </div>
                        <div class="md:w-1/2">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">Qui sommes-nous ?</h2>
                            <p class="text-gray-600 mb-4">
                                Tout Help est une entreprise malgache spécialisée dans les solutions RH et l'accompagnement sur-mesure.
                            </p>
                            <p class="text-gray-600">
                                Notre mission est d'accompagner les organisations dans leur développement en mettant la performance au cœur de leur culture.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div class="bg-gray-50 rounded-xl p-6 text-center">
                    <i class="fas fa-chart-line text-3xl text-green-600 mb-3"></i>
                    <h3 class="font-bold text-lg mb-2">Performance</h3>
                    <p class="text-gray-500 text-sm">Optimisation des processus et des compétences</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-6 text-center">
                    <i class="fas fa-users text-3xl text-green-600 mb-3"></i>
                    <h3 class="font-bold text-lg mb-2">Accompagnement</h3>
                    <p class="text-gray-500 text-sm">Support personnalisé à chaque étape</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-6 text-center">
                    <i class="fas fa-certificate text-3xl text-green-600 mb-3"></i>
                    <h3 class="font-bold text-lg mb-2">Excellence</h3>
                    <p class="text-gray-500 text-sm">Des solutions de qualité supérieure</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex gap-6">
                    <a href="{{ url('/apropos') }}" class="hover:text-green-400 transition">À propos de nous</a>
                    <a href="{{ url('/#contact') }}" class="hover:text-green-400 transition">Contact</a>
                    <a href="#" class="hover:text-green-400 transition">Mentions légales</a>
                </div>
                <div>
                    <p>&copy; {{ date('Y') }} Tout Help. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>