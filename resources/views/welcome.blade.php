<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tout Help - Accueil</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- ============================================ -->
    <!-- EN-TÊTES DE SÉCURITÉ HTTP                     -->
    <!-- ============================================ -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://js.pusher.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; connect-src 'self' ws://localhost:8080 wss://localhost:8080 http://127.0.0.1:8080; frame-ancestors 'none';">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    <meta http-equiv="Permissions-Policy" content="geolocation=(), microphone=(), camera=()">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    
    <style>
        /* ===== STYLES EXISTANTS (conservés) ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

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
        .chat-init-form input, .chat-init-form textarea { width: 100%; padding: 9px 12px; margin-bottom: 10px; border: 1px solid #ffe0e0; border-radius: 10px; font-size: 13px; outline: none; transition: border-color 0.2s; }
        .chat-init-form input:focus, .chat-init-form textarea:focus { border-color: #e63946; }
        .chat-init-btn { width: 100%; background: linear-gradient(135deg, #e63946, #ff6b6b); color: white; border: none; padding: 10px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; transition: opacity 0.2s; }
        .chat-init-btn:hover { opacity: 0.9; }
        .chat-init-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .bubble-sent { display: flex; justify-content: flex-end; }
        .bubble-sent-inner { background: linear-gradient(135deg, #e63946, #ff6b6b); color: white; border-radius: 18px 18px 4px 18px; padding: 9px 13px; max-width: 75%; box-shadow: 0 2px 6px rgba(230,57,70,0.25); }
        .bubble-received { display: flex; justify-content: flex-start; align-items: flex-end; gap: 8px; }
        .bubble-received-avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #e63946, #ff6b6b); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; color: white; font-weight: 600; flex-shrink: 0; }
        .bubble-received-inner { background: white; color: #1f2937; border-radius: 18px 18px 18px 4px; padding: 9px 13px; max-width: 75%; box-shadow: 0 2px 6px rgba(0,0,0,0.07); }
        .bubble-text { font-size: 13px; line-height: 1.45; word-break: break-word; }
        .bubble-time { font-size: 10px; margin-top: 3px; text-align: right; opacity: 0.65; }
        .bubble-time-left { font-size: 10px; margin-top: 3px; opacity: 0.55; }

        .pending-tag { text-align: center; font-size: 11px; color: #e63946; background: rgba(255,255,255,0.9); border-radius: 20px; padding: 4px 12px; margin: 6px auto; width: fit-content; border: 1px solid #ffe0e0; }
        .change-identity-btn { font-size: 11px; color: #e63946; background: none; border: none; cursor: pointer; text-align: center; width: 100%; padding: 6px; display: block; transition: color 0.15s; }
        .change-identity-btn:hover { color: #c1121f; }

        .robot-icon { position: fixed; bottom: 20px; right: 20px; background: linear-gradient(135deg, #e63946, #ff6b6b); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 5px 20px rgba(230,57,70,0.4); transition: all 0.3s ease; z-index: 9999; }
        .robot-icon:hover { transform: scale(1.1); }
        .robot-icon i { font-size: 28px; color: white; }
        .robot-badge { position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 11px; font-weight: bold; min-width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0 4px; border: 2px solid white; }
        .chat-footer { background: white; padding: 6px; text-align: center; font-size: 11px; color: #e63946; border-top: 1px solid #ffe0e0; flex-shrink: 0; }

        .chat-loading { display: flex; align-items: center; justify-content: center; gap: 6px; padding: 20px; color: #e63946; font-size: 12px; }

        .hero-gradient { background: linear-gradient(135deg, #0a2e25 0%, #1a5c4a 100%); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .modal-expertise { transition: opacity 0.3s ease; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes messageAppear { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .chat-message-anim { animation: messageAppear 0.2s ease-out; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #e63946; border-radius: 10px; }

        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); background-color: #ef4444; }
        }
        @keyframes messageSlideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .chat-message-new { animation: messageSlideIn 0.3s ease-out; }
        @keyframes robotShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            75% { transform: translateX(3px); }
        }
        .robot-notification { animation: robotShake 0.5s ease-in-out; }
        #robotBadge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ef4444;
            color: white;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            font-weight: bold;
        }

        /* ===== STYLES POUR LA SECTION À PROPOS ===== */
        .apropos-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .apropos-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }
        .scroll-mt-20 {
            scroll-margin-top: 5rem;
        }
    </style>
