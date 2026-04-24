<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations intra-entreprise - Tout Help</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Couleurs exactes de l'image */
        :root {
            --bg-dark-blue: #0a1d37;
            --btn-blue: #0e2a47;
            --accent-orange: #f37021;
        }

        body { background-color: #f3f4f6; font-family: sans-serif; }

        /* Le bandeau bleu du haut */
        .hero-section {
            background-color: var(--bg-dark-blue);
            height: 450px;
            padding-top: 60px;
        }

        /* La carte qui remonte sur le bleu */
        .content-card {
            margin-top: -180px;
            border-radius: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* Style spécifique pour le texte orange */
        .text-orange-custom { color: var(--accent-orange); }

        .btn-custom-blue {
            background-color: var(--btn-blue);
            transition: all 0.3s;
        }
        .btn-custom-blue:hover { opacity: 0.9; transform: translateY(-1px); }

        /* Styles Chat conservés */
        .chat-modal { display: none; position: fixed; bottom: 100px; right: 20px; width: 380px; background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 99999; overflow: hidden; flex-direction: column; }
        .chat-modal.active { display: flex; animation: fadeInUp 0.3s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .chat-header { background: linear-gradient(135deg, #e63946, #ff6b6b, #f8c291); color: white; padding: 15px 18px; display: flex; align-items: center; justify-content: space-between; }
        .robot-icon { position: fixed; bottom: 20px; right: 20px; background: linear-gradient(135deg, #e63946, #ff6b6b); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 9999; }
        .chat-body { flex: 1; overflow-y: auto; background-color: #fef9f9; max-height: 380px; }
        .chat-messages-area { padding: 14px; display: flex; flex-direction: column; gap: 8px; }
        .chat-input-area { background: white; border-top: 1px solid #ffe0e0; padding: 10px 12px; display: flex; gap: 8px; }
        .chat-textarea { flex: 1; border: 1px solid #ffe0e0; border-radius: 20px; padding: 9px 14px; font-size: 13px; resize: none; outline: none; }
    </style>
</head>
<body>

    <nav class="absolute top-0 w-full z-50 py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-white opacity-80 text-sm">Cabinet Habilité FMFP</div>
            <a href="{{ url('/') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-xs font-bold uppercase tracking-wider transition">
                <i class="fas fa-arrow-left mr-2"></i> Retour à l'accueil
            </a>
        </div>
    </nav>

    <header class="hero-section text-center text-white px-4">
        <h1 class="text-5xl md:text-6xl font-black italic tracking-tighter uppercase mb-4">
            Intra <span class="text-blue-400">Entreprise</span>
        </h1>
        <p class="text-lg md:text-xl italic font-light max-w-2xl mx-auto opacity-90">
            Des solutions pédagogiques conçues spécifiquement pour la culture et les enjeux de votre organisation.
        </p>
    </header>

    <main class="container mx-auto px-4 pb-20">
        <div class="content-card bg-white p-8 md:p-16 flex flex-col md:flex-row gap-12 items-center">
            
            <div class="w-full md:w-1/2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-blue-400 mb-4 block">Exclusivité & Confidentialité</span>
                
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 leading-tight mb-6 uppercase italic">
                    Une formation qui <br>
                    <span class="text-orange-custom">vous ressemble</span>
                </h2>

                <div class="text-gray-500 leading-relaxed space-y-4 text-sm md:text-base">
                    <p>Chaque organisation a ses propres réalités, ses contraintes et ses objectifs. Nos formations intra-entreprise sont conçues sur mesure, pour répondre précisément à vos besoins et accompagner vos équipes dans leur montée en compétence.</p>
                    <p>Nous intervenons directement au sein de votre structure, avec des contenus adaptés à votre activité et à votre contexte.</p>
                </div>

                <ul class="mt-8 space-y-3">
                    <li class="flex items-center text-sm font-medium text-slate-700">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span> Calendrier flexible selon vos besoins
                    </li>
                    <li class="flex items-center text-sm font-medium text-slate-700">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span> Cas pratiques basés sur vos documents internes
                    </li>
                    <li class="flex items-center text-sm font-medium text-slate-700">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span> Cohésion d'équipe renforcée
                    </li>
                </ul>

                <div class="mt-10 flex flex-wrap gap-4">
                    <button onclick="openChatAndPrefill()" class="btn-custom-blue text-white font-bold py-3 px-8 rounded-lg text-xs uppercase tracking-widest">
                        Demander un devis sur-mesure
                    </button>
                    <a href="{{ url('/#catalogue') }}" class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-bold py-3 px-8 rounded-lg text-xs uppercase tracking-widest transition">
                        Nos catalogues
                    </a>
                </div>
            </div>

            <div class="w-full md:w-1/2">
                <div class="rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" alt="Équipe en formation" class="w-full h-auto object-cover">
                </div>
            </div>

        </div>
    </main>

    <footer class="py-8 text-center text-gray-400 text-xs">
        &copy; {{ date('Y') }} Tout Help. Tous droits réservés.
    </footer>

    <div class="robot-icon" id="robotIcon"><i class="fas fa-robot text-white text-2xl"></i><span id="robotBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center border-2 border-white" style="display:none;">0</span></div>
    
    <div class="chat-modal" id="chatModal">
        <div class="chat-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">🤖</div>
                <div>
                    <div class="text-sm font-bold">Support Tout Help</div>
                    <div class="text-[10px] opacity-80 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span> En ligne</div>
                </div>
            </div>
            <button class="text-xl" onclick="closeChatModal()">✕</button>
        </div>
        <div class="chat-body" id="chatBody"><div class="chat-messages-area" id="chatMessagesArea"></div></div>
        
        <div id="chatInputArea" class="chat-input-area" style="display:none;">
            <textarea id="chatTextarea" class="chat-textarea" rows="1" placeholder="Message..."></textarea>
            <button id="chatSendBtn" class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white"><i class="fas fa-paper-plane"></i></button>
        </div>

        <div id="chatInitForm" class="p-6">
            <input type="text" id="initNom" placeholder="Nom complet *" class="w-full border p-2 rounded-lg mb-2 text-sm outline-none focus:border-red-400">
            <input type="email" id="initEmail" placeholder="Email *" class="w-full border p-2 rounded-lg mb-2 text-sm outline-none focus:border-red-400">
            <textarea id="initMessage" rows="2" placeholder="Votre message *" class="w-full border p-2 rounded-lg mb-3 text-sm outline-none focus:border-red-400 resize-none"></textarea>
            <button class="w-full bg-red-500 text-white py-2 rounded-lg font-bold text-sm" onclick="submitInitForm()">Démarrer</button>
        </div>
        <div id="changeIdentityBar" style="display:none;" class="text-center pb-2">
            <button class="text-[10px] text-red-500" onclick="resetChat()">Nouvelle conversation</button>
        </div>
    </div>

    <script>
        let currentEmail = '', currentNom = '', unreadCount = 0;
        function updateBadge() { const b = document.getElementById('robotBadge'); if (unreadCount > 0) { b.textContent = unreadCount; b.style.display = 'flex'; } else { b.style.display = 'none'; } }
        function openChatModal() { document.getElementById('chatModal').classList.add('active'); unreadCount = 0; updateBadge(); if (currentEmail) loadMessages(); scrollChatToBottom(); }
        function closeChatModal() { document.getElementById('chatModal').classList.remove('active'); }
        document.getElementById('robotIcon').addEventListener('click', openChatModal);
        
        function scrollChatToBottom() { setTimeout(() => { const b = document.getElementById('chatBody'); if (b) b.scrollTop = b.scrollHeight; }, 80); }
        function escapeHtml(str) { const div = document.createElement('div'); div.textContent = str; return div.innerHTML; }
        
        async function loadMessages() { 
            if (!currentEmail) return; 
            try { 
                const res = await fetch(`/api/messages?email=${encodeURIComponent(currentEmail)}`); 
                const msgs = await res.json(); 
                renderMessages(msgs); 
            } catch(e) {} 
        }

        function renderMessages(messages) {
            const area = document.getElementById('chatMessagesArea');
            if (!messages || messages.length === 0) { area.innerHTML = '<div class="text-center text-[11px] text-red-400">⏳ En attente...</div>'; return; }
            area.innerHTML = messages.map(m => `
                <div class="flex justify-end mb-2"><div class="bg-red-500 text-white p-2 rounded-lg rounded-tr-none text-xs max-w-[80%]">${escapeHtml(m.message)}</div></div>
                ${m.reponse_admin ? `<div class="flex justify-start mb-2"><div class="bg-white border p-2 rounded-lg rounded-tl-none text-xs max-w-[80%]">${escapeHtml(m.reponse_admin)}</div></div>` : ''}
            `).join('');
            scrollChatToBottom();
        }

        async function submitInitForm() {
            const nom = document.getElementById('initNom').value.trim();
            const email = document.getElementById('initEmail').value.trim();
            const msg = document.getElementById('initMessage').value.trim();
            if(!nom || !email || !msg) return alert('Champs requis');

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch('/contact/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ nom, email, message: msg, telephone: document.getElementById('initTel')?.value || '' })
            });
            const result = await res.json();
            if(result.success) {
                currentEmail = email; currentNom = nom;
                document.getElementById('chatInitForm').style.display = 'none';
                document.getElementById('chatInputArea').style.display = 'flex';
                document.getElementById('changeIdentityBar').style.display = 'block';
                loadMessages();
            }
        }

        function openChatAndPrefill() {
            openChatModal();
            setTimeout(() => {
                document.getElementById('initMessage').value = "Bonjour, je souhaite discuter d'une formation intra-entreprise sur mesure. Pouvez-vous me contacter ?";
            }, 300);
        }
    </script>
</body>
</html>