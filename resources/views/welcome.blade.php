<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tout Help - Accueil</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>
        /* ========== STYLES EXISTANTS (CHAT - CONSERVÉS) ========== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        .chat-modal { display: none; position: fixed; bottom: 100px; right: 20px; width: 380px; background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 1000; overflow: hidden; }
        .chat-modal.active { display: block; animation: fadeInUp 0.3s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .chat-header { background: #1a3c34; color: white; padding: 15px; text-align: center; font-weight: bold; }
        .chat-body { padding: 20px; max-height: 500px; overflow-y: auto; }
        .chat-footer { background: #f3f4f6; padding: 10px; text-align: center; font-size: 12px; color: gray; }
        .robot-icon { position: fixed; bottom: 20px; right: 20px; left: auto !important; background: #1a3c34; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.3s ease; z-index: 9999; }
        .robot-icon:hover { transform: scale(1.1); background: #0f2b24; }
        .robot-icon i { font-size: 30px; color: white; }
        .robot-badge { position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; font-size: 11px; font-weight: bold; min-width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0 5px; border: 2px solid white; }
        .form-input, .form-textarea { width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .form-input:focus, .form-textarea:focus { outline: none; border-color: #1a3c34; }
        .btn-send { background: #1a3c34; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; width: 100%; font-weight: bold; }
        .btn-send:hover { background: #0f2b24; }
        .hidden { display: none; }
        .hero-gradient { background: linear-gradient(135deg, #0a2e25 0%, #1a5c4a 100%); }
        .badge-expertise { transition: all 0.3s ease; }
        .badge-expertise:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .modal-expertise { transition: opacity 0.3s ease; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        /* Animation pour les messages */
        .chat-message { animation: messageAppear 0.2s ease-out; }
        @keyframes messageAppear { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-white">

    <!-- En-tête -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Logo" class="h-12">
                <span class="text-xl font-bold text-green-900">TOUT HELP</span>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="#accueil" class="text-gray-700 hover:text-green-800 font-medium">ACCUEIL</a>
                <a href="#expertise" class="text-gray-700 hover:text-green-800 font-medium">EXPERTISE</a>
                <a href="#catalogue" class="text-gray-700 hover:text-green-800 font-medium">CATALOGUE</a>
                <a href="#blog" class="text-gray-700 hover:text-green-800 font-medium">BLOG</a>
                <a href="#contact" class="text-gray-700 hover:text-green-800 font-medium">CONTACT</a>
            </nav>
        </div>
    </header>

    <!-- SECTION ACCUEIL -->
    <section id="accueil" class="hero-gradient text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block bg-white/20 backdrop-blur-sm rounded-full px-4 py-1 text-sm mb-6">🔥 Formation - Accompagnement - Audit</div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">ENSEMBLE FAISONS DE LA<br>PERFORMANCE UNE CULTURE</h1>
                <p class="text-xl md:text-2xl mb-8 text-green-100">Expert en <span class="font-semibold">Solutions RH</span> et <span class="font-semibold">Accompagnement sur-mesure</span> à Madagascar.</p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <div class="relative">
                        <input type="text" placeholder="Trouver une formation..." class="px-6 py-3 rounded-full w-80 text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button class="bg-green-600 hover:bg-green-500 text-white px-8 py-3 rounded-full transition font-semibold">Rechercher</button>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== SECTION EXPERTISE (3 cartes) ==================== -->
    <section id="expertise" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">EXPERTISE SUR-MESURE</h2>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Des solutions adaptées à chaque besoin</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Carte 1 : FORMATIONS INTER-ENTREPRISES -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-100">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-2xl text-blue-700"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">FORMATIONS INTER-ENTREPRISES</h3>
                        <p class="text-sm text-gray-500 uppercase mb-3">GRAND PUBLIC / SESSION PUBLIQUE</p>
                        <p class="text-gray-600 text-sm">
                            Sessions ouvertes à tous, favorisant le partage d’expériences entre professionnels de différents horizons.
                        </p>
                        <button onclick="openExpertiseModal('inter')" class="mt-4 inline-block bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition">
                            En savoir plus
                        </button>
                    </div>
                </div>

                <!-- Carte 2 : FORMATIONS INTRA-ENTREPRISE -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-100">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-building text-2xl text-green-700"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">FORMATIONS INTRA-ENTREPRISE</h3>
                        <p class="text-sm text-gray-500 uppercase mb-3">SUR-MESURE / PRIVÉ</p>
                        <p class="text-gray-600 text-sm">
                            Formations personnalisées adaptées à la culture et aux besoins spécifiques de votre organisation.
                        </p>
                        <button onclick="openExpertiseModal('intra')" class="mt-4 inline-block bg-green-700 hover:bg-green-800 text-white font-semibold py-2 px-4 rounded-lg transition">
                            En savoir plus
                        </button>
                    </div>
                </div>

                <!-- Carte 3 : ACCOMPAGNEMENT & AUDIT -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-100">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clipboard-list text-2xl text-purple-700"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">ACCOMPAGNEMENT & AUDIT</h3>
                        <p class="text-sm text-gray-500 uppercase mb-3">STRUCTURER – ÉVALUER – PROGRESSER</p>
                        <p class="text-gray-600 text-sm">
                            De la mise en place de vos systèmes à l’évaluation de vos pratiques, une approche claire et progressive.
                        </p>
                        <button onclick="openExpertiseModal('accompagnement')" class="mt-4 inline-block bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 px-4 rounded-lg transition">
                            En savoir plus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== MODALES POUR LES EXPERTISES (textes complets) ==================== -->

    <!-- MODALE : FORMATIONS INTER-ENTREPRISES -->
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
                    <ul class="list-disc pl-6">
                        <li>Sessions publiques ouvertes</li>
                        <li>Séminaires thématiques</li>
                        <li>Journées de sensibilisation</li>
                        <li>Portes ouvertes</li>
                        <li>Calendrier de formations planifiées</li>
                    </ul>
                    <h3 class="text-lg font-bold mt-4">🎯 Pour qui ?</h3>
                    <p>Ces formations s’adressent aux professionnels souhaitant : renforcer leurs compétences, mettre à jour leurs connaissances, s’initier à de nouvelles pratiques, échanger avec d’autres acteurs du même secteur.</p>
                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="{{ route('calendrier') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded-lg" onclick="closeExpertiseModal('inter')">
                            📅 Voir le calendrier des formations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALE : FORMATIONS INTRA-ENTREPRISE -->
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
                    <ul class="list-disc pl-6">
                        <li>Formations personnalisées selon vos besoins</li>
                        <li>Thématiques pluridisciplinaires et variées</li>
                        <li>Adaptation des contenus à votre secteur d’activité</li>
                        <li>Cas pratiques basés sur votre environnement réel</li>
                        <li>Animation sur site ou en format dédié</li>
                        <li>Accompagnement sur des problématiques spécifiques</li>
                    </ul>
                    <h3 class="text-lg font-bold mt-4">🎯 Résultat</h3>
                    <p>L’objectif est de proposer des formations utiles, compréhensibles et immédiatement applicables par vos équipes. Des formations adaptées, pertinentes et alignées avec vos objectifs, qui permettent à vos équipes de progresser efficacement dans leur environnement de travail.</p>
                    <div class="mt-6 flex flex-wrap gap-4">
                        <!-- Bouton modifié : ouvre le chat avec message pré-rempli -->
                        <button onclick="openChatForIntra()" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg">
                            💬 Discuter de votre besoin
                        </button>
                        <a href="#catalogue" class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-2 rounded-lg" onclick="closeExpertiseModal('intra')">
                            📚 Parcourir nos catalogues
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALE : ACCOMPAGNEMENT & AUDIT (texte complet) -->
    <div id="modalAccompagnement" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4 modal-expertise">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">Accompagnement & Audit</h3>
                <button onclick="closeExpertiseModal('accompagnement')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <div class="prose max-w-none">
                    <p>Structurer son organisation et en évaluer l’efficacité vont de pair. Nous vous accompagnons à chaque étape, de la mise en place de vos systèmes jusqu’à l’évaluation de vos pratiques, avec une approche claire, progressive et adaptée à votre réalité.</p>

                    <!-- BLOC 1 : ACCOMPAGNEMENT -->
                    <div class="mt-6 border-l-4 border-green-600 pl-4">
                        <h3 class="text-xl font-bold text-green-700 flex items-center gap-2"><i class="fas fa-cogs"></i> Accompagnement</h3>
                        <p>Nous vous accompagnons dans la mise en place et la structuration de vos systèmes de management, en veillant à ce qu’ils soient simples, adaptés et réellement utilisables par vos équipes.</p>
                        <h4 class="font-semibold mt-2">Ce qu'on vous propose :</h4>
                        <ul class="list-disc pl-6">
                            <li>Mise en place de systèmes (ISO, HSE, SMSST, RSE…)</li>
                            <li>Structuration organisationnelle</li>
                            <li>Rédaction de processus et procédures</li>
                            <li>Élaboration de documents opérationnels</li>
                            <li>Appui et suivi jusqu’à la mise en œuvre</li>
                        </ul>
                        <div class="bg-green-50 p-3 rounded-lg mt-2">
                            <p class="font-semibold text-green-800">Résultat :</p>
                            <p class="text-green-700">Un système clair, structuré et adapté à votre fonctionnement.</p>
                        </div>
                    </div>

                    <!-- BLOC 2 : AUDIT -->
                    <div class="mt-6 border-l-4 border-blue-600 pl-4">
                        <h3 class="text-xl font-bold text-blue-700 flex items-center gap-2"><i class="fas fa-search"></i> Audit</h3>
                        <p>Nous réalisons des audits pour vous aider à prendre du recul, identifier les écarts et définir des axes d’amélioration concrets.</p>
                        <h4 class="font-semibold mt-2">Types d’audit :</h4>
                        <ul class="list-disc pl-6">
                            <li>Audit interne</li>
                            <li>Audit à blanc (pré-certification)</li>
                            <li>Diagnostic organisationnel</li>
                            <li>Évaluation de conformité</li>
                        </ul>
                        <div class="bg-blue-50 p-3 rounded-lg mt-2">
                            <p class="font-semibold text-blue-800">Résultat :</p>
                            <p class="text-blue-700">Une vision claire de votre situation et des actions concrètes pour progresser.</p>
                        </div>
                    </div>

                    <!-- PETITE SECTION COMPLÉMENTAIRE -->
                    <div class="mt-6 p-4 bg-gray-100 rounded-lg text-center">
                        <p><i class="fas fa-sync-alt text-green-600 mr-2"></i> L’accompagnement et l’audit sont complémentaires. Nous pouvons intervenir à différentes étapes selon vos besoins : avant, pendant ou après la mise en place de votre système.</p>
                    </div>

                    <div class="mt-6 flex justify-center">
                        <a href="#contact" class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded-lg" onclick="closeExpertiseModal('accompagnement')">
                            📋 Demander un devis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION CATALOGUE -->
    <section id="catalogue" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Nos catalogues de formation</h2>
                <p class="text-gray-600">Découvrez l'ensemble de nos syllabus. Cliquez sur "En savoir plus" pour voir le programme complet.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($catalogues as $catalogue)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition flex flex-col">
                    @if($catalogue->image)<img src="{{ asset('storage/'.$catalogue->image) }}" class="w-full h-48 object-cover" alt="{{ $catalogue->titre }}">@endif
                    <div class="p-6 flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $catalogue->titre }}</h3>
                        <p class="text-gray-600 line-clamp-3">{{ Str::limit($catalogue->description, 120) }}</p>
                        <button onclick="openModal({{ $catalogue->id }})" class="mt-4 inline-block bg-green-700 hover:bg-green-800 text-white font-semibold py-2 px-4 rounded-lg transition">En savoir plus</button>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 text-gray-500">Aucun catalogue disponible.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ==================== SECTION PARTENAIRES ==================== -->
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
                        @if($partenaire->logo)
                            <img src="{{ asset('storage/' . $partenaire->logo) }}" alt="{{ $partenaire->nom_entreprise }}" class="max-w-full max-h-full p-2 object-contain">
                        @else
                            <span class="text-gray-400 text-xs">{{ $partenaire->nom_entreprise }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                <button onclick="scrollPartenaire('left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button onclick="scrollPartenaire('right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <p class="text-gray-500">Aucun partenaire pour le moment.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- ==================== SECTION AVIS CLIENTS ==================== -->
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
                        <div class="flex text-yellow-500 mb-3">
                            @for($i=1;$i<=5;$i++)
                                @if($i<=$a->note)★@else☆@endif
                            @endfor
                        </div>
                        <p class="text-gray-600 italic mb-4">"{{ Str::limit($a->contenu, 150) }}"</p>
                        <div class="flex items-center gap-3">
                            @if($a->logo_entreprise)
                                <img src="{{ asset('storage/' . $a->logo_entreprise) }}" class="w-10 h-10 rounded-full object-cover">
                            @endif
                            <div>
                                <p class="font-bold text-gray-800">{{ $a->entreprise_nom }}</p>
                                <p class="text-sm text-gray-500">{{ $a->contact_fonction ?? 'Client' }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button onclick="scrollAvis('left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button onclick="scrollAvis('right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            @else
            <div class="text-center py-12 bg-white rounded-lg">
                <p class="text-gray-500">Aucun avis pour le moment.</p>
            </div>
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
                        <div class="mb-3">@if($article->type=='blog')<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">📝 Blog</span>@elseif($article->type=='reussite')<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">🏆 Réussite</span>@else<span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">🤝 Partenariat</span>@endif</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $article->titre }}</h3>
                        <p class="text-gray-500 text-sm mb-3"><i class="far fa-calendar-alt mr-1"></i> {{ $article->date_publication->format('d/m/Y') }}</p>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($article->extrait ?? $article->contenu, 100) }}</p>
                        <a href="#" class="inline-flex items-center gap-2 text-green-700 font-semibold hover:text-green-800">Lire la suite <i class="fas fa-arrow-right text-sm"></i></a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12"><p class="text-gray-500">Aucun article publié pour le moment.</p></div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION CONTACT -->
    <section id="contact" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">Nous contacter</h2>
            <p class="text-center text-gray-600 mb-12">Une question ? Un projet ? Écrivez-nous !</p>
            <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8">
                <form id="contactForm">
                    @csrf
                    <div class="mb-4"><label class="block text-gray-700 font-bold mb-2">Nom complet</label><input type="text" name="nom" id="contact_nom" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required></div>
                    <div class="mb-4"><label class="block text-gray-700 font-bold mb-2">Email</label><input type="email" name="email" id="contact_email" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required></div>
                    <div class="mb-4"><label class="block text-gray-700 font-bold mb-2">Téléphone</label><input type="text" name="telephone" id="contact_telephone" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div class="mb-4"><label class="block text-gray-700 font-bold mb-2">Message</label><textarea name="message" id="contact_message" rows="5" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required></textarea></div>
                    <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-3 rounded-lg transition">Envoyer le message</button>
                </form>
                <div id="contactSuccess" class="hidden mt-4 p-3 bg-green-100 text-green-700 rounded-lg text-center">Message envoyé avec succès !</div>
                <div id="contactError" class="hidden mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-center">Erreur lors de l'envoi.</div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8"><div class="container mx-auto px-4 text-center"><p>&copy; {{ date('Y') }} Tout Help. Tous droits réservés.</p></div></footer>

    <!-- Robot chat (NE PAS MODIFIER) -->
    <div class="robot-icon" id="robotIcon"><i class="fas fa-robot"></i><span id="robotBadge" class="robot-badge hidden">0</span></div>

    <!-- Modal de chat (NE PAS MODIFIER) -->
    <div class="chat-modal" id="chatModal">
        <div class="chat-header"><i class="fas fa-headset mr-2"></i> Support client</div>
        <div class="chat-body" id="chatBody">
            <div id="chatMessages" class="mb-4 space-y-3"></div>
            <form id="chatForm">
                @csrf
                <input type="text" name="nom" id="nom" placeholder="Votre nom" class="form-input" required>
                <input type="email" name="email" id="email" placeholder="Votre email" class="form-input" required>
                <input type="text" name="telephone" id="telephone" placeholder="Votre téléphone" class="form-input">
                <textarea name="message" id="message" rows="3" placeholder="Votre message..." class="form-textarea" required></textarea>
                <button type="submit" id="sendBtn" class="btn-send">Envoyer</button>
            </form>
        </div>
        <div class="chat-footer">Nous vous répondrons dans les plus brefs délais.</div>
    </div>

    <!-- Modale de détail du syllabus (catalogue) -->
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
        // ========== SCRIPT CHAT (VERSION AMÉLIORÉE DE TON AMI) ==========
        // Configuration Echo
        let echoInitialized = false;
        let echoReady = false;
        
        function initEcho() {
            if (typeof Pusher !== 'undefined' && !window.Echo && !echoInitialized) {
                window.Pusher = Pusher;
                window.Echo = new Echo({
                    broadcaster: 'reverb',
                    key: '{{ env("REVERB_APP_KEY") }}',
                    wsHost: '{{ env("REVERB_HOST", "localhost") }}',
                    wsPort: {{ env("REVERB_PORT", 8080) }},
                    forceTLS: false,
                    enabledTransports: ['ws', 'wss']
                });
                echoInitialized = true;
                console.log('✅ Echo initialisé');
                
                setTimeout(() => {
                    echoReady = true;
                    setupGlobalListener();
                }, 1000);
            } else if (!window.Echo) {
                setTimeout(initEcho, 500);
            } else {
                echoReady = true;
                setupGlobalListener();
            }
        }
        
        function setupGlobalListener() {
            if (!echoReady || !window.Echo) return;
            
            window.Echo.channel('new-messages').listen('NewMessageReceived', (event) => {
                console.log('📩 Message reçu en temps réel:', event);
                if (currentEmail === event.email_client) {
                    loadMessages(currentEmail);
                    playNotificationSound();
                    if (!chatModal.classList.contains('active')) {
                        incrementUnread();
                        showNotification('📩 Nouvelle réponse du support');
                    }
                }
            });
            console.log('✅ Écoute globale activée');
        }
        
        document.addEventListener('DOMContentLoaded', initEcho);
    
        // ========== ÉLÉMENTS DOM ==========
        const robotIcon = document.getElementById('robotIcon');
        const chatModal = document.getElementById('chatModal');
        const chatForm = document.getElementById('chatForm');
        const chatBody = document.getElementById('chatBody');
        const messagesContainer = document.getElementById('chatMessages');
        
        let currentEmail = '';
        let currentNom = '';
        let unreadCount = 0;
    
        // ========== NOTIFICATION SONORE ==========
        let audioContext = null;
        
        function initAudio() {
            if (audioContext) return;
            try {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                audioContext.resume();
            } catch(e) {}
        }
        
        function playNotificationSound() {
            initAudio();
            try {
                if (audioContext && audioContext.state === 'running') {
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    oscillator.frequency.value = 880;
                    gainNode.gain.value = 0.2;
                    oscillator.start();
                    gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 0.3);
                    oscillator.stop(audioContext.currentTime + 0.3);
                }
            } catch(e) {}
        }
        
        document.body.addEventListener('click', initAudio);
        robotIcon.addEventListener('click', initAudio);
    
        // ========== BADGE ==========
        function updateRobotBadge() {
            const badge = document.getElementById('robotBadge');
            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        }
    
        function incrementUnread() {
            unreadCount++;
            updateRobotBadge();
        }
    
        function resetUnread() {
            unreadCount = 0;
            updateRobotBadge();
        }
    
        // ========== AFFICHAGE DES MESSAGES (bulles gauche/droite) ==========
        function displayMessages(messages) {
            if (!messagesContainer) return;
            
            if (!messages || messages.length === 0) {
                messagesContainer.innerHTML = '<div class="text-center text-gray-400 text-sm py-4">Aucun message</div>';
                return;
            }
            
            let html = '';
            for (let msg of messages) {
                // Message du client (ENVOYÉ) - à droite en vert
                html += `
                    <div class="chat-message flex justify-end mb-3">
                        <div class="max-w-[75%] bg-[#1a3c34] text-white rounded-2xl px-4 py-2 shadow-sm">
                            <div class="flex items-center justify-end gap-2 mb-1">
                                <small class="text-xs text-green-200">${new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</small>
                                <strong class="text-xs text-green-200">Moi</strong>
                            </div>
                            <p class="text-sm break-words text-right">${escapeHtml(msg.message)}</p>
                        </div>
                    </div>
                `;
                
                // Réponse du support (REÇU) - à gauche en gris
                if (msg.reponse_admin && msg.reponse_admin.trim() !== '') {
                    html += `
                        <div class="chat-message flex justify-start mb-3">
                            <div class="max-w-[75%] bg-gray-200 text-gray-800 rounded-2xl px-4 py-2 shadow-sm">
                                <div class="flex items-center gap-2 mb-1">
                                    <strong class="text-xs text-gray-600">Support</strong>
                                    <small class="text-xs text-gray-500">${new Date(msg.updated_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</small>
                                </div>
                                <p class="text-sm break-words">${escapeHtml(msg.reponse_admin)}</p>
                            </div>
                        </div>
                    `;
                }
            }
            
            messagesContainer.innerHTML = html;
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    
        // ========== CHARGER LES MESSAGES ==========
        async function loadMessages(email) {
            if (!email) return [];
            
            try {
                const response = await fetch(`/api/messages?email=${encodeURIComponent(email)}`);
                const messages = await response.json();
                displayMessages(messages);
                return messages;
            } catch (error) {
                console.error('Erreur:', error);
                return [];
            }
        }
    
        // ========== ENVOYER UN MESSAGE ==========
        async function sendMessage(email, nom, telephone, message) {
            try {
                const response = await fetch('/contact/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ nom, email, telephone, message })
                });
                return await response.json();
            } catch (error) {
                return { success: false };
            }
        }
    
        // ========== SWITCH VUE CONVERSATION ==========
        function switchToConversation(email, nom) {
            currentEmail = email;
            currentNom = nom;
            
            chatForm.style.display = 'none';
            
            let quickForm = document.getElementById('quickForm');
            if (!quickForm) {
                quickForm = document.createElement('div');
                quickForm.id = 'quickForm';
                quickForm.className = 'mt-3';
                quickForm.innerHTML = `
                    <div class="flex gap-2">
                        <textarea id="quickMessage" rows="2" placeholder="Écrivez votre message..." class="flex-1 p-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" style="resize: none;"></textarea>
                        <button id="quickSendBtn" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                `;
                chatBody.appendChild(quickForm);
                
                document.getElementById('quickSendBtn').onclick = async () => {
                    const msg = document.getElementById('quickMessage').value.trim();
                    if (!msg) return;
                    
                    const btn = document.getElementById('quickSendBtn');
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    btn.disabled = true;
                    
                    const result = await sendMessage(currentEmail, currentNom, '', msg);
                    if (result.success) {
                        document.getElementById('quickMessage').value = '';
                        await loadMessages(currentEmail);
                        showNotification('Message envoyé');
                    }
                    
                    btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                    btn.disabled = false;
                };
                
                document.getElementById('quickMessage').addEventListener('keypress', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        document.getElementById('quickSendBtn').click();
                    }
                });
            }
            
            let changeBtn = document.getElementById('changeIdentity');
            if (!changeBtn) {
                changeBtn = document.createElement('button');
                changeBtn.id = 'changeIdentity';
                changeBtn.className = 'text-xs text-gray-500 mt-2 hover:text-gray-700 block transition';
                changeBtn.innerHTML = '<i class="fas fa-user-edit"></i> Changer d\'identité';
                changeBtn.onclick = () => {
                    currentEmail = '';
                    currentNom = '';
                    chatForm.style.display = 'block';
                    quickForm.style.display = 'none';
                    changeBtn.style.display = 'none';
                    messagesContainer.innerHTML = '';
                };
                chatBody.appendChild(changeBtn);
            }
            
            quickForm.style.display = 'block';
            changeBtn.style.display = 'block';
        }
    
        // ========== ÉVÉNEMENTS ==========
        robotIcon.onclick = async () => {
            chatModal.classList.toggle('active');
            if (chatModal.classList.contains('active')) {
                resetUnread();
                if (currentEmail) {
                    await loadMessages(currentEmail);
                }
            }
        };
    
        window.onclick = (e) => {
            if (!chatModal.contains(e.target) && !robotIcon.contains(e.target)) {
                chatModal.classList.remove('active');
            }
        };
    
        chatForm.onsubmit = async (e) => {
            e.preventDefault();
            
            const nom = document.getElementById('nom').value;
            const email = document.getElementById('email').value;
            const telephone = document.getElementById('telephone').value;
            const message = document.getElementById('message').value;
            
            if (!nom || !email || !message) {
                showNotification('Tous les champs sont requis', 'error');
                return;
            }
            
            const btn = document.getElementById('sendBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
            btn.disabled = true;
            
            const result = await sendMessage(email, nom, telephone, message);
            
            if (result.success) {
                document.getElementById('message').value = '';
                const messages = await loadMessages(email);
                if (messages.length > 0) {
                    switchToConversation(email, nom);
                }
                showNotification('Message envoyé !');
            } else {
                showNotification('Erreur', 'error');
            }
            
            btn.innerHTML = 'Envoyer';
            btn.disabled = false;
        };
    
        // ========== UTILITAIRES ==========
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function showNotification(msg, type = 'success') {
            let notif = document.getElementById('chatNotification');
            if (!notif) {
                notif = document.createElement('div');
                notif.id = 'chatNotification';
                notif.style.cssText = 'position:fixed;bottom:100px;right:20px;padding:10px 15px;border-radius:8px;z-index:1002;font-size:13px;background:#333;color:white;z-index:10001';
                document.body.appendChild(notif);
            }
            notif.textContent = msg;
            notif.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
            notif.style.display = 'block';
            setTimeout(() => notif.style.display = 'none', 3000);
        }

        // ========== FONCTIONS POUR LES MODALES D'EXPERTISE ==========
        function openExpertiseModal(type) {
            let modalId = '';
            if (type === 'inter') modalId = 'modalInter';
            else if (type === 'intra') modalId = 'modalIntra';
            else if (type === 'accompagnement') modalId = 'modalAccompagnement';
            const modal = document.getElementById(modalId);
            if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        }
        function closeExpertiseModal(type) {
            let modalId = '';
            if (type === 'inter') modalId = 'modalInter';
            else if (type === 'intra') modalId = 'modalIntra';
            else if (type === 'accompagnement') modalId = 'modalAccompagnement';
            const modal = document.getElementById(modalId);
            if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
        }

        // ========== FONCTION POUR LE BOUTON "DISCUTER DE VOTRE BESOIN" ==========
        function openChatForIntra() {
            closeExpertiseModal('intra');
            const robotIconElem = document.getElementById('robotIcon');
            if (robotIconElem) robotIconElem.click();
            setTimeout(() => {
                let messageField = document.getElementById('quickMessage');
                if (!messageField) {
                    messageField = document.getElementById('message');
                }
                if (messageField) {
                    messageField.value = "Bonjour, je souhaite discuter d'une formation intra-entreprise personnalisée. Pouvez-vous me contacter ?";
                    messageField.focus();
                }
            }, 300);
        }

        // ========== FONCTIONS POUR LA MODALE CATALOGUE ==========
        function openModal(catalogueId) {
            const modal = document.getElementById('syllabusModal');
            const contentDiv = document.getElementById('modalContent');
            const titleSpan = document.getElementById('modalTitle');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            contentDiv.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-green-700"></i><p class="mt-2">Chargement...</p></div>';
            fetch(`/api/catalogue/${catalogueId}`)
                .then(response => response.json())
                .then(data => {
                    let html = `<div class="prose max-w-none">${data.image ? `<img src="${data.image_url}" class="w-full rounded-lg mb-6" alt="${data.titre}">` : ''}<h2 class="text-xl font-semibold text-green-700">📘 Description</h2><p>${data.description || 'Non renseignée'}</p><h2 class="text-xl font-semibold text-green-700 mt-4">🎯 Objectifs</h2><div>${data.objectifs ? data.objectifs.replace(/\n/g, '<br>') : 'Non renseignés'}</div><h2 class="text-xl font-semibold text-green-700 mt-4">👥 Public visé</h2><p>${data.public_vise || 'Non renseigné'}</p><h2 class="text-xl font-semibold text-green-700 mt-4">📚 Programme détaillé</h2><div>${data.programme ? data.programme.replace(/\n/g, '<br>') : 'Non renseigné'}</div></div><div class="mt-6 flex flex-wrap gap-4">${data.fichier_pdf ? `<a href="${data.fichier_url}" target="_blank" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg"><i class="fas fa-download mr-2"></i>Télécharger le syllabus</a>` : ''}<a href="#contact" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg" onclick="closeModal()"><i class="fas fa-file-invoice-dollar mr-2"></i>Demander un devis</a></div>`;
                    contentDiv.innerHTML = html;
                    titleSpan.innerText = data.titre;
                })
                .catch(error => { contentDiv.innerHTML = '<div class="text-red-600 text-center py-8">Erreur de chargement</div>'; console.error(error); });
        }
        function closeModal() { const modal = document.getElementById('syllabusModal'); modal.classList.add('hidden'); modal.classList.remove('flex'); }

        // ========== FONCTIONS CARROUSELS PARTENAIRES ET AVIS ==========
        function scrollPartenaire(direction) {
            const container = document.getElementById('partenairesScroll');
            if (!container) return;
            const scrollAmount = 300;
            container.scrollBy({ left: direction === 'left' ? -scrollAmount : scrollAmount, behavior: 'smooth' });
        }
        function scrollAvis(direction) {
            const container = document.getElementById('avisScroll');
            if (!container) return;
            const scrollAmount = 350;
            container.scrollBy({ left: direction === 'left' ? -scrollAmount : scrollAmount, behavior: 'smooth' });
        }
    </script>
</body>
</html>