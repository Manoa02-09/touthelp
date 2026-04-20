<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tout Help - Accueil</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Politique de sécurité du contenu (CSP) améliorée -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://js.pusher.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; connect-src 'self' ws://localhost:8080 wss://localhost:8080 http://127.0.0.1:8080; frame-ancestors 'none';">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* ===== CHAT MODAL (style rose/rouge dégradé) ===== */
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
        .chat-header-avatar {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }
        .chat-header-name { font-weight: 700; font-size: 14px; }
        .chat-header-status { font-size: 11px; opacity: 0.8; display: flex; align-items: center; gap: 4px; }
        .chat-status-dot { width: 6px; height: 6px; background: #4ade80; border-radius: 50%; display: inline-block; }
        .chat-close-btn {
            background: rgba(255,255,255,0.15);
            border: none; color: white;
            width: 28px; height: 28px;
            border-radius: 50%; cursor: pointer;
            font-size: 14px;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.2s;
        }
        .chat-close-btn:hover { background: rgba(255,255,255,0.3); }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            background-color: #fef9f9;
            background-image: radial-gradient(circle at 10px 10px, rgba(230,57,70,0.03) 1px, transparent 1px);
            background-size: 20px 20px;
            max-height: 380px;
        }
        .chat-messages-area { padding: 14px; display: flex; flex-direction: column; gap: 8px; min-height: 100px; }

        .chat-input-area {
            background: white;
            border-top: 1px solid #ffe0e0;
            padding: 10px 12px;
            display: flex;
            gap: 8px;
            align-items: flex-end;
            flex-shrink: 0;
        }
        .chat-textarea {
            flex: 1;
            border: 1px solid #ffe0e0;
            border-radius: 20px;
            padding: 9px 14px;
            font-size: 13px;
            resize: none;
            outline: none;
            max-height: 100px;
            line-height: 1.4;
            transition: border-color 0.2s;
        }
        .chat-textarea:focus { border-color: #e63946; }
        .chat-send-btn {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            border: none; border-radius: 50%;
            color: white; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: transform 0.15s, opacity 0.15s;
        }
        .chat-send-btn:hover { transform: scale(1.05); }
        .chat-send-btn:active { transform: scale(0.95); }
        .chat-send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .chat-init-form { padding: 16px; }
        .chat-init-form input, .chat-init-form textarea {
            width: 100%;
            padding: 9px 12px;
            margin-bottom: 10px;
            border: 1px solid #ffe0e0;
            border-radius: 10px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }
        .chat-init-form input:focus, .chat-init-form textarea:focus { border-color: #e63946; }
        .chat-init-btn {
            width: 100%;
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            color: white; border: none;
            padding: 10px; border-radius: 10px;
            font-weight: 600; font-size: 14px;
            cursor: pointer; transition: opacity 0.2s;
        }
        .chat-init-btn:hover { opacity: 0.9; }
        .chat-init-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .bubble-sent { display: flex; justify-content: flex-end; }
        .bubble-sent-inner {
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            color: white;
            border-radius: 18px 18px 4px 18px;
            padding: 9px 13px;
            max-width: 75%;
            box-shadow: 0 2px 6px rgba(230,57,70,0.25);
        }
        .bubble-received { display: flex; justify-content: flex-start; align-items: flex-end; gap: 8px; }
        .bubble-received-avatar {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; color: white; font-weight: 600;
            flex-shrink: 0;
        }
        .bubble-received-inner {
            background: white;
            color: #1f2937;
            border-radius: 18px 18px 18px 4px;
            padding: 9px 13px;
            max-width: 75%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.07);
        }
        .bubble-text { font-size: 13px; line-height: 1.45; word-break: break-word; }
        .bubble-time { font-size: 10px; margin-top: 3px; text-align: right; opacity: 0.65; }
        .bubble-time-left { font-size: 10px; margin-top: 3px; opacity: 0.55; }

        .pending-tag {
            text-align: center;
            font-size: 11px;
            color: #e63946;
            background: rgba(255,255,255,0.9);
            border-radius: 20px;
            padding: 4px 12px;
            margin: 6px auto;
            width: fit-content;
            border: 1px solid #ffe0e0;
        }

        .change-identity-btn {
            font-size: 11px; color: #e63946;
            background: none; border: none;
            cursor: pointer; text-align: center;
            width: 100%; padding: 6px;
            display: block;
            transition: color 0.15s;
        }
        .change-identity-btn:hover { color: #c1121f; }

        .robot-icon {
            position: fixed; bottom: 20px; right: 20px;
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            width: 60px; height: 60px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(230,57,70,0.4);
            transition: all 0.3s ease; z-index: 9999;
        }
        .robot-icon:hover { transform: scale(1.1); }
        .robot-icon i { font-size: 28px; color: white; }
        .robot-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: white;
            font-size: 11px; font-weight: bold;
            min-width: 20px; height: 20px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px; border: 2px solid white;
        }
        .chat-footer {
            background: white;
            padding: 6px;
            text-align: center;
            font-size: 11px;
            color: #e63946;
            border-top: 1px solid #ffe0e0;
            flex-shrink: 0;
        }

        /* Styles existants du site (responsives) */
        .hero-gradient { background: linear-gradient(135deg, #0a2e25 0%, #1a5c4a 100%); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-4 { display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
        .modal-expertise { transition: opacity 0.3s ease; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .chat-message-anim { animation: messageAppear 0.2s ease-out; }
        @keyframes messageAppear { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .container-fluid { width: 100%; padding-right: 2rem; padding-left: 2rem; margin-right: auto; margin-left: auto; }
        @media (min-width: 768px) { .container-fluid { padding-right: 4rem; padding-left: 4rem; } }
        @media (min-width: 1024px) { .container-fluid { padding-right: 6rem; padding-left: 6rem; } }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #e63946; border-radius: 10px; }
    </style>
</head>
<body class="bg-white">

    <!-- ===== EN-TÊTE ===== -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Logo" class="h-14">
                <span class="text-2xl font-bold text-green-900">TOUT HELP</span>
            </div>
            <nav class="hidden md:flex space-x-10">
                <a href="#accueil" class="text-gray-700 hover:text-green-800 font-medium text-lg">ACCUEIL</a>
                <a href="#expertise" class="text-gray-700 hover:text-green-800 font-medium text-lg">EXPERTISE</a>
                <a href="#catalogue" class="text-gray-700 hover:text-green-800 font-medium text-lg">CATALOGUE</a>
                <a href="#blog" class="text-gray-700 hover:text-green-800 font-medium text-lg">BLOG</a>
                <a href="/apropos" class="text-gray-700 hover:text-green-800 font-medium">À PROPOS</a>
                <a href="#contact" class="text-gray-700 hover:text-green-800 font-medium text-lg">CONTACT</a>
            </nav>
        </div>
    </header>

    <!-- ===== SECTION ACCUEIL (agrandie, responsive) ===== -->
    <section id="accueil" class="relative bg-white py-24 md:py-32 overflow-hidden min-h-screen flex items-center scroll-mt-24">
        <div class="container mx-auto px-6 flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/2 z-10 text-center lg:text-left">
                <div class="inline-flex items-center space-x-2 bg-gray-100 px-5 py-2 rounded-full text-xs font-bold text-gray-500 mb-10 tracking-widest uppercase mx-auto lg:mx-0">
                    <i class="fas fa-shield-alt text-blue-500"></i>
                    <span>Formation • Accompagnement • Audit</span>
                </div>
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-[#0f2439] leading-[1.1] md:leading-[0.9] mb-6">
                    ENSEMBLE<br>
                    FAISONS DE LA<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-300">PERFORMANCE</span><br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-500 to-orange-400">UNE CULTURE</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 max-w-xl mx-auto lg:mx-0 mb-12 border-l-4 border-orange-500 pl-6">
                    Expert en <span class="text-blue-600 font-bold">Solutions RH</span> et <span class="text-blue-600 font-bold">Accompagnement sur-mesure</span> à Madagascar.
                </p>
            </div>
            <div class="lg:w-1/2 relative mt-16 lg:mt-0">
                <div class="relative z-10">
                    <img src="{{ asset('images/accueil.png') }}" alt="Expertise Tout Help" class="w-full h-auto max-h-[500px] lg:max-h-full object-contain">
                </div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-blue-50 rounded-full filter blur-3xl opacity-50 -z-10"></div>
            </div>
        </div>
    </section>

    <!-- ===== SECTION EXPERTISE (agrandie, égalisée) ===== -->
    <section id="expertise" class="py-24 md:py-32 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-4">EXPERTISE SUR-MESURE</h2>
                <p class="text-gray-500 text-base uppercase tracking-wide">Des solutions adaptées à chaque besoin</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach(range(1,3) as $i) @php $c = ['inter','intra','accompagnement']; $type=$c[$i-1]; @endphp
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition border border-gray-100 flex flex-col h-full">
                    <div class="p-8 text-center flex flex-col flex-1">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 {{ $i==1?'bg-blue-100':($i==2?'bg-green-100':'bg-purple-100') }}">
                            <i class="fas {{ $i==1?'fa-users':($i==2?'fa-building':'fa-clipboard-list') }} text-3xl {{ $i==1?'text-blue-700':($i==2?'text-green-700':'text-purple-700') }}"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $i==1?'FORMATIONS INTER-ENTREPRISES':($i==2?'FORMATIONS INTRA-ENTREPRISE':'ACCOMPAGNEMENT & AUDIT') }}</h3>
                        <p class="text-sm text-gray-500 uppercase mb-4">{{ $i==1?'GRAND PUBLIC / SESSION PUBLIQUE':($i==2?'SUR-MESURE / PRIVÉ':'STRUCTURER – ÉVALUER – PROGRESSER') }}</p>
                        <p class="text-gray-600 text-base flex-1">{{ $i==1?'Sessions ouvertes à tous, favorisant le partage d’expériences entre professionnels de différents horizons.':($i==2?'Formations personnalisées adaptées à la culture et aux besoins spécifiques de votre organisation.':'De la mise en place de vos systèmes à l’évaluation de vos pratiques, une approche claire et progressive.') }}</p>
                        <button onclick="openExpertiseModal('{{ $type }}')" class="mt-6 inline-block {{ $i==1?'bg-blue-700 hover:bg-blue-800':($i==2?'bg-green-700 hover:bg-green-800':'bg-purple-700 hover:bg-purple-800') }} text-white font-semibold py-3 px-6 rounded-xl transition text-base self-center">En savoir plus</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== MODALES EXPERTISE (conservées) ===== -->
    <div id="modalInter" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4 modal-expertise">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">Formations inter-entreprises</h3>
                <button onclick="closeExpertiseModal('inter')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <div class="prose max-w-none">
                    <p>Nos formations interentreprises sont conçues pour permettre à vos collaborateurs de développer leurs compétences dans un cadre structuré, tout en bénéficiant d’échanges avec des professionnels issus de différents horizons.</p>
                    <p>Elles offrent un environnement d’apprentissage dynamique, propice au partage d’expériences et à l’acquisition de bonnes pratiques directement applicables.</p>
                    <h3 class="text-lg font-bold mt-4">🧩 Ce que nous proposons</h3>
                    <ul class="list-disc pl-6"><li>Sessions publiques ouvertes</li><li>Séminaires thématiques</li><li>Journées de sensibilisation</li><li>Portes ouvertes</li><li>Calendrier de formations planifiées</li></ul>
                    <h3 class="text-lg font-bold mt-4">🎯 Pour qui ?</h3>
                    <p>Ces formations s’adressent aux professionnels souhaitant : renforcer leurs compétences, mettre à jour leurs connaissances, s’initier à de nouvelles pratiques, échanger avec d’autres acteurs du même secteur.</p>
                    <div class="mt-6 flex flex-wrap gap-4"><a href="{{ route('calendrier') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded-lg" onclick="closeExpertiseModal('inter')">📅 Voir le calendrier des formations</a></div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalIntra" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4 modal-expertise">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">Formations intra-entreprise</h3>
                <button onclick="closeExpertiseModal('intra')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <div class="prose max-w-none">
                    <p>Chaque organisation a ses propres réalités, ses contraintes et ses objectifs. Nos formations intra-entreprise sont conçues sur mesure, pour répondre précisément à vos besoins et accompagner vos équipes dans leur montée en compétence.</p>
                    <p>Nous intervenons directement au sein de votre structure, avec des contenus adaptés à votre activité et à votre contexte.</p>
                    <h3 class="text-lg font-bold mt-4">🧩 Ce que nous proposons</h3>
                    <ul class="list-disc pl-6"><li>Formations personnalisées selon vos besoins</li><li>Thématiques pluridisciplinaires et variées</li><li>Adaptation des contenus à votre secteur d’activité</li><li>Cas pratiques basés sur votre environnement réel</li><li>Animation sur site ou en format dédié</li><li>Accompagnement sur des problématiques spécifiques</li></ul>
                    <h3 class="text-lg font-bold mt-4">🎯 Résultat</h3>
                    <p>L’objectif est de proposer des formations utiles, compréhensibles et immédiatement applicables par vos équipes.</p>
                    <div class="mt-6 flex flex-wrap gap-4">
                        <button onclick="openChatFromModal('intra')" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg">💬 Discuter de votre besoin</button>
                        <a href="#catalogue" class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-2 rounded-lg" onclick="closeExpertiseModal('intra')">📚 Parcourir nos catalogues</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalAccompagnement" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4 modal-expertise">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">Accompagnement & Audit</h3>
                <button onclick="closeExpertiseModal('accompagnement')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <div class="prose max-w-none">
                    <p>Structurer son organisation et en évaluer l’efficacité vont de pair. Nous vous accompagnons à chaque étape, de la mise en place de vos systèmes jusqu’à l’évaluation de vos pratiques, avec une approche claire, progressive et adaptée à votre réalité.</p>
                    <div class="mt-6 border-l-4 border-green-600 pl-4"><h3 class="text-xl font-bold text-green-700 flex items-center gap-2"><i class="fas fa-cogs"></i> Accompagnement</h3><p>Nous vous accompagnons dans la mise en place et la structuration de vos systèmes de management.</p><h4 class="font-semibold mt-2">Ce qu'on vous propose :</h4><ul class="list-disc pl-6"><li>Mise en place de systèmes (ISO, HSE, SMSST, RSE…)</li><li>Structuration organisationnelle</li><li>Rédaction de processus et procédures</li><li>Élaboration de documents opérationnels</li><li>Appui et suivi jusqu’à la mise en œuvre</li></ul><div class="bg-green-50 p-3 rounded-lg mt-2"><p class="font-semibold text-green-800">Résultat :</p><p class="text-green-700">Un système clair, structuré et adapté à votre fonctionnement.</p></div></div>
                    <div class="mt-6 border-l-4 border-blue-600 pl-4"><h3 class="text-xl font-bold text-blue-700 flex items-center gap-2"><i class="fas fa-search"></i> Audit</h3><p>Nous réalisons des audits pour vous aider à prendre du recul, identifier les écarts et définir des axes d’amélioration concrets.</p><h4 class="font-semibold mt-2">Types d’audit :</h4><ul class="list-disc pl-6"><li>Audit interne</li><li>Audit à blanc (pré-certification)</li><li>Diagnostic organisationnel</li><li>Évaluation de conformité</li></ul><div class="bg-blue-50 p-3 rounded-lg mt-2"><p class="font-semibold text-blue-800">Résultat :</p><p class="text-blue-700">Une vision claire de votre situation et des actions concrètes pour progresser.</p></div></div>
                    <div class="mt-6 p-4 bg-gray-100 rounded-lg text-center"><p><i class="fas fa-sync-alt text-green-600 mr-2"></i> L’accompagnement et l’audit sont complémentaires.</p></div>
                    <div class="mt-6 flex justify-center">
                        <button onclick="openChatFromModal('accompagnement')" class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded-lg">📋 Demander un devis</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION CATALOGUE (agrandie) ===== -->
    <section id="catalogue" class="py-24 md:py-32 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-4xl mx-auto mb-16">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">Nos catalogues de formation</h2>
                <p class="text-gray-600 text-lg">Découvrez l'ensemble de nos syllabus. Cliquez sur "En savoir plus" pour voir le programme complet.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($catalogues as $catalogue)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition flex flex-col h-full">
                    @if($catalogue->image)<img src="{{ asset('storage/'.$catalogue->image) }}" class="w-full h-64 object-cover" alt="{{ $catalogue->titre }}">@endif
                    <div class="p-8 flex flex-col flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $catalogue->titre }}</h3>
                        <p class="text-gray-600 text-base line-clamp-3 flex-1">{{ Str::limit($catalogue->description, 150) }}</p>
                        <button onclick="openModal({{ $catalogue->id }})" class="mt-6 inline-block bg-green-700 hover:bg-green-800 text-white font-semibold py-3 px-6 rounded-xl transition text-base self-start">En savoir plus</button>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20 text-gray-500 text-xl">Aucun catalogue disponible.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ===== SECTION PARTENAIRES (agrandie) ===== -->
    <section class="py-24 md:py-32 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-4">Ils nous font confiance</h2>
                <p class="text-gray-500 text-base uppercase tracking-wide">Nos partenaires et clients</p>
            </div>
            @if(isset($partenaires) && $partenaires->count())
            <div class="relative overflow-hidden group">
                <div class="flex overflow-x-auto scrollbar-hide gap-10 py-6 px-4 scroll-smooth" id="partenairesScroll">
                    @foreach($partenaires as $partenaire)
                    <div class="flex-shrink-0 w-40 h-40 bg-gray-50 rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition">
                        @if($partenaire->logo)<img src="{{ asset('storage/' . $partenaire->logo) }}" alt="{{ $partenaire->nom_entreprise }}" class="max-w-full max-h-full p-3 object-contain">@else<span class="text-gray-400 text-sm">{{ $partenaire->nom_entreprise }}</span>@endif
                    </div>
                    @endforeach
                </div>
                <button onclick="scrollPartenaire('left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button onclick="scrollPartenaire('right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
            @else
            <div class="text-center py-20 bg-gray-50 rounded-2xl"><p class="text-gray-500 text-xl">Aucun partenaire pour le moment.</p></div>
            @endif
        </div>
    </section>

    <!-- ===== SECTION AVIS CLIENTS (agrandie) ===== -->
    <section class="py-24 md:py-32 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-4">Ce qu'ils disent de nous</h2>
                <p class="text-gray-500 text-base uppercase tracking-wide">Témoignages de nos clients</p>
            </div>
            @if(isset($avis) && $avis->count())
            <div class="relative overflow-hidden group">
                <div class="flex overflow-x-auto scrollbar-hide gap-8 py-6 px-4 scroll-smooth" id="avisScroll">
                    @foreach($avis as $a)
                    <div class="flex-shrink-0 w-96 bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition flex flex-col justify-between h-full">
                        <div class="flex text-yellow-500 text-xl mb-4">
                            @for($i=1;$i<=5;$i++) @if($i<=$a->note)<i class="fas fa-star"></i>@else<i class="far fa-star"></i>@endif @endfor
                        </div>
                        <p class="text-gray-700 italic text-lg mb-6 line-clamp-4">"{{ Str::limit($a->contenu, 180) }}"</p>
                        <div class="flex items-center gap-4 mt-2">
                            @if($a->logo_entreprise)<img src="{{ asset('storage/'.$a->logo_entreprise) }}" class="w-12 h-12 rounded-full object-cover">@else<div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">{{ substr($a->entreprise_nom,0,1) }}</div>@endif
                            <div><p class="font-bold text-gray-800 text-lg">{{ Str::limit($a->entreprise_nom, 30) }}</p><p class="text-sm text-gray-500">{{ $a->contact_fonction ?? 'Client' }}</p></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button onclick="scrollAvis('left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button onclick="scrollAvis('right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
            <div class="text-center mt-12"><a href="{{ route('avis.create') }}" class="inline-block bg-green-700 hover:bg-green-800 text-white font-bold py-4 px-10 rounded-xl transition text-lg shadow-md">✍️ Donnez votre avis</a></div>
            @else
            <div class="text-center py-20 bg-white rounded-2xl"><p class="text-gray-500 text-xl">Aucun avis pour le moment.</p></div>
            @endif
        </div>
    </section>

    <!-- ===== SECTION BLOG (agrandie) ===== -->
    <section id="blog" class="py-24 md:py-32 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-center mb-4">Blog & Actualités</h2>
            <p class="text-center text-gray-600 text-lg mb-16">Retrouvez nos conseils, actualités et études de cas</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($articles as $article)
                <div class="bg-gray-50 rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition flex flex-col h-full">
                    @if($article->image_une)<img src="{{ asset('storage/'.$article->image_une) }}" alt="{{ $article->titre }}" class="w-full h-64 object-cover">@endif
                    <div class="p-8 flex flex-col flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3 line-clamp-2">{{ $article->titre }}</h3>
                        <p class="text-gray-500 text-base mb-4"><i class="far fa-calendar-alt mr-2"></i> {{ $article->date_publication->format('d/m/Y') }}</p>
                        <p class="text-gray-600 text-base mb-6 line-clamp-3 flex-1">{{ Str::limit($article->extrait ?? $article->contenu, 150) }}</p>
                        <a href="{{ route('blog.show', $article->slug) }}" class="inline-flex items-center gap-2 text-green-700 font-semibold hover:text-green-800 text-lg mt-auto">Lire la suite <i class="fas fa-arrow-right text-sm"></i></a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20"><p class="text-gray-500 text-xl">Aucun article publié pour le moment.</p></div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ===== FOOTER (agrandi, avec formulaire) ===== -->
    <footer id="contact" class="bg-rose-500 text-white w-full rounded-2xl overflow-hidden shadow-xl">
        <div class="container-fluid px-8 py-24 md:py-32">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 flex justify-center">
                    <img src="{{ asset('images/dame.png') }}" alt="Silhouette" class="w-full max-w-xl h-auto object-contain">
                </div>
                <div class="lg:w-1/2 w-full">
                    <h3 class="text-5xl font-bold mb-10 text-center lg:text-left">Nous contacter</h3>
                    <form id="footerContactForm">
                        @csrf
                        <div class="mb-6"><input type="text" name="nom" id="footer_nom" placeholder="Nom complet" class="w-full px-6 py-5 text-lg rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" required maxlength="150"></div>
                        <div class="mb-6"><input type="email" name="email" id="footer_email" placeholder="Email" class="w-full px-6 py-5 text-lg rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" required maxlength="150"></div>
                        <div class="mb-6"><input type="text" name="telephone" id="footer_telephone" placeholder="Téléphone" class="w-full px-6 py-5 text-lg rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" maxlength="30"></div>
                        <div class="mb-8"><textarea name="message" id="footer_message" rows="5" placeholder="Votre message..." class="w-full px-6 py-5 text-lg rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" required maxlength="2000"></textarea></div>
                        <button type="submit" id="footerSubmitBtn" class="w-full bg-yellow-500 hover:bg-yellow-600 text-rose-800 font-bold py-5 rounded-xl transition text-2xl">Envoyer le message</button>
                    </form>
                    <div id="footerContactSuccess" class="hidden mt-6 text-green-300 text-lg text-center"></div>
                    <div id="footerContactError" class="hidden mt-6 text-red-300 text-lg text-center"></div>
                    <div class="mt-12 flex flex-col sm:flex-row justify-center lg:justify-start gap-6">
                        <a href="https://www.facebook.com/ToutHelp" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 bg-[#1877F2] hover:bg-[#0e63cf] px-6 py-3 rounded-full transition"><i class="fab fa-facebook-f text-2xl"></i><span class="text-xl font-semibold">Tout help</span></a>
                        @php $contactEmail = \App\Models\Setting::get('contact_email', 'contact@touthelp.com'); @endphp
                        <div class="flex items-center gap-3 bg-white/10 px-6 py-3 rounded-full"><i class="fas fa-envelope text-2xl text-red-400"></i><span class="text-xl">{{ $contactEmail }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- ===== ROBOT FLOTTANT ===== -->
    <div class="robot-icon" id="robotIcon">
        <i class="fas fa-robot"></i>
        <span id="robotBadge" class="robot-badge" style="display:none;">0</span>
    </div>

    <!-- ===== MODALE CHAT ===== -->
    <div class="chat-modal" id="chatModal">
        <div class="chat-header">
            <div class="chat-header-left">
                <div class="chat-header-avatar">🤖</div>
                <div>
                    <div class="chat-header-name">Support Tout Help</div>
                    <div class="chat-header-status"><span class="chat-status-dot"></span> En ligne</div>
                </div>
            </div>
            <button class="chat-close-btn" onclick="closeChatModal()">✕</button>
        </div>

        <div class="chat-body" id="chatBody">
            <div class="chat-messages-area" id="chatMessagesArea"></div>
        </div>

        <div id="chatInputArea" class="chat-input-area" style="display:none;">
            <textarea id="chatTextarea" class="chat-textarea" rows="1" placeholder="Écrivez votre message..." maxlength="1000"></textarea>
            <button id="chatSendBtn" class="chat-send-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M22 2 11 13M22 2 15 22l-4-9-9-4 20-7z"/></svg>
            </button>
        </div>

        <div id="chatInitForm" class="chat-init-form">
            <div style="text-align:center;margin-bottom:12px;"><p style="font-size:13px;color:#e63946;">Bonjour ! 👋 Pour commencer, présentez-vous :</p></div>
            <input type="text" id="initNom" placeholder="Votre nom complet *" maxlength="150">
            <input type="email" id="initEmail" placeholder="Votre email *" maxlength="150">
            <input type="text" id="initTel" placeholder="Votre téléphone (optionnel)" maxlength="30">
            <textarea id="initMessage" rows="2" placeholder="Votre message *" style="resize:none;" maxlength="1000"></textarea>
            <button class="chat-init-btn" id="initSendBtn" onclick="submitInitForm()"><i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation</button>
        </div>

        <div id="changeIdentityBar" style="display:none;background:white;border-top:1px solid #ffe0e0;">
            <button class="change-identity-btn" onclick="resetChat()"><i class="fas fa-user-edit mr-1"></i> Nouvelle conversation</button>
        </div>

        <div class="chat-footer">Réponse dans les plus brefs délais · Tout Help</div>
    </div>

    <!-- ===== MODALE CATALOGUE ===== -->
    <div id="syllabusModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                <h3 id="modalTitle" class="text-2xl font-bold text-gray-800">Détail du syllabus</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div id="modalContent" class="p-6"></div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        /* ============================================================
           ÉTAT GLOBAL
           ============================================================ */
        let currentEmail = '';
        let currentNom   = '';
        let unreadCount  = 0;
        let audioCtx     = null;
        let rateLimitTimers = {};

        /* ============================================================
           RATE LIMITING (protection anti-spam)
           ============================================================ */
        function canSendMessage(email) {
            const now = Date.now();
            const last = rateLimitTimers[email] || 0;
            if (now - last < 5000) return false;
            rateLimitTimers[email] = now;
            return true;
        }

        /* ============================================================
           VALIDATION DES ENTRÉES
           ============================================================ */
        function validateInput(input, type) {
            const cleaned = input.replace(/<[^>]*>/g, '');
            if (type === 'email') {
                const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
                return emailRegex.test(cleaned) ? cleaned : '';
            }
            if (type === 'name') {
                const nameRegex = /^[a-zA-ZÀ-ÿ\s\-']+$/;
                return nameRegex.test(cleaned) ? cleaned.substring(0, 150) : '';
            }
            if (type === 'message') return cleaned.substring(0, 1000);
            return cleaned;
        }

        /* ============================================================
           AUDIO
           ============================================================ */
        function initAudio() { if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)(); }
        function playNotifSound() {
            try {
                initAudio();
                const now = audioCtx.currentTime;
                const o = audioCtx.createOscillator(), g = audioCtx.createGain();
                o.connect(g); g.connect(audioCtx.destination);
                o.frequency.value = 880; g.gain.value = 0.2;
                o.start(now);
                g.gain.exponentialRampToValueAtTime(0.00001, now + 0.35);
                o.stop(now + 0.35);
            } catch(e) {}
        }
        document.addEventListener('click', initAudio, { once: true });

        /* ============================================================
           BADGE
           ============================================================ */
        function updateBadge() {
            const b = document.getElementById('robotBadge');
            if (unreadCount > 0) { b.textContent = unreadCount > 99 ? '99+' : unreadCount; b.style.display = 'flex'; }
            else { b.style.display = 'none'; }
        }

        /* ============================================================
           OUVRIR / FERMER CHAT
           ============================================================ */
        function openChatModal() {
            document.getElementById('chatModal').classList.add('active');
            unreadCount = 0;
            updateBadge();
            if (currentEmail) loadMessages();
            scrollChatToBottom();
        }
        function closeChatModal() { document.getElementById('chatModal').classList.remove('active'); }
        document.getElementById('robotIcon').addEventListener('click', openChatModal);
        window.addEventListener('click', (e) => {
            const modal  = document.getElementById('chatModal');
            const robot  = document.getElementById('robotIcon');
            const expertiseModales = ['modalInter','modalIntra','modalAccompagnement'];
            const anyExpertiseOpen = expertiseModales.some(id => {
                const m = document.getElementById(id);
                return m && m.classList.contains('flex');
            });
            if (modal.classList.contains('active') && !modal.contains(e.target) && !robot.contains(e.target) && !anyExpertiseOpen) closeChatModal();
        });
        function scrollChatToBottom() { setTimeout(() => { const b = document.getElementById('chatBody'); if (b) b.scrollTop = b.scrollHeight; }, 80); }

        /* ============================================================
           AFFICHER MESSAGES (avec escape HTML)
           ============================================================ */
        function renderMessages(messages) {
            const area = document.getElementById('chatMessagesArea');
            if (!messages || messages.length === 0) { area.innerHTML = '<div class="pending-tag">⏳ En attente de réponse...</div>'; return; }
            let html = '';
            for (let m of messages) {
                html += `<div class="bubble-sent chat-message-anim"><div class="bubble-sent-inner"><div class="bubble-text">${escapeHtml(m.message)}</div><div class="bubble-time">${formatTime(m.created_at)}</div></div></div>`;
                if (m.reponse_admin && m.reponse_admin.trim()) {
                    html += `<div class="bubble-received chat-message-anim"><div class="bubble-received-avatar">TH</div><div class="bubble-received-inner"><div class="bubble-text">${escapeHtml(m.reponse_admin)}</div><div class="bubble-time-left">${formatTime(m.updated_at)}</div></div></div>`;
                }
            }
            const last = messages[messages.length - 1];
            if (!last.reponse_admin || !last.reponse_admin.trim()) html += '<div class="pending-tag">⏳ En attente de réponse...</div>';
            area.innerHTML = html;
            scrollChatToBottom();
        }

        function formatTime(dateStr) {
            if (!dateStr) return '';
            return new Date(dateStr).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        function escapeHtml(str) {
            if (!str) return '';
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        /* ============================================================
           CHARGER MESSAGES
           ============================================================ */
        async function loadMessages() {
            if (!currentEmail) return;
            try {
                const res  = await fetch(`/api/messages?email=${encodeURIComponent(currentEmail)}`);
                const msgs = await res.json();
                renderMessages(msgs);
            } catch(e) {}
        }

        /* ============================================================
           ENVOYER MESSAGE API (avec anti-spam)
           ============================================================ */
        async function sendMessageAPI(nom, email, telephone, message) {
            if (!canSendMessage(email)) return { success: false, message: 'Veuillez patienter avant d\'envoyer un autre message' };
            const validNom = validateInput(nom, 'name');
            const validEmail = validateInput(email, 'email');
            const validMessage = validateInput(message, 'message');
            if (!validNom || !validEmail || !validMessage) return { success: false, message: 'Données invalides' };
            const res = await fetch('/contact/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ nom: validNom, email: validEmail, telephone, message: validMessage })
            });
            return await res.json();
        }

        /* ============================================================
           PASSER EN MODE CONVERSATION
           ============================================================ */
        function switchToConversationMode() {
            document.getElementById('chatInitForm').style.display   = 'none';
            document.getElementById('chatInputArea').style.display  = 'flex';
            document.getElementById('changeIdentityBar').style.display = 'block';
        }

        /* ============================================================
           FORMULAIRE INITIAL
           ============================================================ */
        async function submitInitForm() {
            const nom     = document.getElementById('initNom').value.trim();
            const email   = document.getElementById('initEmail').value.trim();
            const tel     = document.getElementById('initTel').value.trim();
            const message = document.getElementById('initMessage').value.trim();
            if (!nom || !email || !message) {
                flashError('Merci de remplir tous les champs obligatoires.');
                return;
            }
            const btn = document.getElementById('initSendBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Envoi...';
            btn.disabled  = true;
            try {
                const result = await sendMessageAPI(nom, email, tel, message);
                if (result.success) {
                    currentEmail = email; currentNom = nom;
                    switchToConversationMode();
                    await loadMessages();
                } else {
                    flashError(result.message || 'Erreur lors de l\'envoi. Réessayez.');
                }
            } catch(e) { flashError('Erreur réseau.'); }
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation';
            btn.disabled  = false;
        }

        /* ============================================================
           ENVOYER MESSAGE RAPIDE
           ============================================================ */
        async function sendQuickMessage() {
            const textarea = document.getElementById('chatTextarea');
            const msg = textarea.value.trim();
            if (!msg || !currentEmail) return;
            const btn = document.getElementById('chatSendBtn');
            btn.disabled = true;
            textarea.value = '';
            textarea.style.height = 'auto';
            try {
                const result = await sendMessageAPI(currentNom, currentEmail, '', msg);
                if (result.success) await loadMessages();
                else flashError(result.message || 'Erreur');
            } catch(e) {}
            btn.disabled = false;
        }
        document.getElementById('chatSendBtn').onclick = sendQuickMessage;
        document.getElementById('chatTextarea').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendQuickMessage(); }
        });
        document.getElementById('chatTextarea').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });

        /* ============================================================
           RESET CHAT
           ============================================================ */
        function resetChat() {
            currentEmail = ''; currentNom = '';
            document.getElementById('chatInitForm').style.display      = 'block';
            document.getElementById('chatInputArea').style.display     = 'none';
            document.getElementById('changeIdentityBar').style.display = 'none';
            document.getElementById('chatMessagesArea').innerHTML      = '';
            ['initNom','initEmail','initTel','initMessage'].forEach(id => { document.getElementById(id).value = ''; });
            const btn = document.getElementById('initSendBtn');
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation';
            btn.disabled  = false;
        }

        /* ============================================================
           OUVRIR CHAT DEPUIS MODALE EXPERTISE
           ============================================================ */
        function openChatFromModal(type) {
            const messages = {
                intra: 'Bonjour, je souhaite discuter d\'une formation intra-entreprise sur mesure pour mon organisation. Pouvez-vous me contacter ?',
                accompagnement: 'Bonjour, je souhaite obtenir un devis pour un accompagnement ou un audit. Pouvez-vous me contacter ?'
            };
            closeExpertiseModal(type);
            setTimeout(() => {
                if (currentEmail) {
                    switchToConversationMode();
                    const ta = document.getElementById('chatTextarea');
                    ta.value = messages[type] || '';
                    setTimeout(() => ta.focus(), 100);
                } else {
                    document.getElementById('initMessage').value = messages[type] || '';
                    setTimeout(() => document.getElementById('initNom').focus(), 100);
                }
                openChatModal();
            }, 250);
        }

        /* ============================================================
           FORMULAIRE CONTACT FOOTER (avec sécurité)
           ============================================================ */
        document.getElementById('footerContactForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const nom       = document.getElementById('footer_nom').value.trim();
            const email     = document.getElementById('footer_email').value.trim();
            const telephone = document.getElementById('footer_telephone').value.trim();
            const message   = document.getElementById('footer_message').value.trim();
            if (!nom || !email || !message) return;
            const btn = document.getElementById('footerSubmitBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Envoi en cours...';
            btn.disabled  = true;
            try {
                const result = await sendMessageAPI(nom, email, telephone, message);
                if (result.success) {
                    currentEmail = email; currentNom = nom;
                    document.getElementById('footerContactForm').reset();
                    switchToConversationMode();
                    openChatModal();
                    await loadMessages();
                    const successDiv = document.getElementById('footerContactSuccess');
                    successDiv.innerHTML = 'Message envoyé avec succès ! Nous vous répondrons rapidement.';
                    successDiv.classList.remove('hidden');
                    setTimeout(() => successDiv.classList.add('hidden'), 5000);
                } else {
                    const errorDiv = document.getElementById('footerContactError');
                    errorDiv.innerHTML = result.message || 'Erreur lors de l\'envoi. Réessayez.';
                    errorDiv.classList.remove('hidden');
                    setTimeout(() => errorDiv.classList.add('hidden'), 5000);
                }
            } catch(e) {
                const errorDiv = document.getElementById('footerContactError');
                errorDiv.innerHTML = 'Erreur réseau. Réessayez.';
                errorDiv.classList.remove('hidden');
                setTimeout(() => errorDiv.classList.add('hidden'), 5000);
            }
            btn.innerHTML = originalText;
            btn.disabled  = false;
        });

        /* ============================================================
           REALTIME ECHO
           ============================================================ */
        function setupEchoListener() {
            if (!window.Echo) return;
            window.Echo.channel('new-messages').listen('NewMessageReceived', (event) => {
                if (currentEmail === event.email_client) {
                    loadMessages();
                    playNotifSound();
                    if (!document.getElementById('chatModal').classList.contains('active')) {
                        unreadCount++;
                        updateBadge();
                    }
                }
            });
        }
        function initEcho() {
            if (typeof Pusher !== 'undefined' && !window.Echo) {
                window.Pusher = Pusher;
                window.Echo = new Echo({
                    broadcaster: 'reverb',
                    key: '{{ env("REVERB_APP_KEY") }}',
                    wsHost: '{{ env("REVERB_HOST", "localhost") }}',
                    wsPort: {{ env("REVERB_PORT", 8080) }},
                    forceTLS: false,
                    enabledTransports: ['ws', 'wss']
                });
                setTimeout(setupEchoListener, 1000);
            } else if (window.Echo) {
                setupEchoListener();
            } else {
                setTimeout(initEcho, 500);
            }
        }
        document.addEventListener('DOMContentLoaded', initEcho);

        /* ============================================================
           MODALES EXPERTISE
           ============================================================ */
        function openExpertiseModal(type) {
            const ids = { inter: 'modalInter', intra: 'modalIntra', accompagnement: 'modalAccompagnement' };
            const m = document.getElementById(ids[type]);
            if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
        }
        function closeExpertiseModal(type) {
            const ids = { inter: 'modalInter', intra: 'modalIntra', accompagnement: 'modalAccompagnement' };
            const m = document.getElementById(ids[type]);
            if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
        }

        /* ============================================================
           MODALE CATALOGUE
           ============================================================ */
        function openModal(catalogueId) {
            const modal = document.getElementById('syllabusModal');
            const contentDiv = document.getElementById('modalContent');
            const titleSpan  = document.getElementById('modalTitle');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            contentDiv.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-green-700"></i><p class="mt-2">Chargement...</p></div>';
            fetch(`/api/catalogue/${catalogueId}`)
                .then(r => r.json())
                .then(data => {
                    contentDiv.innerHTML = `<div class="prose max-w-none">${data.image ? `<img src="${data.image_url}" class="w-full rounded-lg mb-6">` : ''}<h2 class="text-xl font-semibold text-green-700">📘 Description</h2><p>${escapeHtml(data.description || 'Non renseignée')}</p><h2 class="text-xl font-semibold text-green-700 mt-4">🎯 Objectifs</h2><div>${escapeHtml(data.objectifs ? data.objectifs.replace(/\n/g,'<br>') : 'Non renseignés')}</div><h2 class="text-xl font-semibold text-green-700 mt-4">👥 Public visé</h2><p>${escapeHtml(data.public_vise || 'Non renseigné')}</p><h2 class="text-xl font-semibold text-green-700 mt-4">📚 Programme détaillé</h2><div>${escapeHtml(data.programme ? data.programme.replace(/\n/g,'<br>') : 'Non renseigné')}</div></div><div class="mt-6 flex flex-wrap gap-4">${data.fichier_pdf ? `<a href="${data.fichier_url}" target="_blank" rel="noopener noreferrer" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg"><i class="fas fa-download mr-2"></i>Télécharger le syllabus</a>` : ''}<a href="#contact" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg" onclick="closeModal()"><i class="fas fa-file-invoice-dollar mr-2"></i>Demander un devis</a></div>`;
                    titleSpan.innerText = escapeHtml(data.titre);
                })
                .catch(() => { contentDiv.innerHTML = '<div class="text-red-600 text-center py-8">Erreur de chargement</div>'; });
        }
        function closeModal() { const m = document.getElementById('syllabusModal'); m.classList.add('hidden'); m.classList.remove('flex'); }

        /* ============================================================
           CARROUSELS
           ============================================================ */
        function scrollPartenaire(dir) {
            const c = document.getElementById('partenairesScroll');
            if (c) c.scrollBy({ left: dir === 'left' ? -300 : 300, behavior: 'smooth' });
        }
        function scrollAvis(dir) {
            const c = document.getElementById('avisScroll');
            if (c) c.scrollBy({ left: dir === 'left' ? -350 : 350, behavior: 'smooth' });
        }

        /* ============================================================
           FLASH ERROR
           ============================================================ */
        function flashError(msg) {
            let el = document.getElementById('chatFlashError');
            if (!el) {
                el = document.createElement('div');
                el.id = 'chatFlashError';
                el.style.cssText = 'background:#fee2e2;color:#b91c1c;padding:8px 12px;border-radius:8px;font-size:12px;margin:0 0 8px;text-align:center;';
                document.getElementById('chatInitForm').prepend(el);
            }
            el.textContent = msg;
            el.style.display = 'block';
            setTimeout(() => { el.style.display = 'none'; }, 4000);
        }
    </script>
</body>
</html>