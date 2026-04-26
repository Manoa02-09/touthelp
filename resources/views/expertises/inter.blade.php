<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations inter-entreprises - Tout Help</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,400;0,600;0,700;0,800;0,900;1,700;1,800;1,900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            background-color: #f8fafc;
            font-family: 'Barlow', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* ===== NAV ===== */
        nav {
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 100;
            padding: 30px 40px;
            display: flex;
            justify-content: flex-end;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #e11d48;
            color: white;
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            font-size: 14px;
            padding: 12px 24px;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-home:hover { background: #be123c; transform: translateY(-2px); }

        /* ===== HERO ===== */
        .hero {
            background: #0a2e5a;
            height: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 100px;
        }
        .hero h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 900;
            font-style: italic;
            font-size: clamp(60px, 10vw, 110px);
            color: white;
            text-transform: uppercase;
            letter-spacing: -0.03em;
        }
        .hero h1 span { color: #93c5fd; }

        /* ===== MAIN CONTENT ===== */
        main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px 100px;
        }

        /* ===== WHITE CARD ===== */
        .content-card {
            background: white;
            border-radius: 50px;
            margin-top: -180px;
            position: relative;
            z-index: 10;
            padding: 80px;
            display: flex;
            gap: 80px;
            align-items: center;
            box-shadow: 0 30px 100px rgba(0,0,0,0.06);
        }
        .content-left { flex: 1.2; }
        .content-right { flex: 0.8; }

        /* Badge Bleu comme dans l'Intra */
        .badge-label {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.2em;
            color: #2563eb;
            margin-bottom: 20px;
            display: block;
        }

        .content-card h2 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 900;
            font-style: italic;
            font-size: clamp(40px, 5vw, 64px);
            text-transform: uppercase;
            color: #0f172a;
            line-height: 1.1;
            margin-bottom: 30px;
        }
        /* Accent TEAL comme dans l'Intra */
        .content-card h2 .teal { color: #0d9488; }

        .content-card p {
            color: #475569;
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 40px;
        }

        /* Liste des propositions */
        .propositions-list { 
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            gap: 15px; 
            margin: 30px 0;
        }
        .prop-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: #1e293b;
            font-size: 15px;
        }
        /* Dot assorti au teal */
        .prop-dot {
            width: 8px;
            height: 8px;
            background: #0d9488;
            border-radius: 50%;
        }

        /* Bouton ORANGE comme dans l'Intra */
        .btn-orange-action {
            background: #f97316;
            color: white;
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 0.1em;
            padding: 20px 40px;
            border-radius: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(249,115,22,0.3);
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-orange-action:hover { background: #ea580c; transform: translateY(-3px); }

        .content-right img {
            width: 100%;
            border-radius: 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.1);
        }

        /* ===== BLOC "POUR QUI" ===== */
        .info-box {
            text-align: center;
            max-width: 900px;
            margin: 80px auto 0;
            padding: 60px 50px;
            background: linear-gradient(135deg, #0a2e5a 0%, #1e40af 100%);
            border-radius: 40px;
            color: white;
            box-shadow: 0 20px 50px rgba(10,46,90,0.2);
            position: relative;
            overflow: hidden;
        }
        .info-box h3 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 900;
            font-style: italic;
            font-size: 36px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        .info-box p {
            font-size: 19px;
            line-height: 1.6;
            font-weight: 500;
            color: #dbeafe;
            max-width: 700px;
            margin: 0 auto;
        }

        /* ===== CHAT MODAL DESIGN ===== */
        .chat-modal { display: none; position: fixed; bottom: 100px; right: 20px; width: 380px; background: white; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 99999; overflow: hidden; flex-direction: column; }
        .chat-modal.active { display: flex; animation: fadeInUp 0.4s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        .chat-header { background: #e11d48; color: white; padding: 20px; display: flex; align-items: center; justify-content: space-between; }
        .robot-icon { position: fixed; bottom: 20px; right: 20px; background: #e11d48; width: 65px; height: 65px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 9999; box-shadow: 0 10px 25px rgba(225,29,72,0.3); transition: transform 0.3s; }
        .robot-icon:hover { transform: scale(1.1); }
        
        .chat-body { flex: 1; overflow-y: auto; background-color: #fff; max-height: 350px; }
        .chat-messages-area { padding: 20px; display: flex; flex-direction: column; gap: 10px; }
        .chat-input-area { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px; display: flex; gap: 10px; }
        .chat-textarea { flex: 1; border: 1px solid #cbd5e1; border-radius: 15px; padding: 10px 15px; font-size: 14px; resize: none; outline: none; }

        @media (max-width: 1024px) {
            .content-card { flex-direction: column; padding: 50px; }
            .propositions-list { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="{{ url('/') }}" class="btn-home">
            <i class="fas fa-arrow-left"></i> RETOUR À L'ACCUEIL
        </a>
    </nav>

    <header class="hero">
        <h1>INTER <span>ENTREPRISES</span></h1>
    </header>

    <main>
        <div class="content-card">
            <div class="content-left">
                <span class="badge-label">PARTAGE & PERFORMANCE</span>
                <h2>
                    UNE DYNAMIQUE <br>
                    <span class="teal">COLLECTIVE</span>
                </h2>

                <div class="space-y-4">
                    <p>Nos formations interentreprises permettent à vos collaborateurs de monter en compétence dans un cadre structuré et dynamique.</p>
                    <p>Elles offrent un environnement propice au partage d'expériences et à l'acquisition de bonnes pratiques immédiatement applicables dans votre secteur.</p>
                </div>

                <h3 class="font-black text-xs uppercase tracking-widest mt-8 mb-4 text-slate-800">🧩 Ce que nous proposons</h3>
                <div class="propositions-list">
                    <div class="prop-item"><span class="prop-dot"></span> Sessions publiques</div>
                    <div class="prop-item"><span class="prop-dot"></span> Séminaires thématiques</div>
                    <div class="prop-item"><span class="prop-dot"></span> Journées de sensibilisation</div>
                    <div class="prop-item"><span class="prop-dot"></span> Portes ouvertes</div>
                </div>

                <div class="mt-10">
                    <a href="{{ route('calendrier') }}" class="btn-orange-action">
                        <i class="fas fa-calendar-alt"></i>
                        VOIR LE CALENDRIER DES FORMATIONS
                    </a>
                </div>
            </div>

            <div class="content-right">
                <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80" alt="Session de formation">
            </div>
        </div>

        <div class="info-box">
            <h3><i class="fas fa-bullseye text-blue-300"></i> Pour qui ?</h3>
            <p>Ces formations s'adressent aux professionnels souhaitant renforcer leurs compétences, s'initier à de nouvelles pratiques ou échanger avec d'autres acteurs du secteur.</p>
        </div>
    </main>

    <footer style="text-align: center; padding: 50px; color: #94a3b8; font-family: 'Barlow Condensed'; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; font-size: 12px;">
        &copy; {{ date('Y') }} Tout Help. Tous droits réservés.
    </footer>

    <div class="robot-icon" id="robotIcon">
        <i class="fas fa-robot text-white text-3xl"></i>
        <span id="robotBadge" class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center border-2 border-white" style="display:none;">0</span>
    </div>

    <div class="chat-modal" id="chatModal">
        <div class="chat-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-xl">🤖</div>
                <div>
                    <div class="text-sm font-bold">Support Tout Help</div>
                    <div class="text-[10px] opacity-80 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span> En ligne</div>
                </div>
            </div>
            <button onclick="closeChatModal()" class="text-2xl">&times;</button>
        </div>
        <div class="chat-body" id="chatBody"><div class="chat-messages-area" id="chatMessagesArea"></div></div>
        
        <div id="chatInputArea" class="chat-input-area" style="display:none;">
            <textarea id="chatTextarea" class="chat-textarea" rows="1" placeholder="Votre message..."></textarea>
            <button id="chatSendBtn" class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white transition hover:scale-105"><i class="fas fa-paper-plane"></i></button>
        </div>

        <div id="chatInitForm" class="p-6">
            <div class="text-center mb-4"><p class="text-xs text-red-500 font-bold">Bonjour ! 👋 Présentez-vous pour discuter :</p></div>
            <input type="text" id="initNom" placeholder="Nom complet *" class="w-full border-2 p-3 rounded-xl mb-2 text-sm outline-none focus:border-red-400">
            <input type="email" id="initEmail" placeholder="Email *" class="w-full border-2 p-3 rounded-xl mb-2 text-sm outline-none focus:border-red-400">
            <textarea id="initMessage" rows="2" placeholder="Votre message *" class="w-full border-2 p-3 rounded-xl mb-4 text-sm outline-none focus:border-red-400 resize-none"></textarea>
            <button class="w-full bg-red-600 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-widest" id="initSendBtn" onclick="submitInitForm()">Démarrer</button>
        </div>

        <div id="changeIdentityBar" style="display:none;" class="text-center pb-2 bg-white border-t">
            <button class="text-[10px] text-red-500 py-2 font-bold" onclick="resetChat()"><i class="fas fa-user-edit mr-1"></i> Nouvelle conversation</button>
        </div>
    </div>

    <script>
        let currentEmail = '', currentNom = '', unreadCount = 0;
        function updateBadge() { const b = document.getElementById('robotBadge'); if (unreadCount > 0) { b.textContent = unreadCount; b.style.display = 'flex'; } else { b.style.display = 'none'; } }
        function openChatModal() { document.getElementById('chatModal').classList.add('active'); unreadCount = 0; updateBadge(); if (currentEmail) loadMessages(); scrollChatToBottom(); }
        function closeChatModal() { document.getElementById('chatModal').classList.remove('active'); }
        document.getElementById('robotIcon').addEventListener('click', openChatModal);
        
        function scrollChatToBottom() { setTimeout(() => { const b = document.getElementById('chatBody'); if (b) b.scrollTop = b.scrollHeight; }, 80); }
        function escapeHtml(str) { if(!str) return ''; const div = document.createElement('div'); div.textContent = str; return div.innerHTML; }

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
            if (!messages || messages.length === 0) { area.innerHTML = '<div class="text-center text-[11px] text-red-400 font-bold">⏳ En attente de réponse...</div>'; return; }
            area.innerHTML = messages.map(m => `
                <div class="flex justify-end mb-2"><div class="bg-red-600 text-white p-3 rounded-2xl rounded-tr-none text-xs max-w-[85%] shadow-sm font-medium">${escapeHtml(m.message)}</div></div>
                ${m.reponse_admin ? `<div class="flex justify-start mb-2"><div class="bg-slate-100 border p-3 rounded-2xl rounded-tl-none text-xs max-w-[85%] shadow-sm font-medium text-slate-700">${escapeHtml(m.reponse_admin)}</div></div>` : ''}
            `).join('');
            scrollChatToBottom();
        }

        async function submitInitForm() {
            const nom = document.getElementById('initNom').value.trim();
            const email = document.getElementById('initEmail').value.trim();
            const msg = document.getElementById('initMessage').value.trim();
            if(!nom || !email || !msg) return alert('Merci de remplir les champs obligatoires.');

            const btn = document.getElementById('initSendBtn');
            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                const res = await fetch('/contact/send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ nom, email, message: msg, telephone: '' })
                });
                const result = await res.json();
                if(result.success) {
                    currentEmail = email; currentNom = nom;
                    document.getElementById('chatInitForm').style.display = 'none';
                    document.getElementById('chatInputArea').style.display = 'flex';
                    document.getElementById('changeIdentityBar').style.display = 'block';
                    loadMessages();
                }
            } catch(e) { alert('Erreur lors de l\'envoi.'); }
            btn.disabled = false; btn.innerHTML = 'Démarrer';
        }

        function resetChat() {
            currentEmail = ''; currentNom = '';
            document.getElementById('chatInitForm').style.display = 'block';
            document.getElementById('chatInputArea').style.display = 'none';
            document.getElementById('changeIdentityBar').style.display = 'none';
            document.getElementById('chatMessagesArea').innerHTML = '';
        }
    </script>
</body>
</html>