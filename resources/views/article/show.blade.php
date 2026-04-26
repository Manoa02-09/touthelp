<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->titre }} | TOUT HELP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&family=Inter:wght@400;600;700&display=swap');
        
        body { 
            font-family: 'Inter', sans-serif; 
            /* Couleur de fond plus sombre pour casser le blanc */
            background-color: #e2e8f0; 
            color: #1e293b; 
            margin: 0; 
        }
        
        /* HOVER LIGNE BLEU MARINE TRÈS VISIBLE */
        .nav-link {
            position: relative;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 8px 0;
            transition: color 0.3s ease;
        }

        /* La ligne horizontale bleue */
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 4px; /* Épaisseur bien visible */
            bottom: -5px;
            left: 0;
            background-color: #001f3f; /* BLEU MARINE */
            transition: width 0.3s ease-in-out;
        }

        .nav-link:hover {
            color: #001f3f;
        }

        .nav-link:hover::after {
            width: 100%; /* La ligne s'étire au survol */
        }

        /* STYLE DE L'ARTICLE "PAPIER" */
        .article-card {
            background-color: #fdfdfd; /* Blanc cassé type papier */
            border: 1px solid #cbd5e1;
            padding: 3rem;
            border-radius: 4px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .article-body {
            line-height: 1.8;
            font-size: 1.2rem;
            text-align: justify;
            color: #334155;
        }

        /* Bordure d'image avec ombre */
        .img-border {
            border: 2px solid #001f3f;
            padding: 10px;
            background: white;
            display: inline-block;
            border-radius: 8px;
        }

        .top-bar { background-color: #001f3f; }
    </style>
</head>
<body class="antialiased">

    <header class="w-full bg-white border-b-2 border-slate-200">
        <div class="top-bar h-10 w-full"></div>
        <div class="container mx-auto px-6 py-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 w-auto">
                <span class="font-black text-2xl text-[#004d40] tracking-tight">TOUT HELP</span>
            </div>
            
            <nav>
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à l'accueil
                </a>
            </nav>
        </div>
    </header>

    <main class="py-16">
        <div class="container mx-auto px-4 max-w-4xl">
            
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-10 leading-tight">
                    {{ $article->titre }}
                </h1>
                
                <div class="img-border shadow-2xl">
                    <img src="{{ asset('storage/'.$article->image_une) }}" class="max-h-80 w-auto rounded" alt="Couverture">
                </div>
            </div>

            <div class="article-card">
                <div class="article-body">
                    {!! $article->contenu !!}
                </div>
            </div>

            <footer class="mt-12 text-right italic text-slate-500 font-bold">
                Mahefa A. RAVALISON
            </footer>
        </div>
    </main>

</body>
</html>