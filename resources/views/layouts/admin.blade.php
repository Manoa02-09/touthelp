<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tout Help</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold">Administration Tout Help</h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4">
        @yield('content')
    </div>
</body>
</html>