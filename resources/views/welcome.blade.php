<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Tout Help - Accueil</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://js.pusher.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; connect-src 'self' ws://localhost:8080 wss://localhost:8080 http://127.0.0.1:8080; frame-ancestors 'none';">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    <meta http-equiv="Permissions-Policy" content="geolocation=(), microphone=(), camera=()">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        .scroll-mt-header { scroll-margin-top: 110px; }
        
        /* Header navigation */
        header {
            border-bottom: 4px solid #0a2e5a;
        }
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
        .nav-link:hover::after { width: 100%; }
        .nav-link:hover { color: #1e3a8a !important; }
        .nav-link.active { color: #f97316 !important; }
        
        /* Boutons */
        .btn-primary {
            background: linear-gradient(135deg, #0a2e5a, #1e3a8a);
            color: white;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(10, 46, 90, 0.25);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(10, 46, 90, 0.35);
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
        }
        .btn-secondary {
            background: rgba(0, 0, 0, 0.05);
            color: #1f2937;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }
        .btn-secondary:hover {
            background: rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
            gap: 12px;
        }
        
        /* Animation image déformée */
        @keyframes morph {
            0% { border-radius: 60% 40% 55% 45% / 40% 55% 45% 60%; }
            50% { border-radius: 45% 55% 50% 50% / 55% 45% 55% 45%; }
            100% { border-radius: 60% 40% 55% 45% / 40% 55% 45% 60%; }
        }
        .blob-image { animation: morph 8s ease-in-out infinite; }
        
        /* Catalogue cards */
        .catalogue-card-modern {
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .catalogue-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.3);
        }
        .catalogue-card-image {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #1e1b4b, #0f172a);
        }
        .catalogue-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .catalogue-card-modern:hover .catalogue-card-image img { transform: scale(1.08); }
        .catalogue-card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(165, 180, 252, 0.1), rgba(129, 140, 248, 0.05));
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .catalogue-card-modern:hover .catalogue-card-overlay { opacity: 1; }
        .catalogue-card-body { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
        .catalogue-card-title { font-size: 1.25rem; font-weight: 800; color: #1e1b4b; margin-bottom: 0.75rem; line-height: 1.4; transition: color 0.3s ease; }
        .catalogue-card-modern:hover .catalogue-card-title { color: #4f46e5; }
        .catalogue-card-desc { color: #4b5563; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem; flex: 1; }
        .catalogue-card-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 40px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            width: fit-content;
            position: relative;
            overflow: hidden;
        }
        .catalogue-card-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        .catalogue-card-btn:hover::before { left: 100%; }
        .catalogue-card-btn:hover {
            background: linear-gradient(135deg, #6366f1, #818cf8);
            transform: translateX(5px);
            gap: 14px;
        }
        
        /* Section badges */
        .section-badge {
            display: inline-block;
            padding: 0.35rem 1.1rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .badge-orange { background: #FEF3C7; color: #d97706; }
        .badge-green { background: #D1FAE5; color: #065f46; }
        .badge-amber { background: #FEF3C7; color: #92400e; }
        .badge-purple { background: #EDE9FE; color: #5b21b6; }
        
        /* Scroll arrows */
        .scroll-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border-radius: 50%;
            padding: 0.6rem 0.75rem;
            box-shadow: 0 3px 12px rgba(0,0,0,0.12);
            opacity: 0;
            transition: opacity 0.2s;
            border: none;
            cursor: pointer;
        }
        .group:hover .scroll-arrow { opacity: 1; }
        .scroll-arrow.left { left: 0; }
        .scroll-arrow.right { right: 0; }
        
        /* Chat Modal */
        .chat-modal {
            display: none;
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 380px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            z-index: 99999;
            overflow: hidden;
            flex-direction: column;
        }
        .chat-modal.active { display: flex; animation: fadeInUp 0.3s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .chat-header {
            background: linear-gradient(135deg, #e63946, #ff6b6b, #f8c291);
            color: white;
            padding: 15px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .chat-header-left { display: flex; align-items: center; gap: 10px; }
        .chat-header-avatar { width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .chat-header-name { font-weight: 700; font-size: 14px; }
        .chat-header-status { font-size: 11px; opacity: 0.8; display: flex; align-items: center; gap: 4px; }
        .chat-status-dot { width: 6px; height: 6px; background: #4ade80; border-radius: 50%; display: inline-block; }
        .chat-close-btn { background: rgba(255,255,255,0.15); border: none; color: white; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; font-size: 14px; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
        .chat-close-btn:hover { background: rgba(255,255,255,0.3); }
        .chat-body { flex: 1; overflow-y: auto; background-color: #fef9f9; background-image: radial-gradient(circle at 10px 10px, rgba(230,57,70,0.03) 1px, transparent 1px); background-size: 20px 20px; max-height: 380px; }
        .chat-messages-area { padding: 14px; display: flex; flex-direction: column; gap: 8px; min-height: 100px; }
        .chat-input-area { background: white; border-top: 1px solid #ffe0e0; padding: 10px 12px; display: flex; gap: 8px; align-items: flex-end; flex-shrink: 0; }
        .chat-textarea { flex: 1; border: 1px solid #ffe0e0; border-radius: 20px; padding: 9px 14px; font-size: 13px; resize: none; outline: none; max-height: 100px; line-height: 1.4; transition: border-color 0.2s; }
        .chat-textarea:focus { border-color: #e63946; }
        .chat-send-btn { width: 38px; height: 38px; background: linear-gradient(135deg, #e63946, #ff6b6b); border: none; border-radius: 50%; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: transform 0.15s, opacity 0.15s; }
        .chat-send-btn:hover { transform: scale(1.05); }
        .chat-send-btn:active { transform: scale(0.95); }
        .chat-send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .chat-init-form { padding: 16px; }
        .chat-init-form input, .chat-init-form textarea { width: 100%; padding: 9px 12px; margin-bottom: 10px; border: 1px solid #ffe0e0; border-radius: 10px; font-size: 13px; outline: none; transition: border-color 0.2s; background: white; color: #1f2937; }
        .chat-init-form input:focus, .chat-init-form textarea:focus { border-color: #e63946; }
        .chat-init-btn { width: 100%; background: linear-gradient(135deg, #e63946, #ff6b6b); color: white; border: none; padding: 10px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; transition: opacity 0.2s; }
        .chat-init-btn:hover { opacity: 0.9; }
        .chat-init-btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .bubble-sent { display: flex; justify-content: flex-end; }
        .bubble-sent-inner { background: linear-gradient(135deg, #e63946, #ff6b6b); color: white; border-radius: 18px 18px 4px 18px; padding: 9px 13px; max-width: 75%; box-shadow: 0 2px 6px rgba(230,57,70,0.25); word-break: break-word; }
        .bubble-received { display: flex; justify-content: flex-start; align-items: flex-end; gap: 8px; }
        .bubble-received-avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #e63946, #ff6b6b); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; color: white; font-weight: 600; flex-shrink: 0; }
        .bubble-received-inner { background: white; color: #1f2937; border-radius: 18px 18px 18px 4px; padding: 9px 13px; max-width: 75%; box-shadow: 0 2px 6px rgba(0,0,0,0.07); word-break: break-word; }
        .bubble-text { font-size: 13px; line-height: 1.45; }
        .bubble-time { font-size: 10px; margin-top: 3px; text-align: right; opacity: 0.65; }
        .bubble-time-left { font-size: 10px; margin-top: 3px; opacity: 0.55; }
        .pending-tag { text-align: center; font-size: 11px; color: #e63946; background: rgba(255,255,255,0.9); border-radius: 20px; padding: 4px 12px; margin: 6px auto; width: fit-content; border: 1px solid #ffe0e0; }
        .change-identity-btn { font-size: 11px; color: #e63946; background: none; border: none; cursor: pointer; text-align: center; width: 100%; padding: 6px; display: block; transition: color 0.15s; }
        .change-identity-btn:hover { color: #c1121f; }
        .robot-icon { position: fixed; bottom: 20px; right: 20px; background: linear-gradient(135deg, #e63946, #ff6b6b); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 5px 20px rgba(230,57,70,0.4); transition: all 0.3s ease; z-index: 9999; }
        .robot-icon:hover { transform: scale(1.1); }
        .robot-icon i { font-size: 28px; color: white; }
        .robot-badge { position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 11px; font-weight: bold; min-width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0 4px; border: 2px solid white; animation: badgePulse 0.6s ease-in-out; }
        @keyframes badgePulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); background-color: #ef4444; } }
        .chat-footer { background: white; padding: 6px; text-align: center; font-size: 11px; color: #e63946; border-top: 1px solid #ffe0e0; flex-shrink: 0; }
        
        /* Général */
        body { overflow-x: hidden; padding-top: 92px; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-4 { display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #e63946; border-radius: 10px; }
        
        /* Partenaires section */
        #partenaires-section .partenaire-badge {
            flex-shrink: 0;
            width: 130px;
            height: 130px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 18px rgba(16,185,129,0.12), 0 1.5px 6px rgba(16,185,129,0.08);
            border: 2px solid #A7F3D0;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        #partenaires-section .partenaire-badge:hover { transform: scale(1.07) translateY(-4px); box-shadow: 0 10px 30px rgba(16,185,129,0.18); }
        
        /* Avis section */
        #avis-section .avis-card {
            flex-shrink: 0;
            width: 340px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 6px 24px rgba(245,158,11,0.12), 0 2px 8px rgba(245,158,11,0.07);
            padding: 2rem;
            border: 1.5px solid #FDE68A;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        #avis-section .avis-card:hover { transform: translateY(-5px); box-shadow: 0 14px 40px rgba(245,158,11,0.18); }
        
        /* Blog section */
        #blog .blog-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 6px 24px rgba(109,40,217,0.09), 0 2px 8px rgba(109,40,217,0.05);
            overflow: hidden;
            border: 1.5px solid #DDD6FE;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }
        #blog .blog-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(109,40,217,0.16); }
        #blog .blog-card img { width: 100%; height: 220px; object-fit: cover; border-bottom: 2px solid #DDD6FE; }
        .give-review-btn { display: inline-block; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; font-weight: 700; padding: 0.85rem 2.5rem; border-radius: 14px; font-size: 1.05rem; transition: opacity 0.2s, transform 0.15s; text-decoration: none; box-shadow: 0 4px 14px rgba(245,158,11,0.25); }
        .give-review-btn:hover { opacity: 0.9; transform: scale(1.03); }
    </style>
</head>
<body class="bg-white">

    <!-- DOUBLE HEADER -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; background-color: #0a2e5a; color: white; font-size: 0.75rem; padding: 0.375rem 0; z-index: 60;">
        <div class="container mx-auto px-4 md:px-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-certificate text-orange-400"></i>
                <span class="font-bold uppercase tracking-tighter">Cabinet Habilité FMFP</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="tel:+261344839743" class="hover:text-yellow-300 transition flex items-center gap-1">
                    <i class="fas fa-phone-alt text-[10px]"></i>
                    <span>+261 34 48 397 43</span>
                </a>
            </div>
        </div>
    </div>

    <header style="position: fixed; top: 28px; left: 0; width: 100%; background-color: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); z-index: 50; padding: 0.75rem 0;">
        <div class="container mx-auto px-4 md:px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 md:space-x-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 md:h-12 lg:h-14">
                <span class="text-xl md:text-2xl font-bold text-green-900">TOUT HELP</span>
            </div>
            <nav class="hidden md:flex space-x-6 lg:space-x-10">
                <a href="#accueil" class="nav-link text-gray-700 text-sm lg:text-base">ACCUEIL</a>
                <a href="#apropos" class="nav-link text-gray-700 text-sm lg:text-base">À PROPOS</a>
                <a href="#expertise" class="nav-link text-gray-700 text-sm lg:text-base">EXPERTISE</a>
                <a href="#catalogue" class="nav-link text-gray-700 text-sm lg:text-base">CATALOGUE</a>
                <a href="#blog" class="nav-link text-gray-700 text-sm lg:text-base">BLOG</a>
                <a href="#contact" class="nav-link text-gray-700 text-sm lg:text-base">CONTACT</a>
            </nav>
        </div>
    </header>

    <!-- SECTION ACCUEIL -->
    <section id="accueil" class="relative bg-white overflow-hidden scroll-mt-header" style="height: calc(100vh - 92px); display: flex; flex-direction: column; justify-content: center;">
        <div class="container mx-auto px-4 md:px-6 flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-12">
            <div class="lg:w-1/2 z-10 text-center lg:text-left">
                <div class="inline-flex items-center space-x-2 bg-gray-100 px-3 py-1.5 md:px-4 md:py-2 rounded-full text-[10px] md:text-xs font-bold text-gray-500 mb-6 md:mb-8 tracking-widest uppercase mx-auto lg:mx-0">
                    <i class="fas fa-shield-alt text-blue-500"></i>
                    <span>Formation • Accompagnement • Audit</span>
                </div>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black text-[#0f2439] leading-[1.1] md:leading-[1.1] lg:leading-[0.9] mb-4 md:mb-6">
                    ENSEMBLE<br>
                    FAISONS DE LA<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-300">PERFORMANCE</span><br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-500 to-orange-400">UNE CULTURE</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-xl mx-auto lg:mx-0 mb-8 md:mb-10 border-l-4 border-orange-500 pl-4 md:pl-6">
                    Expert en <span class="text-blue-600 font-bold">Solutions RH</span> et <span class="text-blue-600 font-bold">Accompagnement sur-mesure</span> à Madagascar.
                </p>
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="#contact" class="btn-primary"><i class="fas fa-file-invoice-dollar"></i> Demander un devis</a>
                    <a href="#apropos" class="btn-secondary">En savoir plus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="lg:w-1/2 relative mt-10 lg:mt-0 flex justify-center">
                <div class="relative z-10 w-full max-w-md md:max-w-xl lg:max-w-2xl">
                    <img src="{{ asset('images/accueil.png') }}" alt="Expertise Tout Help" class="w-full h-auto object-contain">
                </div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-blue-50 rounded-full filter blur-3xl opacity-50 -z-10"></div>
            </div>
        </div>
        <div class="zigzag-bottom w-full overflow-hidden leading-none flex-shrink-0">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs><linearGradient id="violetGradient" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#667eea;stop-opacity:1" /><stop offset="50%" style="stop-color:#764ba2;stop-opacity:1" /><stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" /></linearGradient></defs>
                <rect x="0" y="0" width="1440" height="80" fill="#0a0a0a"/>
                <polygon points="0,0 40,80 80,0 120,80 160,0 200,80 240,0 280,80 320,0 360,80 400,0 440,80 480,0 520,80 560,0 600,80 640,0 680,80 720,0 760,80 800,0 840,80 880,0 920,80 960,0 1000,80 1040,0 1080,80 1120,0 1160,80 1200,0 1240,80 1280,0 1320,80 1360,0 1400,80 1440,0" fill="url(#violetGradient)"/>
            </svg>
        </div>
    </section>

    <!-- SECTION À PROPOS (fond noir - design luxe) -->
    <section id="apropos" class="py-20 md:py-28 scroll-mt-header" style="background-color: #0a0a0a;">
        <div class="container mx-auto px-4 md:px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-4">
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase" style="background: rgba(255, 215, 0, 0.1); color: #FFD700; border: 1px solid rgba(255, 215, 0, 0.2);">✦ Qui sommes-nous ? ✦</span>
                </div>
                <div class="text-center mb-12">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold" style="color: #ffffff;">TOUT <span style="color: #FFD700;">HELP</span></h2>
                    <div class="w-16 h-0.5 mx-auto mt-4 rounded-full" style="background: #FFD700;"></div>
                </div>
                <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                    <div class="lg:w-1/2 space-y-5">
                        <p class="text-gray-300 text-lg leading-relaxed">Tout Help est une entreprise malgache spécialisée dans les <span style="color: #FFD700;">solutions RH</span> et l'<span style="color: #FFD700;">accompagnement sur-mesure</span>.</p>
                        <p class="text-gray-400 leading-relaxed">Notre mission est d'accompagner les organisations dans leur développement en mettant la <span style="color: #FFD700;">performance</span> au cœur de leur culture d'entreprise.</p>
                        <div class="flex flex-wrap gap-6 pt-4">
                            <div><p class="text-white font-bold text-2xl">10+</p><p class="text-gray-400 text-xs">Années d'expertise</p></div>
                            <div><p class="text-white font-bold text-2xl">200+</p><p class="text-gray-400 text-xs">Entreprises formées</p></div>
                            <div><p class="text-white font-bold text-2xl">50+</p><p class="text-gray-400 text-xs">Programmes actifs</p></div>
                        </div>
                    </div>
                    <div class="lg:w-1/2 flex justify-center">
                        <div class="relative">
                            <div class="absolute -inset-4 rounded-full opacity-30 blur-xl" style="background: radial-gradient(circle, #FFD700, transparent);"></div>
                            <div class="relative overflow-hidden blob-image" style="box-shadow: 0 20px 40px rgba(0,0,0,0.4);">
                                <img src="{{ asset('images/apropos.png') }}" alt="Tout Help" class="w-80 md:w-96 object-cover">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-20">
                    <div class="text-center p-6 rounded-2xl transition-all duration-300 hover:bg-white/5" style="background: rgba(255,255,255,0.03);">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4" style="background: rgba(255, 215, 0, 0.1);"><i class="fas fa-chart-line text-yellow-500"></i></div>
                        <h4 class="text-white font-bold text-lg mb-2">Performance</h4><p class="text-gray-400 text-sm">Optimisation des processus et compétences</p>
                    </div>
                    <div class="text-center p-6 rounded-2xl transition-all duration-300 hover:bg-white/5" style="background: rgba(255,255,255,0.03);">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4" style="background: rgba(255, 215, 0, 0.1);"><i class="fas fa-users text-yellow-500"></i></div>
                        <h4 class="text-white font-bold text-lg mb-2">Accompagnement</h4><p class="text-gray-400 text-sm">Support personnalisé à chaque étape</p>
                    </div>
                    <div class="text-center p-6 rounded-2xl transition-all duration-300 hover:bg-white/5" style="background: rgba(255,255,255,0.03);">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4" style="background: rgba(255, 215, 0, 0.1);"><i class="fas fa-certificate text-yellow-500"></i></div>
                        <h4 class="text-white font-bold text-lg mb-2">Excellence</h4><p class="text-gray-400 text-sm">Des solutions de qualité supérieure</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Séparateur zigzag -->
    <div class="section-divider" style="background:#ffffff;"><svg viewBox="0 0 1200 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="height:80px;"><polygon points="0,0 60,60 120,0 180,60 240,0 300,60 360,0 420,60 480,0 540,60 600,0 660,60 720,0 780,60 840,0 900,60 960,0 1020,60 1080,0 1140,60 1200,0 1200,0 0,0" fill="#0a0a0a"/></svg></div>

    <!-- SECTION EXPERTISE -->
    <section id="expertise" class="py-16 md:py-24 lg:py-32 bg-white scroll-mt-header">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-10 md:mb-16">
                <span class="section-badge badge-orange inline-block mb-3">✨ Notre savoir-faire</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-3 md:mb-4">EXPERTISE <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-yellow-500 via-green-500 via-blue-500 to-purple-500">SUR-MESURE</span></h2>
                <p class="text-gray-500 text-base md:text-lg max-w-2xl mx-auto">Des solutions éprouvées pour transformer vos défis en opportunités de croissance</p>
                <div class="w-24 h-1 bg-gradient-to-r from-red-500 via-yellow-500 via-green-500 via-blue-500 to-purple-500 mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 lg:gap-10">
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col h-full group relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 to-green-500"></div>
                    <div class="p-6 md:p-8 text-center flex flex-col h-full">
                        <div class="flex-shrink-0"><div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-teal-100 flex items-center justify-center mx-auto mb-4 md:mb-6 group-hover:scale-110 transition-transform duration-300"><i class="fas fa-users text-2xl md:text-3xl text-teal-600"></i></div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">FORMATIONS INTER-ENTREPRISES</h3>
                        <p class="text-xs md:text-sm text-teal-600 font-semibold uppercase mb-3 md:mb-4 tracking-wide">OPEN & COLLABORATIF</p></div>
                        <div class="flex-grow text-left"><div class="text-gray-600 text-sm md:text-base space-y-3"><p class="leading-relaxed">Nos formations inter-entreprises rassemblent des professionnels de divers horizons pour un apprentissage riche et collaboratif.</p>
                        <ul class="list-disc pl-5 space-y-1 text-gray-600"><li>✅ D'échanges croisés entre secteurs d'activité</li><li>✅ De cas pratiques concrets et actuels</li><li>✅ D'un réseau professionnel élargi</li><li>✅ De tarifs optimisés grâce au format collectif</li></ul></div></div>
                        <div class="flex-shrink-0 mt-6"><a href="{{ route('expertise.inter') }}" class="w-full md:w-auto inline-block bg-gradient-to-r from-teal-500 to-green-500 hover:from-teal-600 hover:to-green-600 text-white font-semibold py-2 px-5 md:py-3 md:px-6 rounded-xl transition-all duration-300 text-sm md:text-base shadow-md hover:shadow-lg text-center">En savoir plus</a></div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col h-full group relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 to-amber-500"></div>
                    <div class="p-6 md:p-8 text-center flex flex-col h-full">
                        <div class="flex-shrink-0"><div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-4 md:mb-6 group-hover:scale-110 transition-transform duration-300"><i class="fas fa-building text-2xl md:text-3xl text-orange-600"></i></div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">FORMATIONS INTRA-ENTREPRISE</h3>
                        <p class="text-xs md:text-sm text-orange-600 font-semibold uppercase mb-3 md:mb-4 tracking-wide">100% PERSONNALISÉ</p></div>
                        <div class="flex-grow text-left"><div class="text-gray-600 text-sm md:text-base space-y-3"><p class="leading-relaxed">Nos formations intra-entreprise sont conçues sur mesure pour répondre exactement aux besoins spécifiques de votre organisation.</p>
                        <ul class="list-disc pl-5 space-y-1 text-gray-600"><li>✅ Contenus 100% adaptés à votre secteur</li><li>✅ Formations délivrées dans vos locaux</li><li>✅ Planning flexible</li><li>✅ Confidentialité absolue</li></ul></div></div>
                        <div class="flex-shrink-0 mt-6"><a href="{{ route('expertise.intra') }}" class="w-full md:w-auto inline-block bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-semibold py-2 px-5 md:py-3 md:px-6 rounded-xl transition-all duration-300 text-sm md:text-base shadow-md hover:shadow-lg text-center">En savoir plus</a></div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col h-full group relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>
                    <div class="p-6 md:p-8 text-center flex flex-col h-full">
                        <div class="flex-shrink-0"><div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4 md:mb-6 group-hover:scale-110 transition-transform duration-300"><i class="fas fa-clipboard-list text-2xl md:text-3xl text-purple-600"></i></div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">ACCOMPAGNEMENT & AUDIT</h3>
                        <p class="text-xs md:text-sm text-purple-600 font-semibold uppercase mb-3 md:mb-4 tracking-wide">STRUCTURATION & PERFORMANCE</p></div>
                        <div class="flex-grow text-left"><div class="text-gray-600 text-sm md:text-base space-y-3"><p class="leading-relaxed">Bénéficiez d'un regard extérieur expert pour structurer, évaluer et optimiser vos processus.</p>
                        <ul class="list-disc pl-5 space-y-1 text-gray-600"><li>✅ Diagnostic approfondi</li><li>✅ Mise en place de systèmes de management</li><li>✅ Accompagnement jusqu'à la certification</li><li>✅ Plans d'action concrets</li></ul></div></div>
                        <div class="flex-shrink-0 mt-6"><a href="{{ route('expertise.accompagnement') }}" class="w-full md:w-auto inline-block bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-2 px-5 md:py-3 md:px-6 rounded-xl transition-all duration-300 text-sm md:text-base shadow-md hover:shadow-lg text-center">En savoir plus</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION CATALOGUE -->
    <section id="catalogue" class="py-20 md:py-28 scroll-mt-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase mb-4" style="background: rgba(255,255,255,0.1); color: #a5b4fc; border: 1px solid rgba(165, 180, 252, 0.2);">📚 Notre offre de formation</span>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4" style="color: #ffffff;">Catalogues de <span style="color: #a5b4fc;">formation</span></h2>
                <div class="w-20 h-0.5 mx-auto rounded-full" style="background: linear-gradient(90deg, #a5b4fc, #818cf8, #a5b4fc);"></div>
                <p class="text-indigo-200 mt-4 text-base md:text-lg">Découvrez l'ensemble de nos syllabus. Cliquez sur "En savoir plus" pour voir le programme complet.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @forelse($catalogues as $catalogue)
                <div class="catalogue-card-modern group">
                    @if($catalogue->image)<div class="catalogue-card-image"><img src="{{ asset('storage/'.e($catalogue->image)) }}" alt="{{ e($catalogue->titre) }}"><div class="catalogue-card-overlay"></div></div>
                    @else<div class="catalogue-card-image bg-gradient-to-br from-indigo-900 to-purple-900 flex items-center justify-center"><i class="fas fa-book-open text-5xl text-indigo-300 opacity-50"></i></div>@endif
                    <div class="catalogue-card-body">
                        <h3 class="catalogue-card-title">{{ $catalogue->titre }}</h3>
                        <p class="catalogue-card-desc line-clamp-3">{{ Str::limit($catalogue->description, 120) }}</p>
                        <button onclick="openModal({{ (int)$catalogue->id }})" class="catalogue-card-btn"><span>En savoir plus</span><i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20"><i class="fas fa-folder-open text-6xl text-indigo-300 mb-4"></i><p class="text-indigo-200 text-lg md:text-xl">Aucun catalogue disponible pour le moment.</p></div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Séparateur -->
    <div class="section-divider" style="background:#ECFDF5;"><svg viewBox="0 0 1440 90" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="height:90px;"><path d="M0,0 L36,65 L72,0 L108,65 L144,0 L180,65 L216,0 L252,65 L288,0 L324,65 L360,0 L396,65 L432,0 L468,65 L504,0 L540,65 L576,0 L612,65 L648,0 L684,65 L720,0 L756,65 L792,0 L828,65 L864,0 L900,65 L936,0 L972,65 L1008,0 L1044,65 L1080,0 L1116,65 L1152,0 L1188,65 L1224,0 L1260,65 L1296,0 L1332,65 L1368,0 L1404,65 L1440,0 L1440,0 L0,0 Z" fill="#0f172a"/></svg></div>

    <!-- SECTION PARTENAIRES -->
    <section id="partenaires-section" class="py-16 md:py-24 lg:py-32 scroll-mt-header" style="background-color: #ECFDF5;">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-10 md:mb-16">
                <span class="section-badge badge-green">🤝 Confiance</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-3 md:mb-4" style="color: #065f46;">Ils nous font confiance</h2>
                <p class="text-emerald-700 text-sm md:text-base uppercase tracking-wide">Nos partenaires et clients</p>
            </div>
            @if(isset($partenaires) && $partenaires->count())
            <div class="relative overflow-hidden group">
                <div class="flex overflow-x-auto scrollbar-hide gap-6 md:gap-8 lg:gap-10 py-4 md:py-6 px-2 scroll-smooth" id="partenairesScroll">
                    @foreach($partenaires as $partenaire)
                    <div class="partenaire-badge">@if($partenaire->logo)<img src="{{ asset('storage/'.e($partenaire->logo)) }}" alt="{{ e($partenaire->nom_entreprise) }}" class="max-w-full max-h-full p-3 object-contain">@else<span class="text-emerald-700 text-xs font-bold text-center px-2">{{ e($partenaire->nom_entreprise) }}</span>@endif</div>
                    @endforeach
                </div>
                <button onclick="scrollPartenaire('left')" class="scroll-arrow left"><svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button onclick="scrollPartenaire('right')" class="scroll-arrow right"><svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
            @else
            <div class="text-center py-12 md:py-20 bg-white rounded-2xl border border-emerald-100"><p class="text-emerald-500 text-base md:text-xl">Aucun partenaire pour le moment.</p></div>
            @endif
        </div>
    </section>

    <!-- SECTION AVIS -->
    <section id="avis-section" class="py-16 md:py-24 lg:py-32 scroll-mt-header" style="background-color: #FFFBEB;">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-10 md:mb-16">
                <span class="section-badge badge-amber">⭐ Témoignages</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-3 md:mb-4" style="color: #92400e;">Ce qu'ils disent de nous</h2>
                <p class="text-amber-700 text-sm md:text-base uppercase tracking-wide">Témoignages de nos clients</p>
            </div>
            @if(isset($avis) && $avis->count())
            <div class="relative overflow-hidden group">
                <div class="flex overflow-x-auto scrollbar-hide gap-6 md:gap-8 py-4 md:py-6 px-2 scroll-smooth" id="avisScroll">
                    @foreach($avis as $a)
                    <div class="avis-card">
                        <div class="flex text-yellow-500 mb-3">@for($i=1;$i<=5;$i++) @if($i<=$a->note)<i class="fas fa-star"></i>@else<i class="far fa-star" style="color:#FDE68A;"></i>@endif @endfor</div>
                        <p class="text-gray-600 italic mb-4 line-clamp-4">"{{ e(Str::limit($a->contenu, 180)) }}"</p>
                        <div class="flex items-center gap-3 mt-2">
                            @if($a->logo_entreprise)<img src="{{ asset('storage/'.e($a->logo_entreprise)) }}" class="w-11 h-11 rounded-full object-cover border-2 border-amber-200" alt="{{ e($a->entreprise_nom) }}">@else<div class="w-11 h-11 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold">{{ strtoupper(substr($a->entreprise_nom,0,1)) }}</div>@endif
                            <div><p class="font-bold text-gray-800">{{ e($a->entreprise_nom) }}</p><p class="text-sm text-gray-500">{{ e($a->contact_fonction ?? 'Client') }}</p></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button onclick="scrollAvis('left')" class="scroll-arrow left"><svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button onclick="scrollAvis('right')" class="scroll-arrow right"><svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
            <div class="text-center mt-10 md:mt-12"><a href="{{ route('avis.create') }}" class="give-review-btn">✍️ Donnez votre avis</a></div>
            @else
            <div class="text-center py-12 md:py-20 bg-white rounded-2xl border border-amber-100"><p class="text-amber-500 text-base md:text-xl">Aucun avis pour le moment.</p></div>
            @endif
        </div>
    </section>

    <!-- SECTION BLOG -->
    <section id="blog" class="py-16 md:py-24 lg:py-32 scroll-mt-header" style="background-color: #F5F3FF;">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-10 md:mb-16">
                <span class="section-badge badge-purple">✍️ Actualités</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-3 md:mb-4" style="color: #5b21b6;">Blog & Actualités</h2>
                <p class="text-purple-700 text-base md:text-lg">Retrouvez nos conseils, actualités et études de cas</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 lg:gap-10">
                @forelse($articles as $article)
                <div class="blog-card">
                    @if($article->image_une)<img src="{{ asset('storage/'.e($article->image_une)) }}" alt="{{ e($article->titre) }}">@else<div class="h-48 bg-purple-100 flex items-center justify-center"><i class="fas fa-newspaper text-4xl text-purple-300"></i></div>@endif
                    <div class="p-5">
                        <div class="mb-2"><span class="text-xs font-semibold text-purple-600">{{ $article->type=='blog' ? '📝 Blog' : ($article->type=='reussite' ? '🏆 Réussite' : '🤝 Partenariat') }}</span></div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ e($article->titre) }}</h3>
                        <p class="text-gray-500 text-sm mb-3"><i class="far fa-calendar-alt mr-1"></i> {{ $article->date_publication->format('d/m/Y') }}</p>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ e(Str::limit($article->extrait ?? $article->contenu, 100)) }}</p>
                        <a href="{{ route('blog.show', $article->slug) }}" class="inline-flex items-center gap-2 text-purple-700 font-semibold hover:text-purple-800">Lire la suite <i class="fas fa-arrow-right text-sm"></i></a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 md:py-20"><p class="text-purple-400 text-base md:text-xl">Aucun article publié pour le moment.</p></div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FOOTER FUSIONNÉ (NOTRE VERSION) -->
    <footer id="contact" class="scroll-mt-header">
        <div class="bg-rose-500 text-white pt-12">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row items-center gap-12 pb-12">
                    <div class="lg:w-1/2 flex justify-center">
                        <img src="{{ asset('images/dame.png') }}" alt="Silhouette" class="w-full max-w-md h-auto object-contain">
                    </div>
                    <div class="lg:w-1/2 w-full">
                        <h3 class="text-3xl md:text-4xl font-bold mb-6 text-center lg:text-left">Nous contacter</h3>
                        <form id="footerContactForm" novalidate>
                            @csrf
                            <div class="mb-4"><input type="text" name="nom" id="footer_nom" placeholder="Nom complet" autocomplete="name" class="w-full px-5 py-3 text-base rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" required maxlength="150"></div>
                            <div class="mb-4"><input type="email" name="email" id="footer_email" placeholder="Email" autocomplete="email" class="w-full px-5 py-3 text-base rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" required maxlength="150"></div>
                            <div class="mb-4"><input type="tel" name="telephone" id="footer_telephone" placeholder="Téléphone" autocomplete="tel" class="w-full px-5 py-3 text-base rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" maxlength="30"></div>
                            <div class="mb-5"><textarea name="message" id="footer_message" rows="3" placeholder="Votre message..." class="w-full px-5 py-3 text-base rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" required maxlength="2000"></textarea></div>
                            <button type="submit" id="footerSubmitBtn" class="w-full bg-yellow-500 hover:bg-yellow-600 text-rose-800 font-bold py-3 rounded-xl transition text-lg">Envoyer le message</button>
                        </form>
                        <div id="footerContactSuccess" class="hidden mt-3 p-2 bg-green-800/30 text-green-200 text-sm text-center rounded-xl"></div>
                        <div id="footerContactError" class="hidden mt-3 p-2 bg-red-800/30 text-red-200 text-sm text-center rounded-xl"></div>
                        <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                            <a href="https://www.facebook.com/ToutHelp" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 bg-[#1877F2] hover:bg-[#0e63cf] px-5 py-3 rounded-full transition"><i class="fab fa-facebook-f text-xl"></i><span class="font-semibold">Tout help</span></a>
                            @php $contactEmail = \App\Models\Setting::get('contact_email', 'contact@touthelp.com'); @endphp
                            <div class="flex items-center gap-3 bg-white/20 px-5 py-3 rounded-full"><i class="fas fa-envelope text-xl text-yellow-300"></i><span>{{ e($contactEmail) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-800 text-gray-300 py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div><div class="flex items-center gap-2 mb-4"><img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Logo" class="h-10"><span class="text-xl font-bold text-white">TOUT HELP</span></div><p class="text-gray-400 text-sm leading-relaxed">Expert en solutions RH et accompagnement sur-mesure à Madagascar.</p></div>
                    <div><h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Navigation</h4><ul class="space-y-2 text-sm"><li><a href="#accueil" class="text-gray-400 hover:text-white transition">Accueil</a></li><li><a href="#apropos" class="text-gray-400 hover:text-white transition">À propos</a></li><li><a href="#expertise" class="text-gray-400 hover:text-white transition">Expertise</a></li><li><a href="#catalogue" class="text-gray-400 hover:text-white transition">Catalogue</a></li><li><a href="#blog" class="text-gray-400 hover:text-white transition">Blog</a></li></ul></div>
                    <div><h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Services</h4><ul class="space-y-2 text-sm"><li class="text-gray-400">Formations inter-entreprises</li><li class="text-gray-400">Formations intra-entreprise</li><li class="text-gray-400">Accompagnement & Audit</li><li class="text-gray-400">Conseil RH</li></ul></div>
                    <div><h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Légal</h4><ul class="space-y-2 text-sm"><li><a href="#" class="text-gray-400 hover:text-white transition">Mentions légales</a></li><li><a href="#" class="text-gray-400 hover:text-white transition">Politique de confidentialité</a></li><li><a href="#" class="text-gray-400 hover:text-white transition">CGV</a></li></ul></div>
                </div>
                <div class="border-t border-gray-700 pt-6 mt-8 text-center"><p class="text-gray-500 text-xs">&copy; {{ date('Y') }} Tout Help. Tous droits réservés.</p></div>
            </div>
        </div>
    </footer>

    <!-- ROBOT FLOTTANT -->
    <div class="robot-icon" id="robotIcon"><i class="fas fa-robot"></i><span id="robotBadge" class="robot-badge" style="display:none;">0</span></div>

    <!-- MODALE CHAT -->
    <div class="chat-modal" id="chatModal">
        <div class="chat-header"><div class="chat-header-left"><div class="chat-header-avatar">🤖</div><div><div class="chat-header-name">Support Tout Help</div><div class="chat-header-status"><span class="chat-status-dot"></span> En ligne</div></div></div><button class="chat-close-btn" onclick="closeChatModal()">✕</button></div>
        <div class="chat-body" id="chatBody"><div class="chat-messages-area" id="chatMessagesArea"></div></div>
        <div id="chatInputArea" class="chat-input-area" style="display:none;"><textarea id="chatTextarea" class="chat-textarea" rows="1" placeholder="Écrivez votre message..." maxlength="1000"></textarea><button id="chatSendBtn" class="chat-send-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M22 2 11 13M22 2 15 22l-4-9-9-4 20-7z"/></svg></button></div>
        <div id="chatInitForm" class="chat-init-form"><div style="text-align:center;margin-bottom:12px;"><p style="font-size:13px;color:#e63946;">Bonjour ! 👋 Pour commencer, présentez-vous :</p></div><input type="text" id="initNom" placeholder="Votre nom complet *" maxlength="150" autocomplete="name"><input type="email" id="initEmail" placeholder="Votre email *" maxlength="150" autocomplete="email"><input type="tel" id="initTel" placeholder="Votre téléphone (optionnel)" maxlength="30" autocomplete="tel"><textarea id="initMessage" rows="2" placeholder="Votre message *" style="resize:none;" maxlength="1000"></textarea><button class="chat-init-btn" id="initSendBtn" onclick="submitInitForm()"><i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation</button></div>
        <div id="changeIdentityBar" style="display:none;background:white;border-top:1px solid #ffe0e0;"><button class="change-identity-btn" onclick="resetChat()"><i class="fas fa-user-edit mr-1"></i> Nouvelle conversation</button></div>
        <div class="chat-footer">Réponse dans les plus brefs délais · Tout Help</div>
    </div>

    <!-- MODALE CATALOGUE -->
    <div id="syllabusModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4"><div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"><div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center"><h3 id="modalTitle" class="text-2xl font-bold text-gray-800">Détail du syllabus</h3><button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button></div><div id="modalContent" class="p-6"></div></div></div>

    @vite(['resources/js/app.js'])
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
    (function() {
        "use strict";
        
        let currentEmail = '';
        let currentNom = '';
        let unreadCount = 0;
        let audioCtx = null;
        let echoListenerSet = false;
        let pollInterval = null;
        let isLoading = false;
        let isSending = false;
        let lastMessageId = null;
        let lastRefreshTime = 0;
        let lastMessagesHash = '';
        let soundEnabled = true;
        let audioEnabled = false;
        let pendingSounds = [];
        const rateLimits = new Map();
        
        const MAX_MESSAGE_LENGTH = 1000;
        const MAX_NAME_LENGTH = 150;
        const MAX_EMAIL_LENGTH = 150;
        const MAX_PHONE_LENGTH = 30;
        
        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            const div = document.createElement('div');
            div.textContent = String(str);
            return div.innerHTML;
        }
        
        function isValidEmail(email) {
            if (!email || typeof email !== 'string') return false;
            const trimmed = email.trim();
            if (trimmed.length > MAX_EMAIL_LENGTH) return false;
            const emailRegex = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]{0,63}@[a-zA-Z0-9][a-zA-Z0-9.-]{0,252}\.[a-zA-Z]{2,}$/;
            return emailRegex.test(trimmed);
        }
        
        function isValidName(name) {
            if (!name || typeof name !== 'string') return false;
            const trimmed = name.trim();
            if (trimmed.length > MAX_NAME_LENGTH) return false;
            if (trimmed.length < 2) return false;
            const nameRegex = /^[a-zA-ZÀ-ÿ\s'\-]{2,150}$/;
            return nameRegex.test(trimmed);
        }
        
        function isValidPhone(phone) {
            if (!phone) return true;
            const trimmed = phone.trim();
            if (trimmed.length > MAX_PHONE_LENGTH) return false;
            const phoneRegex = /^[\d\s+\-().]{1,30}$/;
            return phoneRegex.test(trimmed);
        }
        
        function isValidMessage(msg) {
            if (!msg || typeof msg !== 'string') return false;
            const trimmed = msg.trim();
            if (trimmed.length < 2) return false;
            if (trimmed.length > MAX_MESSAGE_LENGTH) return false;
            if (/<[^>]*>/.test(trimmed)) return false;
            if (/[\x00-\x08\x0B\x0C\x0E-\x1F]/.test(trimmed)) return false;
            return true;
        }
        
        function sanitize(str, max = 1000) {
            if (!str) return '';
            let cleaned = String(str);
            cleaned = cleaned.replace(/<[^>]*>/g, '');
            cleaned = cleaned.substring(0, max);
            return cleaned.trim();
        }
        
        const RATE_LIMIT_DELAY = 5000;
        const MAX_REQUESTS_PER_MINUTE = 12;
        const requestTimestamps = [];
        
        function isRateLimited(email) {
            const now = Date.now();
            while (requestTimestamps.length > 0 && requestTimestamps[0] < now - 60000) requestTimestamps.shift();
            if (requestTimestamps.length >= MAX_REQUESTS_PER_MINUTE) { flashError('Trop de tentatives. Veuillez patienter une minute.'); return true; }
            const last = rateLimits.get(email) || 0;
            if (now - last < RATE_LIMIT_DELAY) { flashError('Merci de patienter quelques secondes avant de renvoyer.'); return true; }
            rateLimits.set(email, now);
            requestTimestamps.push(now);
            return false;
        }
        
        function checkRateLimit(email) { return !isRateLimited(email); }
        
        function initAudio() {
            if (!audioCtx) { try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch(e) {} }
        }
        
        function enableAudio() {
            if (audioEnabled) return;
            initAudio();
            if (audioCtx && audioCtx.state === 'suspended') {
                audioCtx.resume().then(() => { audioEnabled = true; pendingSounds.forEach(() => playNotificationSound()); pendingSounds = []; }).catch(e => {});
            } else if (audioCtx && audioCtx.state === 'running') { audioEnabled = true; }
        }
        
        function playNotificationSound() {
            if (!soundEnabled) return;
            if (!audioEnabled) { pendingSounds.push(true); return; }
            try {
                initAudio();
                if (!audioCtx || audioCtx.state !== 'running') return;
                const now = audioCtx.currentTime;
                const o1 = audioCtx.createOscillator(), g1 = audioCtx.createGain();
                o1.connect(g1); g1.connect(audioCtx.destination);
                o1.type = 'sine'; o1.frequency.value = 880;
                g1.gain.setValueAtTime(0.2, now);
                g1.gain.exponentialRampToValueAtTime(0.00001, now + 0.25);
                o1.start(now); o1.stop(now + 0.25);
                setTimeout(() => {
                    if (audioCtx && audioCtx.state === 'running') {
                        const o2 = audioCtx.createOscillator(), g2 = audioCtx.createGain();
                        o2.connect(g2); g2.connect(audioCtx.destination);
                        o2.type = 'sine'; o2.frequency.value = 660;
                        g2.gain.setValueAtTime(0.15, audioCtx.currentTime);
                        g2.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + 0.2);
                        o2.start(); o2.stop(audioCtx.currentTime + 0.2);
                    }
                }, 120);
            } catch(e) {}
        }
        
        document.addEventListener('click', enableAudio);
        document.getElementById('robotIcon')?.addEventListener('click', enableAudio);
        
        function updateBadge() {
            const b = document.getElementById('robotBadge');
            if (!b) return;
            if (unreadCount > 0) { b.textContent = unreadCount > 99 ? '99+' : unreadCount; b.style.display = 'flex'; b.style.animation = 'none'; b.offsetHeight; b.style.animation = 'badgePulse 0.6s ease-in-out'; }
            else { b.style.display = 'none'; }
        }
        
        function showRobotNotification() {
            const robot = document.getElementById('robotIcon');
            if (!robot) return;
            robot.classList.add('robot-notification');
            setTimeout(() => robot.classList.remove('robot-notification'), 1000);
        }
        
        function openChatModal() {
            const modal = document.getElementById('chatModal');
            if (!modal) return;
            modal.classList.add('active');
            unreadCount = 0;
            updateBadge();
            if (currentEmail) { loadMessages(true); startPolling(); }
            scrollChatToBottom();
        }
        
        function closeChatModal() {
            const modal = document.getElementById('chatModal');
            if (modal) modal.classList.remove('active');
            stopPolling();
        }
        
        const robotIcon = document.getElementById('robotIcon');
        if (robotIcon) robotIcon.addEventListener('click', openChatModal);
        
        function scrollChatToBottom() {
            setTimeout(() => { const b = document.getElementById('chatBody'); if (b) b.scrollTop = b.scrollHeight; }, 100);
        }
        
        function startPolling() {
            stopPolling();
            if (!currentEmail) return;
            pollInterval = setInterval(() => {
                if (currentEmail && document.getElementById('chatModal') && document.getElementById('chatModal').classList.contains('active')) loadMessages(false);
            }, 6000);
        }
        
        function stopPolling() {
            if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
        }
        
        function generateMessagesHash(messages) {
            if (!messages || messages.length === 0) return '';
            const lastMsg = messages[messages.length - 1];
            return `${lastMsg?.id || ''}-${lastMsg?.updated_at || ''}-${messages.length}`;
        }
        
        function renderMessages(messages) {
            const area = document.getElementById('chatMessagesArea');
            if (!area) return;
            if (!messages || messages.length === 0) { area.innerHTML = '<div class="pending-tag">⏳ En attente de réponse...</div>'; return; }
            let html = '';
            for (let i = 0; i < messages.length; i++) {
                const m = messages[i];
                if (m.message && m.message.trim() !== '') {
                    html += `<div class="bubble-sent"><div class="bubble-sent-inner"><div class="bubble-text">${escapeHtml(m.message)}</div><div class="bubble-time">${formatTime(m.created_at)}</div></div></div>`;
                }
                if (m.reponse_admin && m.reponse_admin.trim() !== '') {
                    html += `<div class="bubble-received"><div class="bubble-received-avatar">TH</div><div class="bubble-received-inner"><div class="bubble-text">${escapeHtml(m.reponse_admin)}</div><div class="bubble-time-left">${formatTime(m.updated_at)}</div></div></div>`;
                }
            }
            area.innerHTML = html;
            scrollChatToBottom();
        }
        
        function formatTime(d) {
            if (!d) return '';
            try { return new Date(d).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); } catch(e) { return ''; }
        }
        
        async function loadMessages(force = false) {
            if (!currentEmail) return;
            if (isLoading) return;
            const now = Date.now();
            if (now - lastRefreshTime < 500 && !force) return;
            lastRefreshTime = now;
            isLoading = true;
            try {
                const encodedEmail = encodeURIComponent(currentEmail);
                const url = `/api/messages?email=${encodedEmail}&_=${now}`;
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, cache: 'no-store' });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const msgs = await res.json();
                const messages = Array.isArray(msgs) ? msgs : [];
                const currentHash = generateMessagesHash(messages);
                const isNewContent = currentHash !== lastMessagesHash;
                if (isNewContent || force) {
                    lastMessagesHash = currentHash;
                    if (messages.length > 0) lastMessageId = messages[messages.length - 1]?.id;
                    const isChatOpen = document.getElementById('chatModal') && document.getElementById('chatModal').classList.contains('active');
                    if (!isChatOpen && isNewContent && !force) { unreadCount++; updateBadge(); showRobotNotification(); playNotificationSound(); }
                    renderMessages(messages);
                    if (isChatOpen) scrollChatToBottom();
                }
            } catch(e) { console.warn('[Chat] loadMessages error:', e.message); }
            finally { isLoading = false; }
        }
        
        async function sendMessageAPI(nom, email, telephone, message) {
            if (!isValidName(nom)) return { success: false, message: 'Nom invalide.' };
            if (!isValidEmail(email)) return { success: false, message: 'Email invalide.' };
            if (!isValidPhone(telephone)) return { success: false, message: 'Téléphone invalide.' };
            if (!isValidMessage(message)) return { success: false, message: 'Message invalide.' };
            if (!checkRateLimit(email)) return { success: false, message: '' };
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrf) return { success: false, message: 'Erreur de sécurité.' };
            const cleanNom = sanitize(nom, MAX_NAME_LENGTH);
            const cleanEmail = email.trim().substring(0, MAX_EMAIL_LENGTH);
            const cleanTel = sanitize(telephone, MAX_PHONE_LENGTH);
            const cleanMsg = sanitize(message, MAX_MESSAGE_LENGTH);
            try {
                const res = await fetch('/contact/send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: JSON.stringify({ nom: cleanNom, email: cleanEmail, telephone: cleanTel, message: cleanMsg })
                });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return await res.json();
            } catch(e) { return { success: false, message: 'Erreur réseau.' }; }
        }
        
        async function submitInitForm() {
            if (isSending) return;
            const nom = document.getElementById('initNom').value.trim();
            const email = document.getElementById('initEmail').value.trim();
            const tel = document.getElementById('initTel').value.trim();
            const msg = document.getElementById('initMessage').value.trim();
            if (!nom || !email || !msg) { flashError('Merci de remplir tous les champs obligatoires.'); return; }
            const btn = document.getElementById('initSendBtn');
            if (!btn) return;
            isSending = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Envoi...';
            btn.disabled = true;
            const result = await sendMessageAPI(nom, email, tel, msg);
            if (result.success) {
                currentEmail = email.trim().substring(0, MAX_EMAIL_LENGTH);
                currentNom = nom.trim().substring(0, MAX_NAME_LENGTH);
                document.getElementById('chatInitForm').style.display = 'none';
                document.getElementById('chatInputArea').style.display = 'flex';
                document.getElementById('changeIdentityBar').style.display = 'block';
                await new Promise(r => setTimeout(r, 500));
                lastMessagesHash = '';
                await loadMessages(true);
                startPolling();
                setupEchoListener();
            } else if (result.message) { flashError(result.message); }
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation';
            btn.disabled = false;
            isSending = false;
        }
        
        async function sendQuickMessage() {
            if (isSending) return;
            const ta = document.getElementById('chatTextarea');
            if (!ta) return;
            const msg = ta.value.trim();
            if (!msg || !currentEmail) return;
            const btn = document.getElementById('chatSendBtn');
            if (!btn) return;
            isSending = true;
            btn.disabled = true;
            ta.value = '';
            ta.style.height = 'auto';
            const result = await sendMessageAPI(currentNom, currentEmail, '', msg);
            if (result.success) { lastMessagesHash = ''; await loadMessages(true); }
            else if (result.message) { flashError(result.message); }
            btn.disabled = false;
            isSending = false;
        }
        
        function resetChat() {
            currentEmail = ''; currentNom = ''; lastMessageId = null; lastMessagesHash = ''; unreadCount = 0;
            updateBadge(); stopPolling(); echoListenerSet = false;
            document.getElementById('chatInitForm').style.display = 'block';
            document.getElementById('chatInputArea').style.display = 'none';
            document.getElementById('changeIdentityBar').style.display = 'none';
            const area = document.getElementById('chatMessagesArea');
            if (area) area.innerHTML = '';
            ['initNom','initEmail','initTel','initMessage'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
            const btn = document.getElementById('initSendBtn');
            if (btn) { btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation'; btn.disabled = false; }
            isSending = false;
        }
        
        let wsRetryCount = 0;
        function setupEchoListener() {
            if (echoListenerSet) return;
            function trySetup() {
                if (window.Echo) {
                    if (window.echoChannel) window.Echo.leaveChannel('new-messages');
                    window.echoChannel = window.Echo.channel('new-messages');
                    window.echoChannel.listen('NewMessageReceived', (event) => {
                        if (currentEmail && currentEmail === event.email_client) {
                            lastMessagesHash = '';
                            loadMessages(true);
                            if (document.getElementById('chatModal').classList.contains('active')) { playNotificationSound(); }
                            else { unreadCount++; updateBadge(); showRobotNotification(); playNotificationSound(); }
                        }
                    });
                    echoListenerSet = true;
                } else { wsRetryCount++; if (wsRetryCount < 30) setTimeout(trySetup, 500); }
            }
            trySetup();
        }
        setupEchoListener();
        
        document.getElementById('footerContactForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (isSending) return;
            const nom = document.getElementById('footer_nom').value.trim();
            const email = document.getElementById('footer_email').value.trim();
            const tel = document.getElementById('footer_telephone').value.trim();
            const msg = document.getElementById('footer_message').value.trim();
            if (!nom || !email || !msg) return;
            const btn = document.getElementById('footerSubmitBtn');
            const originalText = btn.innerHTML;
            isSending = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Envoi en cours...';
            btn.disabled = true;
            const successDiv = document.getElementById('footerContactSuccess');
            const errorDiv = document.getElementById('footerContactError');
            const result = await sendMessageAPI(nom, email, tel, msg);
            if (result.success) {
                currentEmail = email.trim().substring(0, MAX_EMAIL_LENGTH);
                currentNom = nom.trim().substring(0, MAX_NAME_LENGTH);
                document.getElementById('footerContactForm').reset();
                document.getElementById('chatInitForm').style.display = 'none';
                document.getElementById('chatInputArea').style.display = 'flex';
                document.getElementById('changeIdentityBar').style.display = 'block';
                openChatModal();
                lastMessagesHash = '';
                await loadMessages(true);
                startPolling();
                successDiv.textContent = 'Message envoyé avec succès !';
                successDiv.classList.remove('hidden');
                setTimeout(() => successDiv.classList.add('hidden'), 5000);
            } else {
                errorDiv.textContent = result.message || 'Erreur lors de l\'envoi.';
                errorDiv.classList.remove('hidden');
                setTimeout(() => errorDiv.classList.add('hidden'), 5000);
            }
            btn.innerHTML = originalText;
            btn.disabled = false;
            isSending = false;
        });
        
        function openModal(catalogueId) {
            const safeId = parseInt(catalogueId, 10);
            if (!safeId || safeId <= 0) return;
            const modal = document.getElementById('syllabusModal');
            const contentDiv = document.getElementById('modalContent');
            const titleSpan = document.getElementById('modalTitle');
            if (!modal || !contentDiv || !titleSpan) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            contentDiv.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-green-700"></i><p class="mt-2">Chargement...</p></div>';
            fetch(`/api/catalogue/${safeId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
                .then(data => {
                    contentDiv.innerHTML = '';
                    if (data.image) { const img = document.createElement('img'); img.src = data.image_url; img.className = 'w-full rounded-lg mb-6'; img.alt = escapeHtml(data.titre || ''); contentDiv.appendChild(img); }
                    [{ label: '📘 Description', val: data.description }, { label: '🎯 Objectifs', val: data.objectifs }, { label: '👥 Public visé', val: data.public_vise }, { label: '📚 Programme détaillé', val: data.programme }].forEach(({ label, val }) => {
                        const h = document.createElement('h2'); h.className = 'text-xl font-semibold text-green-700 mt-4 mb-2'; h.textContent = label;
                        const p = document.createElement('div'); p.className = 'text-gray-700'; p.innerHTML = escapeHtml(val || 'Non renseigné').replace(/\n/g, '<br>');
                        contentDiv.append(h, p);
                    });
                    const actions = document.createElement('div');
                    actions.className = 'mt-6 flex flex-wrap gap-4';
                    if (data.fichier_pdf) { const dl = document.createElement('a'); dl.href = data.fichier_url; dl.target = '_blank'; dl.rel = 'noopener noreferrer'; dl.className = 'bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2'; dl.innerHTML = '<i class="fas fa-download"></i>'; const sp = document.createElement('span'); sp.textContent = 'Télécharger le syllabus'; dl.appendChild(sp); actions.appendChild(dl); }
                    const devis = document.createElement('a'); devis.href = '#contact'; devis.className = 'bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2'; devis.onclick = () => closeModal(); devis.innerHTML = '<i class="fas fa-file-invoice-dollar"></i>'; const sp2 = document.createElement('span'); sp2.textContent = 'Demander un devis'; devis.appendChild(sp2); actions.appendChild(devis);
                    contentDiv.appendChild(actions);
                    titleSpan.textContent = escapeHtml(data.titre || 'Syllabus');
                })
                .catch(() => { contentDiv.innerHTML = '<div class="text-red-600 text-center py-8">Erreur de chargement. Réessayez.</div>'; });
        }
        
        function closeModal() { const m = document.getElementById('syllabusModal'); if (m) { m.classList.add('hidden'); m.classList.remove('flex'); } }
        function scrollPartenaire(dir) { const c = document.getElementById('partenairesScroll'); if (c) c.scrollBy({ left: dir === 'left' ? -300 : 300, behavior: 'smooth' }); }
        function scrollAvis(dir) { const c = document.getElementById('avisScroll'); if (c) c.scrollBy({ left: dir === 'left' ? -350 : 350, behavior: 'smooth' }); }
        
        function flashError(msg) {
            if (!msg) return;
            let el = document.getElementById('chatFlashError');
            if (!el) { el = document.createElement('div'); el.id = 'chatFlashError'; el.style.cssText = 'background:#fee2e2;color:#b91c1c;padding:8px 12px;border-radius:8px;font-size:12px;margin:0 0 8px;text-align:center;'; const form = document.getElementById('chatInitForm'); if (form) form.prepend(el); }
            el.textContent = escapeHtml(String(msg).substring(0, 200));
            el.style.display = 'block';
            setTimeout(() => { if (el) el.style.display = 'none'; }, 5000);
        }
        
        window.submitInitForm = submitInitForm;
        window.sendQuickMessage = sendQuickMessage;
        window.closeChatModal = closeChatModal;
        window.resetChat = resetChat;
        window.openModal = openModal;
        window.closeModal = closeModal;
        window.scrollPartenaire = scrollPartenaire;
        window.scrollAvis = scrollAvis;
        
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('chatSendBtn')?.addEventListener('click', sendQuickMessage);
            document.getElementById('chatTextarea')?.addEventListener('keypress', (e) => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendQuickMessage(); } });
            document.getElementById('chatTextarea')?.addEventListener('input', function() { this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 100) + 'px'; });
        });
    })();
</script>
</body>
</html>
