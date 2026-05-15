<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->titre }} | TOUT HELP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Inter:wght@400;500;600;700&display=swap');

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }

        /* ── Header sticky (contient la bande bleue + la barre blanche) ── */
        .main-header {
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* ── Top band (maintenant DANS le header) ── */
        .top-band {
            background-color: #1a2a5e;
            height: 6px;
            width: 100%;
        }

        /* ── Barre blanche du header ── */
        .header-bar {
            background: #ffffff;
            border-bottom: 3px solid #f97316;
            box-shadow: 0 2px 16px rgba(26,42,94,0.10);
        }

        .header-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 48px;
            height: 68px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-group {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .logo-img-wrap {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-text-block {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .logo-title {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            letter-spacing: 0.12em;
            color: #1a2a5e;
            font-size: 1.1rem;
            line-height: 1;
        }

        .logo-tagline {
            font-size: 0.65rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .header-divider {
            width: 1px;
            height: 32px;
            background: #e2e8f0;
            margin: 0 24px;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #ffffff;
            background-color: #dc2626;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.07em;
            text-decoration: none;
            padding: 9px 18px;
            border: none;
            border-radius: 7px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(220,38,38,0.25);
        }

        .nav-link:hover {
            background-color: #b91c1c;
            box-shadow: 0 4px 12px rgba(220,38,38,0.35);
            transform: translateY(-1px);
        }

        .nav-link:active {
            transform: translateY(0);
        }

        /* ── Section titre + image centrée ── */
        .hero-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 52px 48px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 900;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            line-height: 1.2;
            color: #1a2a5e;
            margin: 0 0 36px;
        }

        .hero-image-wrap {
            display: inline-block;
            max-width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(26,42,94,0.13);
        }

        .hero-image-wrap img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        /* ── Article body ── */
        .article-wrap {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 48px 80px;
        }

        .article-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 52px 64px;
        }

        .article-body {
            font-family: 'Inter', sans-serif;
            font-size: 1.1rem;
            line-height: 1.85;
            color: #334155;
            text-align: justify;
        }

        .article-body h2,
        .article-body h3 {
            font-family: 'Playfair Display', Georgia, serif;
            color: #1a2a5e;
            margin-top: 2rem;
        }

        .article-body p { margin: 0 0 1.2rem; }

        .article-body a {
            color: #f97316;
            text-decoration: underline;
        }

        .article-body img {
            max-width: 100%;
            border-radius: 6px;
            margin: 1.5rem 0;
        }

        .article-body blockquote {
            border-left: 4px solid #f97316;
            margin: 1.5rem 0;
            padding: 0.5rem 0 0.5rem 1.5rem;
            color: #64748b;
            font-style: italic;
        }

        @media (max-width: 640px) {
            .article-card { padding: 28px 20px; }
            .header-inner { padding: 0 16px; height: 60px; }
            .hero-section { padding: 32px 16px 0; }
            .article-wrap { padding: 24px 16px 60px; }
            .logo-tagline { display: none; }
            .header-divider { display: none; }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Header sticky (contient la bande bleue + la barre blanche) -->
    <header class="main-header">
        <div class="top-band"></div>
        <div class="header-bar">
            <div class="header-inner">
                <a href="{{ url('/') }}" class="logo-group">
                    <div class="logo-img-wrap">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo TOUT HELP">
                    </div>
                    <div class="logo-text-block">
                        <span class="logo-title">TOUT HELP</span>
                        <span class="logo-tagline">Magazine & Actualités</span>
                    </div>
                </a>

                <div class="header-divider"></div>

                <nav class="header-nav">
                    <a href="{{ url('/') }}" class="nav-link">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Retour à l'accueil
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Titre + image centrée avec marges -->
    <div class="hero-section">
        <h1>{{ $article->titre }}</h1>
        <div class="hero-image-wrap">
            <img
                src="{{ asset('storage/'.$article->image_une) }}"
                alt="Illustration de l'article"
            >
        </div>
    </div>

    <!-- Contenu de l'article -->
    <main>
        <div class="article-wrap">
            <article class="article-card">
                <div class="article-body">
                    {!! $article->contenu !!}
                </div>
            </article>
        </div>
    </main>

</body>
</html>