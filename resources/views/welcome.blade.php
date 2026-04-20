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
        .line-clamp-4 { display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
        .modal-expertise { transition: opacity 0.3s ease; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .chat-message { animation: messageAppear 0.2s ease-out; }
        @keyframes messageAppear { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .container-fluid { width: 100%; padding-right: 2rem; padding-left: 2rem; margin-right: auto; margin-left: auto; }
        @media (min-width: 768px) { .container-fluid { padding-right: 4rem; padding-left: 4rem; } }
        @media (min-width: 1024px) { .container-fluid { padding-right: 6rem; padding-left: 6rem; } }
    </style>
</head>
<body class="bg-white">

    <!-- En-tête -->
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
                <a href="#contact" class="text-gray-700 hover:text-green-800 font-medium text-lg">CONTACT</a>
            </nav>
        </div>
    </header>

    <!-- SECTION ACCUEIL (agrandie) -->
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
    <!-- SECTION EXPERTISE (cartes agrandies et égalisées) -->
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

    <!-- MODALES (inchangées, mais conservées) -->
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
                    <p>L’objectif est de proposer des formations utiles, compréhensibles et immédiatement applicables par vos équipes. Des formations adaptées, pertinentes et alignées avec vos objectifs, qui permettent à vos équipes de progresser efficacement dans leur environnement de travail.</p>
                    <div class="mt-6 flex flex-wrap gap-4">
                        <button onclick="openChatForIntra()" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg">💬 Discuter de votre besoin</button>
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
                    <div class="mt-6 border-l-4 border-green-600 pl-4"><h3 class="text-xl font-bold text-green-700 flex items-center gap-2"><i class="fas fa-cogs"></i> Accompagnement</h3><p>Nous vous accompagnons dans la mise en place et la structuration de vos systèmes de management, en veillant à ce qu’ils soient simples, adaptés et réellement utilisables par vos équipes.</p><h4 class="font-semibold mt-2">Ce qu'on vous propose :</h4><ul class="list-disc pl-6"><li>Mise en place de systèmes (ISO, HSE, SMSST, RSE…)</li><li>Structuration organisationnelle</li><li>Rédaction de processus et procédures</li><li>Élaboration de documents opérationnels</li><li>Appui et suivi jusqu’à la mise en œuvre</li></ul><div class="bg-green-50 p-3 rounded-lg mt-2"><p class="font-semibold text-green-800">Résultat :</p><p class="text-green-700">Un système clair, structuré et adapté à votre fonctionnement.</p></div></div>
                    <div class="mt-6 border-l-4 border-blue-600 pl-4"><h3 class="text-xl font-bold text-blue-700 flex items-center gap-2"><i class="fas fa-search"></i> Audit</h3><p>Nous réalisons des audits pour vous aider à prendre du recul, identifier les écarts et définir des axes d’amélioration concrets.</p><h4 class="font-semibold mt-2">Types d’audit :</h4><ul class="list-disc pl-6"><li>Audit interne</li><li>Audit à blanc (pré-certification)</li><li>Diagnostic organisationnel</li><li>Évaluation de conformité</li></ul><div class="bg-blue-50 p-3 rounded-lg mt-2"><p class="font-semibold text-blue-800">Résultat :</p><p class="text-blue-700">Une vision claire de votre situation et des actions concrètes pour progresser.</p></div></div>
                    <div class="mt-6 p-4 bg-gray-100 rounded-lg text-center"><p><i class="fas fa-sync-alt text-green-600 mr-2"></i> L’accompagnement et l’audit sont complémentaires. Nous pouvons intervenir à différentes étapes selon vos besoins : avant, pendant ou après la mise en place de votre système.</p></div>
                    <div class="mt-6 flex justify-center"><a href="#contact" class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded-lg" onclick="closeExpertiseModal('accompagnement')">📋 Demander un devis</a></div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION CATALOGUE (agrandie) -->
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

    <!-- SECTION PARTENAIRES (agrandie) -->
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
                <button onclick="scrollPartenaire('left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <button onclick="scrollPartenaire('right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
            </div>
            @else
            <div class="text-center py-20 bg-gray-50 rounded-2xl"><p class="text-gray-500 text-xl">Aucun partenaire pour le moment.</p></div>
            @endif
        </div>
    </section>

    <!-- SECTION AVIS CLIENTS (agrandie) -->
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
                <button onclick="scrollAvis('left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <button onclick="scrollAvis('right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-3 shadow-md opacity-0 group-hover:opacity-100 transition"><svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
            </div>
            <div class="text-center mt-12"><a href="{{ route('avis.create') }}" class="inline-block bg-green-700 hover:bg-green-800 text-white font-bold py-4 px-10 rounded-xl transition text-lg shadow-md">✍️ Donnez votre avis</a></div>
            @else
            <div class="text-center py-20 bg-white rounded-2xl"><p class="text-gray-500 text-xl">Aucun avis pour le moment.</p></div>
            @endif
        </div>
    </section>

    <!-- SECTION BLOG (agrandie) -->
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

    <!-- FOOTER (agrandi) -->
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
                        <div class="mb-6"><input type="text" name="nom" placeholder="Nom complet" class="w-full px-6 py-5 text-lg rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" required></div>
                        <div class="mb-6"><input type="email" name="email" placeholder="Email" class="w-full px-6 py-5 text-lg rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" required></div>
                        <div class="mb-6"><input type="text" name="telephone" placeholder="Téléphone" class="w-full px-6 py-5 text-lg rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400"></div>
                        <div class="mb-8"><textarea name="message" rows="5" placeholder="Votre message..." class="w-full px-6 py-5 text-lg rounded-xl bg-white/10 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400" required></textarea></div>
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-rose-800 font-bold py-5 rounded-xl transition text-2xl">Envoyer le message</button>
                    </form>
                    <div id="footerContactSuccess" class="hidden mt-6 text-green-300 text-lg text-center"></div>
                    <div id="footerContactError" class="hidden mt-6 text-red-300 text-lg text-center"></div>
                    <div class="mt-12 flex flex-col sm:flex-row justify-center lg:justify-start gap-6">
                        <a href="https://www.facebook.com/ToutHelp" target="_blank" class="flex items-center gap-3 bg-[#1877F2] hover:bg-[#0e63cf] px-6 py-3 rounded-full transition"><i class="fab fa-facebook-f text-2xl"></i><span class="text-xl font-semibold">Tout help</span></a>
                        @php $contactEmail = \App\Models\Setting::get('contact_email', 'contact@touthelp.com'); @endphp
                        <div class="flex items-center gap-3 bg-white/10 px-6 py-3 rounded-full"><i class="fas fa-envelope text-2xl text-red-400"></i><span class="text-xl">{{ $contactEmail }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @vite(['resources/js/app.js'])
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // ========== SCRIPT CHAT (version conservée et agrandie, aucune modification fonctionnelle) ==========
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
                setTimeout(() => { echoReady = true; setupGlobalListener(); }, 1000);
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
    
        const robotIcon = document.getElementById('robotIcon');
        const chatModal = document.getElementById('chatModal');
        const chatForm = document.getElementById('chatForm');
        const chatBody = document.getElementById('chatBody');
        const messagesContainer = document.getElementById('chatMessages');
        
        let currentEmail = '';
        let currentNom = '';
        let unreadCount = 0;
    
        let audioContext = null;
        function initAudio() { if (audioContext) return; try { audioContext = new (window.AudioContext || window.webkitAudioContext)(); audioContext.resume(); } catch(e) {} }
        function playNotificationSound() { initAudio(); try { if (audioContext && audioContext.state === 'running') { const oscillator = audioContext.createOscillator(); const gainNode = audioContext.createGain(); oscillator.connect(gainNode); gainNode.connect(audioContext.destination); oscillator.frequency.value = 880; gainNode.gain.value = 0.2; oscillator.start(); gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 0.3); oscillator.stop(audioContext.currentTime + 0.3); } } catch(e) {} }
        
        document.body.addEventListener('click', initAudio);
        robotIcon.addEventListener('click', initAudio);
    
        function updateRobotBadge() { const badge = document.getElementById('robotBadge'); if (badge) { if (unreadCount > 0) { badge.textContent = unreadCount > 99 ? '99+' : unreadCount; badge.classList.remove('hidden'); } else { badge.classList.add('hidden'); } } }
        function incrementUnread() { unreadCount++; updateRobotBadge(); }
        function resetUnread() { unreadCount = 0; updateRobotBadge(); }
    
        function displayMessages(messages) {
            if (!messagesContainer) return;
            if (!messages || messages.length === 0) { messagesContainer.innerHTML = '<div class="text-center text-gray-400 text-sm py-4">Aucun message</div>'; return; }
            let html = '';
            for (let msg of messages) {
                html += `<div class="chat-message flex justify-end mb-3"><div class="max-w-[75%] bg-[#1a3c34] text-white rounded-2xl px-4 py-2 shadow-sm"><div class="flex items-center justify-end gap-2 mb-1"><small class="text-xs text-green-200">${new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</small><strong class="text-xs text-green-200">Moi</strong></div><p class="text-sm break-words text-right">${escapeHtml(msg.message)}</p></div></div>`;
                if (msg.reponse_admin && msg.reponse_admin.trim() !== '') { html += `<div class="chat-message flex justify-start mb-3"><div class="max-w-[75%] bg-gray-200 text-gray-800 rounded-2xl px-4 py-2 shadow-sm"><div class="flex items-center gap-2 mb-1"><strong class="text-xs text-gray-600">Support</strong><small class="text-xs text-gray-500">${new Date(msg.updated_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</small></div><p class="text-sm break-words">${escapeHtml(msg.reponse_admin)}</p></div></div>`; }
            }
            messagesContainer.innerHTML = html;
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    
        async function loadMessages(email) { if (!email) return []; try { const response = await fetch(`/api/messages?email=${encodeURIComponent(email)}`); const messages = await response.json(); displayMessages(messages); return messages; } catch (error) { return []; } }
        async function sendMessage(email, nom, telephone, message) { try { const response = await fetch('/contact/send', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ nom, email, telephone, message }) }); return await response.json(); } catch (error) { return { success: false }; } }
    
        function switchToConversation(email, nom) {
            currentEmail = email; currentNom = nom; chatForm.style.display = 'none';
            let quickForm = document.getElementById('quickForm');
            if (!quickForm) {
                quickForm = document.createElement('div'); quickForm.id = 'quickForm'; quickForm.className = 'mt-3';
                quickForm.innerHTML = `<div class="flex gap-2"><textarea id="quickMessage" rows="2" placeholder="Écrivez votre message..." class="flex-1 p-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" style="resize: none;"></textarea><button id="quickSendBtn" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition"><i class="fas fa-paper-plane"></i></button></div>`;
                chatBody.appendChild(quickForm);
                document.getElementById('quickSendBtn').onclick = async () => { const msg = document.getElementById('quickMessage').value.trim(); if (!msg) return; const btn = document.getElementById('quickSendBtn'); btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; btn.disabled = true; const result = await sendMessage(currentEmail, currentNom, '', msg); if (result.success) { document.getElementById('quickMessage').value = ''; await loadMessages(currentEmail); showNotification('Message envoyé'); } btn.innerHTML = '<i class="fas fa-paper-plane"></i>'; btn.disabled = false; };
                document.getElementById('quickMessage').addEventListener('keypress', (e) => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); document.getElementById('quickSendBtn').click(); } });
            }
            let changeBtn = document.getElementById('changeIdentity');
            if (!changeBtn) {
                changeBtn = document.createElement('button'); changeBtn.id = 'changeIdentity'; changeBtn.className = 'text-xs text-gray-500 mt-2 hover:text-gray-700 block transition'; changeBtn.innerHTML = '<i class="fas fa-user-edit"></i> Changer d\'identité'; changeBtn.onclick = () => { currentEmail = ''; currentNom = ''; chatForm.style.display = 'block'; quickForm.style.display = 'none'; changeBtn.style.display = 'none'; messagesContainer.innerHTML = ''; };
                chatBody.appendChild(changeBtn);
            }
            quickForm.style.display = 'block'; changeBtn.style.display = 'block';
        }
    
        robotIcon.onclick = async () => { chatModal.classList.toggle('active'); if (chatModal.classList.contains('active')) { resetUnread(); if (currentEmail) await loadMessages(currentEmail); } };
        window.onclick = (e) => { if (!chatModal.contains(e.target) && !robotIcon.contains(e.target)) chatModal.classList.remove('active'); };
    
        chatForm.onsubmit = async (e) => {
            e.preventDefault(); const nom = document.getElementById('nom').value; const email = document.getElementById('email').value; const telephone = document.getElementById('telephone').value; const message = document.getElementById('message').value; if (!nom || !email || !message) { showNotification('Tous les champs sont requis', 'error'); return; } const btn = document.getElementById('sendBtn'); btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...'; btn.disabled = true; const result = await sendMessage(email, nom, telephone, message); if (result.success) { document.getElementById('message').value = ''; const messages = await loadMessages(email); if (messages.length > 0) switchToConversation(email, nom); showNotification('Message envoyé !'); } else { showNotification('Erreur', 'error'); } btn.innerHTML = 'Envoyer'; btn.disabled = false;
        };
    
        function escapeHtml(text) { if (!text) return ''; const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }
        function showNotification(msg, type = 'success') { let notif = document.getElementById('chatNotification'); if (!notif) { notif = document.createElement('div'); notif.id = 'chatNotification'; notif.style.cssText = 'position:fixed;bottom:100px;right:20px;padding:10px 15px;border-radius:8px;z-index:1002;font-size:13px;background:#333;color:white;z-index:10001'; document.body.appendChild(notif); } notif.textContent = msg; notif.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336'; notif.style.display = 'block'; setTimeout(() => notif.style.display = 'none', 3000); }
    
        function openExpertiseModal(type) { let modalId = ''; if (type === 'inter') modalId = 'modalInter'; else if (type === 'intra') modalId = 'modalIntra'; else if (type === 'accompagnement') modalId = 'modalAccompagnement'; const modal = document.getElementById(modalId); if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); } }
        function closeExpertiseModal(type) { let modalId = ''; if (type === 'inter') modalId = 'modalInter'; else if (type === 'intra') modalId = 'modalIntra'; else if (type === 'accompagnement') modalId = 'modalAccompagnement'; const modal = document.getElementById(modalId); if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); } }
    
        function openChatForIntra() { closeExpertiseModal('intra'); const robotIconElem = document.getElementById('robotIcon'); if (robotIconElem) robotIconElem.click(); setTimeout(() => { let messageField = document.getElementById('quickMessage'); if (!messageField) messageField = document.getElementById('message'); if (messageField) { messageField.value = "Bonjour, je souhaite discuter d'une formation intra-entreprise personnalisée. Pouvez-vous me contacter ?"; messageField.focus(); } }, 300); }
    
        function openModal(catalogueId) { const modal = document.getElementById('syllabusModal'); const contentDiv = document.getElementById('modalContent'); const titleSpan = document.getElementById('modalTitle'); modal.classList.remove('hidden'); modal.classList.add('flex'); contentDiv.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-green-700"></i><p class="mt-2">Chargement...</p></div>'; fetch(`/api/catalogue/${catalogueId}`).then(response => response.json()).then(data => { let html = `<div class="prose max-w-none">${data.image ? `<img src="${data.image_url}" class="w-full rounded-lg mb-6" alt="${data.titre}">` : ''}<h2 class="text-xl font-semibold text-green-700">📘 Description</h2><p>${data.description || 'Non renseignée'}</p><h2 class="text-xl font-semibold text-green-700 mt-4">🎯 Objectifs</h2><div>${data.objectifs ? data.objectifs.replace(/\n/g, '<br>') : 'Non renseignés'}</div><h2 class="text-xl font-semibold text-green-700 mt-4">👥 Public visé</h2><p>${data.public_vise || 'Non renseigné'}</p><h2 class="text-xl font-semibold text-green-700 mt-4">📚 Programme détaillé</h2><div>${data.programme ? data.programme.replace(/\n/g, '<br>') : 'Non renseigné'}</div></div><div class="mt-6 flex flex-wrap gap-4">${data.fichier_pdf ? `<a href="${data.fichier_url}" target="_blank" class="bg-green-700 hover:bg-green-800 text-white px-6 py-2 rounded-lg"><i class="fas fa-download mr-2"></i>Télécharger le syllabus</a>` : ''}<a href="#contact" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg" onclick="closeModal()"><i class="fas fa-file-invoice-dollar mr-2"></i>Demander un devis</a></div>`; contentDiv.innerHTML = html; titleSpan.innerText = data.titre; }).catch(error => { contentDiv.innerHTML = '<div class="text-red-600 text-center py-8">Erreur de chargement</div>'; console.error(error); }); }
        function closeModal() { const modal = document.getElementById('syllabusModal'); modal.classList.add('hidden'); modal.classList.remove('flex'); }
    
        function scrollPartenaire(direction) { const container = document.getElementById('partenairesScroll'); if (!container) return; const scrollAmount = 300; container.scrollBy({ left: direction === 'left' ? -scrollAmount : scrollAmount, behavior: 'smooth' }); }
        function scrollAvis(direction) { const container = document.getElementById('avisScroll'); if (!container) return; const scrollAmount = 350; container.scrollBy({ left: direction === 'left' ? -scrollAmount : scrollAmount, behavior: 'smooth' }); }
    </script>
</body>
</html>