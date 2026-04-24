<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accompagnement & Audit - Tout Help</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --bg-dark-blue: #0a1d37;
            --btn-blue: #0e2a47;
        }

        body { background-color: #f3f4f6; font-family: system-ui, -apple-system, sans-serif; }

        .hero-section {
            background-color: var(--bg-dark-blue);
            height: 400px;
            padding-top: 60px;
        }

        .content-card {
            margin-top: -150px;
            border-radius: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .service-column {
            transition: all 0.3s ease;
        }

        .btn-custom-dark {
            background-color: var(--btn-blue);
            transition: all 0.3s ease;
        }
        .btn-custom-dark:hover { opacity: 0.9; transform: translateY(-1px); }

        /* Styles CHAT */
        .chat-modal { display: none; position: fixed; bottom: 100px; right: 20px; width: 380px; background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 99999; overflow: hidden; flex-direction: column; }
        .chat-modal.active { display: flex; animation: fadeInUp 0.3s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .chat-header { background: linear-gradient(135deg, #e63946, #ff6b6b, #f8c291); color: white; padding: 15px 18px; display: flex; align-items: center; justify-content: space-between; }
        .robot-icon { position: fixed; bottom: 20px; right: 20px; background: linear-gradient(135deg, #e63946, #ff6b6b); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 9999; box-shadow: 0 5px 20px rgba(230,57,70,0.4); }
    </style>
</head>
<body>

    <nav class="absolute top-0 w-full z-50 py-4 px-6">
        <div class="container mx-auto flex justify-between items-center text-white">
            <div class="opacity-80 text-sm italic uppercase tracking-widest">Tout Help Consulting</div>
            <a href="{{ url('/') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-xs font-bold uppercase transition">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </nav>

    <header class="hero-section text-center text-white px-4">
        <h1 class="text-4xl md:text-6xl font-black italic tracking-tighter uppercase mb-4">
            Audit <span class="text-blue-400">&</span> Accompagnement
        </h1>
        <p class="text-sm md:text-base font-light max-w-xl mx-auto opacity-70 italic">
            Une approche binaire pour une performance globale : structurer vos systèmes et valider votre conformité.
        </p>
    </header>

    <main class="container mx-auto px-4 pb-20">
        <div class="content-card bg-white overflow-hidden flex flex-col md:flex-row">
            
            <div class="w-full md:w-1/2 p-8 md:p-14 border-b md:border-b-0 md:border-r border-gray-100 service-column hover:bg-slate-50">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h2 class="text-2xl font-black italic uppercase tracking-tighter text-slate-800">Accompagnement</h2>
                </div>
                
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Nous vous guidons dans la mise en place et la structuration de vos systèmes de management pour garantir une base solide à votre activité.
                </p>

                <h4 class="text-[10px] font-bold uppercase text-blue-600 tracking-widest mb-4">Expertises clés</h4>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                        <span>Mise en place de systèmes (ISO, HSE, RSE...)</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                        <span>Structuration organisationnelle</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                        <span>Rédaction de procédures & processus</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                        <span>Appui opérationnel continu</span>
                    </li>
                </ul>
                <div class="bg-blue-600/5 p-4 rounded-xl">
                    <p class="text-xs italic text-blue-800"><strong>Résultat :</strong> Un système clair, structuré et adapté à votre fonctionnement.</p>
                </div>
            </div>

            <div class="w-full md:w-1/2 p-8 md:p-14 service-column hover:bg-slate-50">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-search-plus"></i>
                    </div>
                    <h2 class="text-2xl font-black italic uppercase tracking-tighter text-slate-800">Audit</h2>
                </div>

                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Prenez du recul sur vos pratiques. Nous identifions les écarts et définissons des axes d'amélioration concrets pour vos certifications.
                </p>

                <h4 class="text-[10px] font-bold uppercase text-emerald-600 tracking-widest mb-4">Interventions</h4>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <i class="fas fa-dot-circle text-emerald-500 mt-1"></i>
                        <span>Audit interne & diagnostic</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <i class="fas fa-dot-circle text-emerald-500 mt-1"></i>
                        <span>Audit à blanc (pré-certification)</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <i class="fas fa-dot-circle text-emerald-500 mt-1"></i>
                        <span>Évaluation de conformité réglementaire</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <i class="fas fa-dot-circle text-emerald-500 mt-1"></i>
                        <span>Optimisation des processus</span>
                    </li>
                </ul>
                <div class="bg-emerald-600/5 p-4 rounded-xl">
                    <p class="text-xs italic text-emerald-800"><strong>Résultat :</strong> Une vision précise de votre situation et des actions concrètes.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <button onclick="openChatAndPrefill()" class="btn-custom-dark text-white font-bold py-5 px-12 rounded-2xl text-xs uppercase tracking-widest shadow-2xl inline-flex items-center gap-4">
                <i class="fas fa-file-signature text-lg"></i> Obtenir un devis personnalisé
            </button>
        </div>
    </main>

    <footer class="py-10 text-center text-gray-400 text-[10px] uppercase tracking-[0.2em]">
        Tout Help &copy; {{ date('Y') }} · Conseil en Organisation
    </footer>

    <div class="robot-icon" id="robotIcon"><i class="fas fa-robot text-white text-2xl"></i></div>
    <div class="chat-modal" id="chatModal">
        <div class="chat-header">
            <div class="flex items-center gap-3">🤖 <span class="text-sm font-bold">Support Tout Help</span></div>
            <button onclick="closeChatModal()">✕</button>
        </div>
        <div class="chat-body" id="chatBody"><div class="chat-messages-area" id="chatMessagesArea"></div></div>
        <div id="chatInitForm" class="p-6">
            <input type="text" id="initNom" placeholder="Nom *" class="w-full border p-2 rounded mb-2 text-sm">
            <input type="email" id="initEmail" placeholder="Email *" class="w-full border p-2 rounded mb-2 text-sm">
            <textarea id="initMessage" rows="3" class="w-full border p-2 rounded mb-3 text-sm resize-none"></textarea>
            <button class="w-full bg-red-500 text-white py-2 rounded font-bold text-sm" onclick="submitInitForm()">Démarrer</button>
        </div>
    </div>

    <script>
        function openChatModal() { document.getElementById('chatModal').classList.add('active'); }
        function closeChatModal() { document.getElementById('chatModal').classList.remove('active'); }
        document.getElementById('robotIcon').addEventListener('click', openChatModal);
        
        function openChatAndPrefill() {
            openChatModal();
            setTimeout(() => {
                document.getElementById('initMessage').value = "Bonjour, je souhaiterais un devis pour un accompagnement/audit. Merci !";
            }, 300);
        }

        async function submitInitForm() {
            const nom = document.getElementById('initNom').value.trim();
            const email = document.getElementById('initEmail').value.trim();
            const msg = document.getElementById('initMessage').value.trim();
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            
            if(!nom || !email || !msg) return alert('Champs requis.');

            try {
                const res = await fetch('/contact/send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ nom, email, message: msg })
                });
                const result = await res.json();
                if(result.success) {
                    document.getElementById('chatInitForm').innerHTML = `<div class="p-4 text-center text-sm text-green-600 italic">Demande envoyée ! Nous revenons vers vous rapidement.</div>`;
                }
            } catch(e) { alert('Erreur.'); }
        }
    </script>
</body>
</html>