</head>
<body class="bg-white">

    <!-- EN-TÊTE (mis à jour avec lien À PROPOS corrigé) -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Logo" class="h-12">
                <span class="text-xl font-bold text-green-900">TOUT HELP</span>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="#accueil" class="text-gray-700 hover:text-green-800 font-medium">ACCUEIL</a>
                <a href="#apropos" class="text-gray-700 hover:text-green-800 font-medium">À PROPOS</a>
                <a href="#expertise" class="text-gray-700 hover:text-green-800 font-medium">EXPERTISE</a>
                <a href="#catalogue" class="text-gray-700 hover:text-green-800 font-medium">CATALOGUE</a>
                <a href="#blog" class="text-gray-700 hover:text-green-800 font-medium">BLOG</a>
                <a href="#contact" class="text-gray-700 hover:text-green-800 font-medium">CONTACT</a>
            </nav>
        </div>
    </header>

    <!-- SECTION ACCUEIL -->
    <section id="accueil" class="relative bg-white pt-16 pb-20 overflow-hidden">
        <div class="container mx-auto px-4 flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/2 z-10">
                <div class="inline-flex items-center space-x-2 bg-gray-100 px-4 py-1 rounded-full text-[10px] font-bold text-gray-500 mb-8 tracking-widest uppercase">
                    <i class="fas fa-shield-alt text-blue-500"></i>
                    <span>Formation • Accompagnement • Audit</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-[#0f2439] leading-[0.9] mb-4">
                    ENSEMBLE<br>
                    FAISONS DE LA<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-300">PERFORMANCE</span><br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-500 to-orange-400">UNE CULTURE</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-lg mb-10 border-l-4 border-orange-500 pl-4">
                    Expert en <span class="text-blue-600 font-bold">Solutions RH</span> et <span class="text-blue-600 font-bold">Accompagnement sur-mesure</span> à Madagascar.
                </p>
            </div>
            <div class="lg:w-1/2 relative mt-12 lg:mt-0">
                <div class="relative z-10">
                    <img src="{{ asset('images/accueil.png') }}" alt="Expertise Tout Help" class="w-full h-auto">
                </div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-blue-50 rounded-full filter blur-3xl opacity-50 -z-10"></div>
            </div>
        </div>
    </section>

    <!-- SECTION À PROPOS (NOUVELLE SECTION INTÉGRÉE) -->
    <section id="apropos" class="py-16 bg-white scroll-mt-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">À propos de nous</h2>
                    <div class="w-24 h-1 bg-green-600 mx-auto rounded-full"></div>
                    <p class="text-gray-500 mt-4 text-sm uppercase tracking-wide">Découvrez qui nous sommes et notre mission</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 apropos-card">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row gap-8 items-center">
                            <div class="md:w-2/5">
                                <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Tout Help" class="w-full max-w-xs mx-auto">
                            </div>
                            <div class="md:w-3/5">
                                <h3 class="text-2xl font-bold text-gray-800 mb-4">Qui sommes-nous ?</h3>
                                <p class="text-gray-600 mb-4 leading-relaxed">
                                    Tout Help est une entreprise malgache spécialisée dans les <span class="font-semibold text-green-700">solutions RH</span> et l'<span class="font-semibold text-green-700">accompagnement sur-mesure</span>.
                                </p>
                                <p class="text-gray-600 leading-relaxed">
                                    Notre mission est d'accompagner les organisations dans leur développement en mettant la <span class="font-semibold text-green-700">performance</span> au cœur de leur culture.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cartes valeurs -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-10">
                    <div class="bg-gray-50 rounded-xl p-5 text-center hover:shadow-md transition border border-gray-100 apropos-card">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-chart-line text-xl text-green-700"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1">Performance</h4>
                        <p class="text-gray-500 text-xs">Optimisation des processus et des compétences</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-5 text-center hover:shadow-md transition border border-gray-100 apropos-card">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-users text-xl text-green-700"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1">Accompagnement</h4>
                        <p class="text-gray-500 text-xs">Support personnalisé à chaque étape</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-5 text-center hover:shadow-md transition border border-gray-100 apropos-card">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-certificate text-xl text-green-700"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1">Excellence</h4>
                        <p class="text-gray-500 text-xs">Des solutions de qualité supérieure</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION EXPERTISE -->
    <section id="expertise" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">EXPERTISE SUR-MESURE</h2>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Des solutions adaptées à chaque besoin</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-100">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-users text-2xl text-blue-700"></i></div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">FORMATIONS INTER-ENTREPRISES</h3>
                        <p class="text-sm text-gray-500 uppercase mb-3">GRAND PUBLIC / SESSION PUBLIQUE</p>
                        <p class="text-gray-600 text-sm">Sessions ouvertes à tous, favorisant le partage d'expériences entre professionnels de différents horizons.</p>
                        <button onclick="openExpertiseModal('inter')" class="mt-4 inline-block bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition">En savoir plus</button>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-100">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-building text-2xl text-green-700"></i></div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">FORMATIONS INTRA-ENTREPRISE</h3>
                        <p class="text-sm text-gray-500 uppercase mb-3">SUR-MESURE / PRIVÉ</p>
                        <p class="text-gray-600 text-sm">Formations personnalisées adaptées à la culture et aux besoins spécifiques de votre organisation.</p>
                        <button onclick="openExpertiseModal('intra')" class="mt-4 inline-block bg-green-700 hover:bg-green-800 text-white font-semibold py-2 px-4 rounded-lg transition">En savoir plus</button>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-100">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-content-center mx-auto mb-4"><i class="fas fa-clipboard-list text-2xl text-purple-700"></i></div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">ACCOMPAGNEMENT & AUDIT</h3>
                        <p class="text-sm text-gray-500 uppercase mb-3">STRUCTURER – ÉVALUER – PROGRESSER</p>
                        <p class="text-gray-600 text-sm">De la mise en place de vos systèmes à l'évaluation de vos pratiques, une approche claire et progressive.</p>
                        <button onclick="openExpertiseModal('accompagnement')" class="mt-4 inline-block bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 px-4 rounded-lg transition">En savoir plus</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MODALES EXPERTISE (inchangées) -->
    <div id="modalInter" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4 modal-expertise">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">Formations inter-entreprises</h3>
                <button onclick="closeExpertiseModal('inter')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div class="p-6 prose max-w-none">
                <p>Nos formations interentreprises sont conçues pour permettre à vos collaborateurs de développer leurs compétences dans un cadre structuré, tout en bénéficiant d'échanges avec des professionnels issus de différents horizons.</p>
                <h3 class="text-lg font-bold mt-4">🧩 Ce que nous proposons</h3>
                <ul class="list-disc pl-6"><li>Sessions publiques ouvertes</li><li>Séminaires thématiques</li><li>Journées de sensibilisation</li><li>Portes ouvertes</li><li>Calendrier de formations planifiées</li></ul>
                <h3 class="text-lg font-bold mt-4">🎯 Pour qui ?</h3>
                <p>Ces formations s'adressent aux professionnels souhaitant : renforcer leurs compétences, mettre à jour leurs connaissances, s'initier à de nouvelles pratiques.</p>
                <div class="mt-6 flex flex-wrap gap-4"><a href="{{ route('calendrier') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded-lg" onclick="closeExpertiseModal('inter')">📅 Voir le calendrier des formations</a></div>
            </div>
        </div>
    </div>

    <div id="modalIntra" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4 modal-expertise">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">Formations intra-entreprise</h3>
                <button onclick="closeExpertiseModal('intra')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div class="p-6 prose max-w-none">
                <p>Chaque organisation a ses propres réalités. Nos formations intra-entreprise sont conçues sur mesure pour répondre précisément à vos besoins.</p>
                <h3 class="text-lg font-bold mt-4">🧩 Ce que nous proposons</h3>
                <ul class="list-disc pl-6"><li>Formations personnalisées selon vos besoins</li><li>Thématiques pluridisciplinaires et variées</li><li>Adaptation des contenus à votre secteur d'activité</li><li>Cas pratiques basés sur votre environnement réel</li></ul>
                <div class="mt-6 flex flex-wrap gap-4">
                    <button onclick="openChatFromModal('intra')" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg">💬 Discuter de votre besoin</button>
                    <a href="#catalogue" class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-2 rounded-lg" onclick="closeExpertiseModal('intra')">📚 Parcourir nos catalogues</a>
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
            <div class="p-6 prose max-w-none">
                <p>Structurer son organisation et en évaluer l'efficacité vont de pair.</p>
                <div class="mt-6 border-l-4 border-green-600 pl-4">
                    <h3 class="text-xl font-bold text-green-700"><i class="fas fa-cogs mr-2"></i>Accompagnement</h3>
                    <ul class="list-disc pl-6 mt-2"><li>Mise en place de systèmes (ISO, HSE, SMSST, RSE…)</li><li>Structuration organisationnelle</li><li>Rédaction de processus et procédures</li></ul>
                    <div class="bg-green-50 p-3 rounded-lg mt-2"><p class="font-semibold text-green-800">Résultat :</p><p class="text-green-700">Un système clair, structuré et adapté à votre fonctionnement.</p></div>
                </div>
                <div class="mt-6 border-l-4 border-blue-600 pl-4">
                    <h3 class="text-xl font-bold text-blue-700"><i class="fas fa-search mr-2"></i>Audit</h3>
                    <ul class="list-disc pl-6 mt-2"><li>Audit interne</li><li>Audit à blanc (pré-certification)</li><li>Diagnostic organisationnel</li></ul>
                    <div class="bg-blue-50 p-3 rounded-lg mt-2"><p class="font-semibold text-blue-800">Résultat :</p><p class="text-blue-700">Une vision claire et des actions concrètes pour progresser.</p></div>
                </div>
                <div class="mt-6 flex justify-center">
                    <button onclick="openChatFromModal('accompagnement')" class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded-lg">📋 Demander un devis</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION CATALOGUE -->
    <section id="catalogue" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Nos catalogues de formation</h2>
                <p class="text-gray-600">Découvrez l'ensemble de nos syllabus.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($catalogues as $catalogue)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition flex flex-col">
                    @if($catalogue->image)<img src="{{ asset('storage/'.e($catalogue->image)) }}" class="w-full h-48 object-cover" alt="{{ e($catalogue->titre) }}">@endif
                    <div class="p-6 flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ e($catalogue->titre) }}</h3>
                        <p class="text-gray-600 line-clamp-3">{{ e(Str::limit($catalogue->description, 120)) }}</p>
                        <button onclick="openModal({{ (int)$catalogue->id }})" class="mt-4 inline-block bg-green-700 hover:bg-green-800 text-white font-semibold py-2 px-4 rounded-lg transition">En savoir plus</button>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 text-gray-500">Aucun catalogue disponible.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION PARTENAIRES -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Ils nous font confiance</h2>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Nos partenaires et clients</p>
            </div>
            @if(isset($partenaires) && $partenaires->count())
            <div class="relative overflow-hidden group">
                <div class="flex overflow-x-auto scrollbar-hide gap-8 py-4 px-2 scroll-smooth" id="partenairesScroll">
                    @foreach($partenaires as $partenaire)
                    <div class="flex-shrink-0 w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition">
                        @if($partenaire->logo)<img src="{{ asset('storage/'.e($partenaire->logo)) }}" alt="{{ e($partenaire->nom_entreprise) }}" class="max-w-full max-h-full p-2 object-contain">@else<span class="text-gray-400 text-xs">{{ e($partenaire->nom_entreprise) }}</span>@endif
                    </div>
                    @endforeach
                </div>
                <button onclick="scrollPartenaire('left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button onclick="scrollPartenaire('right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
            @else
            <div class="text-center py-12 bg-gray-50 rounded-lg"><p class="text-gray-500">Aucun partenaire pour le moment.</p></div>
            @endif
        </div>
    </section>

    <!-- SECTION AVIS -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Ce qu'ils disent de nous</h2>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Témoignages de nos clients</p>
            </div>
            @if(isset($avis) && $avis->count())
            <div class="relative overflow-hidden group">
                <div class="flex overflow-x-auto scrollbar-hide gap-6 py-4 px-2 scroll-smooth" id="avisScroll">
                    @foreach($avis as $a)
                    <div class="flex-shrink-0 w-80 bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex text-yellow-500 mb-3">@for($i=1;$i<=5;$i++) @if($i<=$a->note)★@else☆@endif @endfor</div>
                        <p class="text-gray-600 italic mb-4">"{{ e(Str::limit($a->contenu, 150)) }}"</p>
                        <div class="flex items-center gap-3">
                            @if($a->logo_entreprise)<img src="{{ asset('storage/'.e($a->logo_entreprise)) }}" class="w-10 h-10 rounded-full object-cover" alt="{{ e($a->entreprise_nom) }}">@endif
                            <div><p class="font-bold text-gray-800">{{ e($a->entreprise_nom) }}</p><p class="text-sm text-gray-500">{{ e($a->contact_fonction ?? 'Client') }}</p></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button onclick="scrollAvis('left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button onclick="scrollAvis('right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
            @else
            <div class="text-center py-12 bg-white rounded-lg"><p class="text-gray-500">Aucun avis pour le moment.</p></div>
            @endif
        </div>
    </section>

    <!-- SECTION BLOG -->
    <section id="blog" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">Blog & Actualités</h2>
            <p class="text-center text-gray-600 mb-12">Retrouvez nos conseils, actualités et études de cas</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($articles as $article)
                <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="p-6">
                        <div class="mb-3">
                            @if($article->type=='blog')<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">📝 Blog</span>
                            @elseif($article->type=='reussite')<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">🏆 Réussite</span>
                            @else<span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">🤝 Partenariat</span>@endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ e($article->titre) }}</h3>
                        <p class="text-gray-500 text-sm mb-3"><i class="far fa-calendar-alt mr-1"></i> {{ $article->date_publication->format('d/m/Y') }}</p>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ e(Str::limit($article->extrait ?? $article->contenu, 100)) }}</p>
                        <a href="{{ route('blog.show', $article->slug) }}" class="inline-flex items-center gap-2 text-green-700 font-semibold hover:text-green-800">Lire la suite <i class="fas fa-arrow-right text-sm"></i></a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12"><p class="text-gray-500">Aucun article publié pour le moment.</p></div>
                @endforelse
            </div>
        </div>
    </section>

   <!-- FOOTER FUSIONNÉ CORRIGÉ -->
<!-- Première partie : Ancien footer (rose avec formulaire + image + Facebook + Email) -->
<footer>
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
                    <div id="footerContactError"   class="hidden mt-3 p-2 bg-red-800/30 text-red-200 text-sm text-center rounded-xl"></div>
                    
                    <!-- Facebook et Email (ancien footer, gardés ici uniquement) -->
                    <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="https://www.facebook.com/ToutHelp" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 bg-[#1877F2] hover:bg-[#0e63cf] px-5 py-3 rounded-full transition">
                            <i class="fab fa-facebook-f text-xl"></i>
                            <span class="font-semibold">Tout help</span>
                        </a>
                        @php $contactEmail = \App\Models\Setting::get('contact_email', 'contact@touthelp.com'); @endphp
                        <div class="flex items-center gap-3 bg-white/20 px-5 py-3 rounded-full">
                            <i class="fas fa-envelope text-xl text-yellow-300"></i>
                            <span>{{ e($contactEmail) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deuxième partie : Nouveau footer (gris standard, SANS email ni Facebook) -->
    <div class="bg-gray-800 text-gray-300 py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Colonne 1 - Logo & infos -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Logo" class="h-10">
                        <span class="text-xl font-bold text-white">TOUT HELP</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Expert en solutions RH et accompagnement sur-mesure à Madagascar.
                    </p>
                </div>

                <!-- Colonne 2 - Liens rapides -->
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Navigation</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#accueil" class="text-gray-400 hover:text-white transition">Accueil</a></li>
                        <li><a href="#apropos" class="text-gray-400 hover:text-white transition">À propos</a></li>
                        <li><a href="#expertise" class="text-gray-400 hover:text-white transition">Expertise</a></li>
                        <li><a href="#catalogue" class="text-gray-400 hover:text-white transition">Catalogue</a></li>
                        <li><a href="#blog" class="text-gray-400 hover:text-white transition">Blog</a></li>
                    </ul>
                </div>

                <!-- Colonne 3 - Services -->
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Services</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="text-gray-400">Formations inter-entreprises</li>
                        <li class="text-gray-400">Formations intra-entreprise</li>
                        <li class="text-gray-400">Accompagnement & Audit</li>
                        <li class="text-gray-400">Conseil RH</li>
                    </ul>
                </div>

                <!-- Colonne 4 - Infos légales -->
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Légal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Mentions légales</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">CGV</a></li>
                    </ul>
                </div>
            </div>

            <!-- Barre de copyright -->
            <div class="border-t border-gray-700 pt-6 mt-8 text-center">
                <p class="text-gray-500 text-xs">&copy; {{ date('Y') }} Tout Help. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</footer>

    <!-- ROBOT FLOTTANT -->
    <div class="robot-icon" id="robotIcon">
        <i class="fas fa-robot"></i>
        <span id="robotBadge" class="robot-badge" style="display:none;">0</span>
    </div>

    <!-- MODALE CHAT -->
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
            <input type="text"  id="initNom"     placeholder="Votre nom complet *"         maxlength="150" autocomplete="name">
            <input type="email" id="initEmail"   placeholder="Votre email *"                maxlength="150" autocomplete="email">
            <input type="tel"   id="initTel"     placeholder="Votre téléphone (optionnel)" maxlength="30"  autocomplete="tel">
            <textarea           id="initMessage" rows="2" placeholder="Votre message *" style="resize:none;" maxlength="1000"></textarea>
            <button class="chat-init-btn" id="initSendBtn" onclick="submitInitForm()">
                <i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation
            </button>
        </div>
        <div id="changeIdentityBar" style="display:none;background:white;border-top:1px solid #ffe0e0;">
            <button class="change-identity-btn" onclick="resetChat()"><i class="fas fa-user-edit mr-1"></i> Nouvelle conversation</button>
        </div>
        <div class="chat-footer">Réponse dans les plus brefs délais · Tout Help</div>
    </div>

    <!-- MODALE CATALOGUE -->
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
       SÉCURITÉ RENFORCÉE - CLIENT CHAT
       ============================================================ */
    
    (function() {
        "use strict";
        
        /* ============================================================
           ÉTAT GLOBAL
        ============================================================ */
        let currentEmail    = '';
        let currentNom      = '';
        let unreadCount     = 0;
        let audioCtx        = null;
        let echoListenerSet = false;
        let pollInterval    = null;
        let isLoading       = false;
        let isSending       = false;
        let lastMessageId   = null;
        let lastRefreshTime = 0;
        let lastMessagesHash = '';
        let hasNewMessage = false;
        let notificationTimeout = null;
        const rateLimits    = new Map();
        
        // === NOUVEAU : Protection contre les attaques XSS avancées ===
        const MAX_MESSAGE_LENGTH = 1000;
        const MAX_NAME_LENGTH = 150;
        const MAX_EMAIL_LENGTH = 150;
        const MAX_PHONE_LENGTH = 30;
        
        // === NOUVEAU : Nettoyage avancé des entrées ===
        function deepSanitize(input) {
            if (input === null || input === undefined) return '';
            let cleaned = String(input);
            // Supprimer les balises HTML/script
            cleaned = cleaned.replace(/<[^>]*>/g, '');
            // Supprimer les caractères de contrôle dangereux
            cleaned = cleaned.replace(/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/g, '');
            // Supprimer les entités HTML dangereuses
            cleaned = cleaned.replace(/&[#\w]+;/g, '');
            // Limiter la longueur
            cleaned = cleaned.substring(0, MAX_MESSAGE_LENGTH);
            return cleaned.trim();
        }
        
        /* ============================================================
           SÉCURITÉ — Échappement HTML renforcé (XSS)
        ============================================================ */
        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            const div = document.createElement('div');
            div.textContent = String(str);
            return div.innerHTML;
        }
        
        // === NOUVEAU : Validation email ultra stricte ===
        function isValidEmail(email) {
            if (!email || typeof email !== 'string') return false;
            const trimmed = email.trim();
            if (trimmed.length > MAX_EMAIL_LENGTH) return false;
            // Regex RFC 5322 compliant simplifiée mais stricte
            const emailRegex = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]{0,63}@[a-zA-Z0-9][a-zA-Z0-9.-]{0,252}\.[a-zA-Z]{2,}$/;
            return emailRegex.test(trimmed);
        }
        
        // === NOUVEAU : Validation nom (prévention injections) ===
        function isValidName(name) {
            if (!name || typeof name !== 'string') return false;
            const trimmed = name.trim();
            if (trimmed.length > MAX_NAME_LENGTH) return false;
            if (trimmed.length < 2) return false;
            // Lettres, espaces, apostrophes, tirets, caractères accentués UNIQUEMENT
            const nameRegex = /^[a-zA-ZÀ-ÿ\s'\-]{2,150}$/;
            return nameRegex.test(trimmed);
        }
        
        // === NOUVEAU : Validation téléphone ===
        function isValidPhone(phone) {
            if (!phone) return true; // Optionnel
            const trimmed = phone.trim();
            if (trimmed.length > MAX_PHONE_LENGTH) return false;
            const phoneRegex = /^[\d\s+\-().]{1,30}$/;
            return phoneRegex.test(trimmed);
        }
        
        // === NOUVEAU : Validation message ===
        function isValidMessage(msg) {
            if (!msg || typeof msg !== 'string') return false;
            const trimmed = msg.trim();
            if (trimmed.length < 2) return false;
            if (trimmed.length > MAX_MESSAGE_LENGTH) return false;
            // Pas de balises HTML, pas de caractères de contrôle
            if (/<[^>]*>/.test(trimmed)) return false;
            if (/[\x00-\x08\x0B\x0C\x0E-\x1F]/.test(trimmed)) return false;
            return true;
        }
        
        /* ============================================================
           VALIDATION (sécurisée)
        ============================================================ */
        const VALIDATORS = {
            email: isValidEmail,
            name: isValidName,
            message: isValidMessage,
            phone: isValidPhone,
        };
        
        function sanitize(str, max = 1000) {
            if (!str) return '';
            let cleaned = String(str);
            cleaned = cleaned.replace(/<[^>]*>/g, '');
            cleaned = cleaned.substring(0, max);
            return cleaned.trim();
        }
        
        /* ============================================================
           RATE LIMITING RENFORCÉ (anti-spam + anti-DoS)
        ============================================================ */
        const RATE_LIMIT_DELAY = 5000; // 5 secondes entre chaque message
        const MAX_REQUESTS_PER_MINUTE = 12; // Maximum 12 requêtes par minute
        const requestTimestamps = [];
        
        function isRateLimited(email) {
            // Nettoyer les anciennes timestamps (plus vieilles que 60 secondes)
            const now = Date.now();
            while (requestTimestamps.length > 0 && requestTimestamps[0] < now - 60000) {
                requestTimestamps.shift();
            }
            
            // Vérifier le nombre de requêtes dans la dernière minute
            if (requestTimestamps.length >= MAX_REQUESTS_PER_MINUTE) {
                flashError('Trop de tentatives. Veuillez patienter une minute.');
                return true;
            }
            
            // Vérifier le délai entre chaque message
            const last = rateLimits.get(email) || 0;
            if (now - last < RATE_LIMIT_DELAY) {
                flashError('Merci de patienter quelques secondes avant de renvoyer.');
                return true;
            }
            
            rateLimits.set(email, now);
            requestTimestamps.push(now);
            return false;
        }
        
        function checkRateLimit(email) {
            return !isRateLimited(email);
        }
        
        /* ============================================================
   AUDIO - CORRECTION POUR NAVIGATEURS MODERNES
   ============================================================ */
let audioEnabled = false;
let pendingSounds = [];

function initAudio() {
    if (!audioCtx) {
        try { 
            audioCtx = new (window.AudioContext || window.webkitAudioContext)(); 
        } catch(e) {
            console.warn('Web Audio API non supportée');
        }
    }
}

// Fonction pour activer l'audio (appelée au clic utilisateur)
function enableAudio() {
    if (audioEnabled) return;
    
    initAudio();
    if (audioCtx && audioCtx.state === 'suspended') {
        audioCtx.resume().then(() => {
            audioEnabled = true;
            console.log('✅ Audio activé');
            // Jouer les sons en attente
            pendingSounds.forEach(() => playNotifSound());
            pendingSounds = [];
        }).catch(e => console.warn('Audio resume failed:', e));
    } else if (audioCtx && audioCtx.state === 'running') {
        audioEnabled = true;
    }
}

function playNotifSound() {
    if (!soundEnabled) return;
    
    // Si l'audio n'est pas encore activé, mettre en file d'attente
    if (!audioEnabled) {
        pendingSounds.push(true);
        return;
    }
    
    try {
        initAudio();
        if (!audioCtx || audioCtx.state !== 'running') return;
        
        const now = audioCtx.currentTime;
        
        // Premier bip (aigu)
        const o1 = audioCtx.createOscillator();
        const g1 = audioCtx.createGain();
        o1.connect(g1);
        g1.connect(audioCtx.destination);
        o1.type = 'sine';
        o1.frequency.value = 880;
        g1.gain.setValueAtTime(0.2, now);
        g1.gain.exponentialRampToValueAtTime(0.00001, now + 0.25);
        o1.start(now);
        o1.stop(now + 0.25);
        
        // Deuxième bip (plus grave) après un petit délai
        setTimeout(() => {
            if (audioCtx && audioCtx.state === 'running') {
                const o2 = audioCtx.createOscillator();
                const g2 = audioCtx.createGain();
                o2.connect(g2);
                g2.connect(audioCtx.destination);
                o2.type = 'sine';
                o2.frequency.value = 660;
                g2.gain.setValueAtTime(0.15, audioCtx.currentTime);
                g2.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + 0.2);
                o2.start();
                o2.stop(audioCtx.currentTime + 0.2);
            }
        }, 120);
        
    } catch(e) {
        console.warn('Erreur lecture son:', e);
    }
}

// Activer l'audio au premier clic sur le robot OU n'importe où sur la page
document.addEventListener('click', enableAudio, { once: true });
// Aussi au clic sur le robot spécifiquement
document.getElementById('robotIcon')?.addEventListener('click', enableAudio);
        
        /* ============================================================
           BADGE ROBOT
        ============================================================ */
        function updateBadge() {
            const b = document.getElementById('robotBadge');
            if (!b) return;
            
            if (unreadCount > 0) {
                b.textContent = unreadCount > 99 ? '99+' : unreadCount;
                b.style.display = 'flex';
                
                b.style.animation = 'none';
                b.offsetHeight;
                b.style.animation = 'badgePulse 0.6s ease-in-out';
            } else {
                b.style.display = 'none';
            }
        }
        
        function showRobotNotification() {
            const robot = document.getElementById('robotIcon');
            if (!robot) return;
            
            robot.classList.add('robot-notification');
            setTimeout(() => {
                robot.classList.remove('robot-notification');
            }, 1000);
        }
        
        /* ============================================================
           OUVERTURE / FERMETURE CHAT
        ============================================================ */
        function openChatModal() {
            const modal = document.getElementById('chatModal');
            modal.classList.add('active');
            
            unreadCount = 0;
            updateBadge();
            
            if (currentEmail) {
                loadMessages(true);
                startPolling();
            }
            scrollChatToBottom();
        }
        
        function closeChatModal() {
            document.getElementById('chatModal').classList.remove('active');
            stopPolling();
        }
        
        document.getElementById('robotIcon').addEventListener('click', openChatModal);
        
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('chatModal');
            const robot = document.getElementById('robotIcon');
            if (!modal.classList.contains('active')) return;
            if (modal.contains(e.target) || robot.contains(e.target)) return;
            const expertiseOpen = ['modalInter','modalIntra','modalAccompagnement'].some(id => {
                const m = document.getElementById(id); return m && m.classList.contains('flex');
            });
            if (!expertiseOpen) closeChatModal();
        });
        
        document.addEventListener('keydown', e => {
            if (e.key !== 'Escape') return;
            closeChatModal();
            ['inter','intra','accompagnement'].forEach(t => closeExpertiseModal(t));
            closeModal();
        });
        
        function scrollChatToBottom() {
            setTimeout(() => {
                const b = document.getElementById('chatBody');
                if (b) b.scrollTop = b.scrollHeight;
            }, 100);
        }
        
        /* ============================================================
           POLLING OPTIMISÉ
        ============================================================ */
        function startPolling() {
            stopPolling();
            if (!currentEmail) return;
            
            pollInterval = setInterval(() => {
                if (currentEmail && document.getElementById('chatModal').classList.contains('active')) {
                    loadMessages(false);
                }
            }, 6000);
        }
        
        function stopPolling() {
            if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
        }
        
        /* ============================================================
           GÉNÉRATION DE HASH
        ============================================================ */
        function generateMessagesHash(messages) {
            if (!messages || messages.length === 0) return '';
            const lastMsg = messages[messages.length - 1];
            return `${lastMsg?.id || ''}-${lastMsg?.updated_at || ''}-${messages.length}`;
        }
        
        /* ============================================================
           AFFICHAGE DES MESSAGES (avec escapeHtml)
        ============================================================ */
        function renderMessages(messages, isNewMessage = false) {
            const area = document.getElementById('chatMessagesArea');
            if (!messages || messages.length === 0) {
                area.innerHTML = '<div class="pending-tag">⏳ En attente de réponse...</div>';
                return;
            }
        
            const frag = document.createDocumentFragment();
            let hasNew = false;
        
            for (let i = 0; i < messages.length; i++) {
                const m = messages[i];
                
                const sentDiv = document.createElement('div');
                sentDiv.className = 'bubble-sent';
                if (isNewMessage && i === messages.length - 1 && m.message && !m.reponse_admin) {
                    sentDiv.classList.add('chat-message-new');
                    hasNew = true;
                }
                
                const sentInner = document.createElement('div');
                sentInner.className = 'bubble-sent-inner';
                const sentTxt = document.createElement('div');
                sentTxt.className = 'bubble-text';
                sentTxt.textContent = escapeHtml(m.message); // SÉCURISÉ
                const sentTime = document.createElement('div');
                sentTime.className = 'bubble-time';
                sentTime.textContent = formatTime(m.created_at);
                sentInner.append(sentTxt, sentTime);
                sentDiv.appendChild(sentInner);
                frag.appendChild(sentDiv);
        
                if (m.reponse_admin && m.reponse_admin.trim()) {
                    const recvDiv = document.createElement('div');
                    recvDiv.className = 'bubble-received';
                    if (isNewMessage && i === messages.length - 1 && m.reponse_admin) {
                        recvDiv.classList.add('chat-message-new');
                        hasNew = true;
                    }
                    
                    const av = document.createElement('div');
                    av.className = 'bubble-received-avatar';
                    av.textContent = 'TH';
                    const recvInner = document.createElement('div');
                    recvInner.className = 'bubble-received-inner';
                    const recvTxt = document.createElement('div');
                    recvTxt.className = 'bubble-text';
                    recvTxt.textContent = escapeHtml(m.reponse_admin); // SÉCURISÉ
                    const recvTime = document.createElement('div');
                    recvTime.className = 'bubble-time-left';
                    recvTime.textContent = formatTime(m.updated_at);
                    recvInner.append(recvTxt, recvTime);
                    recvDiv.append(av, recvInner);
                    frag.appendChild(recvDiv);
                }
            }
        
            const currentHTML = area.innerHTML;
            const newHTML = frag.children.length > 0 ? Array.from(frag.children).map(el => el.outerHTML).join('') : '';
            
            if (currentHTML !== newHTML) {
                area.innerHTML = '';
                area.appendChild(frag);
                scrollChatToBottom();
            }
        }
        
        function formatTime(d) {
            if (!d) return '';
            try { 
                return new Date(d).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); 
            } catch(e) { 
                return ''; 
            }
        }
        
        /* ============================================================
           CHARGEMENT DES MESSAGES (sécurisé)
        ============================================================ */
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
                const res = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    cache: 'no-store'
                });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const msgs = await res.json();
                const messages = Array.isArray(msgs) ? msgs : [];
                
                const currentHash = generateMessagesHash(messages);
                const isNewContent = currentHash !== lastMessagesHash;
                
                if (isNewContent || force) {
                    const isNewMessage = isNewContent && !force;
                    lastMessagesHash = currentHash;
                    
                    if (messages.length > 0) {
                        lastMessageId = messages[messages.length - 1]?.id;
                    }
                    
                    const isChatOpen = document.getElementById('chatModal').classList.contains('active');
                    
                    if (!isChatOpen && isNewContent && !force) {
                        unreadCount++;
                        updateBadge();
                        showRobotNotification();
                        playNotifSound();
                    }
                    
                    renderMessages(messages, isNewMessage && !isChatOpen);
                    
                    if (isChatOpen) {
                        scrollChatToBottom();
                    }
                }
                
            } catch(e) {
                console.warn('[Chat] loadMessages error:', e.message);
            } finally {
                isLoading = false;
            }
        }
        
        /* ============================================================
           ENVOI DE MESSAGE (ULTRA SÉCURISÉ)
        ============================================================ */
        async function sendMessageAPI(nom, email, telephone, message) {
            // Validation stricte
            if (!isValidName(nom)) return { success: false, message: 'Nom invalide (2-150 caractères, lettres uniquement).' };
            if (!isValidEmail(email)) return { success: false, message: 'Email invalide.' };
            if (!isValidPhone(telephone)) return { success: false, message: 'Téléphone invalide.' };
            if (!isValidMessage(message)) return { success: false, message: 'Message invalide (2-1000 caractères, pas de code HTML).' };
            if (!checkRateLimit(email)) return { success: false, message: '' };
        
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrf) return { success: false, message: 'Erreur de sécurité. Rechargez la page.' };
        
            // Nettoyage final
            const cleanNom = sanitize(nom, MAX_NAME_LENGTH);
            const cleanEmail = email.trim().substring(0, MAX_EMAIL_LENGTH);
            const cleanTel = sanitize(telephone, MAX_PHONE_LENGTH);
            const cleanMsg = sanitize(message, MAX_MESSAGE_LENGTH);
        
            try {
                const res = await fetch('/contact/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        nom: cleanNom, 
                        email: cleanEmail, 
                        telephone: cleanTel, 
                        message: cleanMsg 
                    })
                });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return await res.json();
            } catch(e) {
                console.warn('[Chat] sendMessageAPI error:', e.message);
                return { success: false, message: 'Erreur réseau. Vérifiez votre connexion.' };
            }
        }
        
        /* ============================================================
           FORMULAIRE INITIAL
        ============================================================ */
        async function submitInitForm() {
            if (isSending) return;
            
            const nom = document.getElementById('initNom').value.trim();
            const email = document.getElementById('initEmail').value.trim();
            const tel = document.getElementById('initTel').value.trim();
            const msg = document.getElementById('initMessage').value.trim();
            
            if (!nom || !email || !msg) {
                flashError('Merci de remplir tous les champs obligatoires.');
                return;
            }
        
            const btn = document.getElementById('initSendBtn');
            isSending = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Envoi...';
            btn.disabled = true;
        
            const result = await sendMessageAPI(nom, email, tel, msg);
            
            if (result.success) {
                currentEmail = email.trim().substring(0, MAX_EMAIL_LENGTH);
                currentNom   = nom.trim().substring(0, MAX_NAME_LENGTH);
                switchToConversationMode();
                await new Promise(r => setTimeout(r, 500));
                lastMessagesHash = '';
                await loadMessages(true);
                startPolling();
                setupEchoListener();
            } else if (result.message) {
                flashError(result.message);
            }
        
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation';
            btn.disabled = false;
            isSending = false;
        }
        
        /* ============================================================
           MESSAGE RAPIDE
        ============================================================ */
        async function sendQuickMessage() {
            if (isSending) return;
            
            const ta = document.getElementById('chatTextarea');
            const msg = ta.value.trim();
            if (!msg || !currentEmail) return;
            
            const btn = document.getElementById('chatSendBtn');
            isSending = true;
            btn.disabled = true;
            ta.value = '';
            ta.style.height = 'auto';
        
            const result = await sendMessageAPI(currentNom, currentEmail, '', msg);
            
            if (result.success) {
                lastMessagesHash = '';
                await loadMessages(true);
            } else if (result.message) {
                flashError(result.message);
            }
            
            btn.disabled = false;
            isSending = false;
        }
        
        /* ============================================================
           MODE CONVERSATION
        ============================================================ */
        function switchToConversationMode() {
            document.getElementById('chatInitForm').style.display = 'none';
            document.getElementById('chatInputArea').style.display = 'flex';
            document.getElementById('changeIdentityBar').style.display = 'block';
        }
        
        function resetChat() {
            currentEmail = '';
            currentNom = '';
            lastMessageId = null;
            lastMessagesHash = '';
            unreadCount = 0;
            updateBadge();
            stopPolling();
            echoListenerSet = false;
            document.getElementById('chatInitForm').style.display = 'block';
            document.getElementById('chatInputArea').style.display = 'none';
            document.getElementById('changeIdentityBar').style.display = 'none';
            const area = document.getElementById('chatMessagesArea');
            if (area) area.innerHTML = '';
            ['initNom','initEmail','initTel','initMessage'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            const btn = document.getElementById('initSendBtn');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation';
                btn.disabled = false;
            }
            isSending = false;
        }
        
        /* ============================================================
           WEBSOCKET ECHO
        ============================================================ */
        let wsRetryCount = 0;
        
        function setupEchoListener() {
            if (echoListenerSet) return;
        
            function trySetup() {
                if (window.Echo) {
                    console.log('[Chat] Echo trouvé, abonnement...');
                    if (window.echoChannel) {
                        window.Echo.leaveChannel('new-messages');
                    }
                    window.echoChannel = window.Echo.channel('new-messages');
                    window.echoChannel.listen('NewMessageReceived', (event) => {
                        console.log('[Chat] Événement reçu:', event);
                        if (currentEmail && currentEmail === event.email_client) {
                            lastMessagesHash = '';
                            loadMessages(true);
                            
                            if (document.getElementById('chatModal').classList.contains('active')) {
                                playNotifSound();
                            } else {
                                unreadCount++;
                                updateBadge();
                                showRobotNotification();
                                playNotifSound();
                            }
                        }
                    });
                    echoListenerSet = true;
                    console.info('[Chat] WebSocket actif');
                } else {
                    wsRetryCount++;
                    if (wsRetryCount < 30) {
                        setTimeout(trySetup, 500);
                    } else {
                        console.warn('[Chat] Echo non disponible, polling uniquement');
                    }
                }
            }
            trySetup();
        }
        
        setupEchoListener();
        
        /* ============================================================
           FORMULAIRE CONTACT FOOTER
        ============================================================ */
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
                switchToConversationMode();
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
        
        function openChatFromModal(type) {
            const messages = {
                intra: 'Bonjour, je souhaite discuter d\'une formation intra-entreprise sur mesure. Pouvez-vous me contacter ?',
                accompagnement: 'Bonjour, je souhaite obtenir un devis pour un accompagnement ou un audit. Pouvez-vous me contacter ?'
            };
            closeExpertiseModal(type);
            setTimeout(() => {
                if (currentEmail) {
                    switchToConversationMode();
                    const ta = document.getElementById('chatTextarea');
                    if (ta) { ta.value = messages[type] || ''; setTimeout(() => ta.focus(), 100); }
                } else {
                    const im = document.getElementById('initMessage');
                    if (im) im.value = messages[type] || '';
                    setTimeout(() => { const n = document.getElementById('initNom'); if (n) n.focus(); }, 100);
                }
                openChatModal();
            }, 250);
        }
        
        /* ============================================================
           MODALE CATALOGUE
        ============================================================ */
        function openModal(catalogueId) {
            const safeId = parseInt(catalogueId, 10);
            if (!safeId || safeId <= 0) return;
            const modal = document.getElementById('syllabusModal');
            const contentDiv = document.getElementById('modalContent');
            const titleSpan = document.getElementById('modalTitle');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            contentDiv.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-green-700"></i><p class="mt-2">Chargement...</p></div>';
        
            fetch(`/api/catalogue/${safeId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
                .then(data => {
                    contentDiv.innerHTML = '';
                    if (data.image) {
                        const img = document.createElement('img');
                        img.src = data.image_url;
                        img.className = 'w-full rounded-lg mb-6';
                        img.alt = escapeHtml(data.titre || '');
                        contentDiv.appendChild(img);
                    }
                    [
                        { label: '📘 Description', val: data.description },
                        { label: '🎯 Objectifs', val: data.objectifs },
                        { label: '👥 Public visé', val: data.public_vise },
                        { label: '📚 Programme détaillé', val: data.programme }
                    ].forEach(({ label, val }) => {
                        const h = document.createElement('h2');
                        h.className = 'text-xl font-semibold text-green-700 mt-4 mb-2';
                        h.textContent = label;
                        const p = document.createElement('div');
                        p.className = 'text-gray-700';
                        p.innerHTML = escapeHtml(val || 'Non renseigné').replace(/\n/g, '<br>');
                        contentDiv.append(h, p);
                    });
                    const actions = document.createElement('div');
                    actions.className = 'mt-6 flex flex-wrap gap-4';
                    if (data.fichier_pdf) {
                        const dl = document.createElement('a');
                        dl.href = data.fichier_url;
                        dl.target = '_blank';
                        dl.rel = 'noopener noreferrer';
                        dl.className = 'bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2';
                        dl.innerHTML = '<i class="fas fa-download"></i>';
                        const sp = document.createElement('span');
                        sp.textContent = 'Télécharger le syllabus';
                        dl.appendChild(sp);
                        actions.appendChild(dl);
                    }
                    const devis = document.createElement('a');
                    devis.href = '#contact';
                    devis.className = 'bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-flex items-center gap-2';
                    devis.onclick = () => closeModal();
                    devis.innerHTML = '<i class="fas fa-file-invoice-dollar"></i>';
                    const sp2 = document.createElement('span');
                    sp2.textContent = 'Demander un devis';
                    devis.appendChild(sp2);
                    actions.appendChild(devis);
                    contentDiv.appendChild(actions);
                    titleSpan.textContent = escapeHtml(data.titre || 'Syllabus');
                })
                .catch(() => {
                    contentDiv.innerHTML = '<div class="text-red-600 text-center py-8">Erreur de chargement. Réessayez.</div>';
                });
        }
        
        function closeModal() {
            const m = document.getElementById('syllabusModal');
            if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
        }
        
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
           NOTIFICATIONS
        ============================================================ */
        function flashError(msg) {
            if (!msg) return;
            let el = document.getElementById('chatFlashError');
            if (!el) {
                el = document.createElement('div');
                el.id = 'chatFlashError';
                el.style.cssText = 'background:#fee2e2;color:#b91c1c;padding:8px 12px;border-radius:8px;font-size:12px;margin:0 0 8px;text-align:center;';
                const form = document.getElementById('chatInitForm');
                if (form) form.prepend(el);
            }
            el.textContent = escapeHtml(String(msg).substring(0, 200));
            el.style.display = 'block';
            setTimeout(() => { if (el) el.style.display = 'none'; }, 5000);
        }
        
        /* ============================================================
           EXPOSER LES FONCTIONS NÉCESSAIRES (compatibilité)
        ============================================================ */
        window.openExpertiseModal = openExpertiseModal;
        window.closeExpertiseModal = closeExpertiseModal;
        window.openChatFromModal = openChatFromModal;
        window.openModal = openModal;
        window.closeModal = closeModal;
        window.scrollPartenaire = scrollPartenaire;
        window.scrollAvis = scrollAvis;
        window.submitInitForm = submitInitForm;
        window.sendQuickMessage = sendQuickMessage;
        window.closeChatModal = closeChatModal;
        window.resetChat = resetChat;
        
        /* ============================================================
           INITIALISATION
        ============================================================ */
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('chatSendBtn')?.addEventListener('click', sendQuickMessage);
            document.getElementById('chatTextarea')?.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendQuickMessage();
                }
            });
            document.getElementById('chatTextarea')?.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 100) + 'px';
            });
        });
        
    })();
    </script>
</body>
</html>