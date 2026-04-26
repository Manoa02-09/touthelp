<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations intra-entreprise - Tout Help</title>
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
            gap: 15px;
        }
        .btn-home, .btn-catalogue {
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
        .btn-home:hover, .btn-catalogue:hover { background: #be123c; transform: translateY(-2px); }
        .btn-catalogue { background: #0f172a; }
        .btn-catalogue:hover { background: #1e293b; }

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
            line-height: 1;
            margin-bottom: 30px;
        }
        .content-card h2 .teal { color: #0d9488; }

        .content-card p {
            color: #475569;
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 40px;
        }

        .features { display: flex; flex-direction: column; gap: 20px; margin-bottom: 50px; }
        .feature-item { display: flex; align-items: center; gap: 20px; }
        .feature-icon {
            width: 48px; height: 48px;
            background: #f0fdf4;
            color: #10b981;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .feature-label { font-weight: 700; font-size: 19px; color: #1e293b; }

        .btn-row { display: flex; gap: 20px; flex-wrap: wrap; }
        .btn-orange {
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
            cursor: pointer;
            border: none;
        }
        .btn-orange:hover { background: #ea580c; transform: translateY(-3px); }

        .btn-outline {
            background: white;
            color: #0a2e5a;
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 0.1em;
            padding: 18px 40px;
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }

        .content-right img { width: 100%; border-radius: 40px; box-shadow: 0 25px 60px rgba(0,0,0,0.1); }

        /* ===== SECTOR CARDS ===== */
        .section-title-wrap { text-align: center; margin: 100px 0 60px; }
        .section-title { font-family: 'Barlow Condensed', sans-serif; font-weight: 900; font-style: italic; font-size: clamp(30px, 4vw, 48px); text-transform: uppercase; color: #0a2e5a; }
        .section-divider { width: 80px; height: 6px; background: #e11d48; margin: 25px auto 0; border-radius: 10px; }

        .cards-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; }
        .card-secteur { border-radius: 35px; padding: 40px 35px; display: flex; flex-direction: column; transition: all 0.4s ease; text-align: center; align-items: center; }
        .card-secteur:hover { transform: translateY(-10px); box-shadow: 0 20px 50px rgba(0,0,0,0.1); }
        .icon-box { width: 55px; height: 55px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 25px; font-size: 22px; color: white; }
        .card-title { font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: 20px; text-transform: uppercase; margin-bottom: 15px; }
        .card-desc { font-size: 13px; line-height: 1.5; font-weight: 500; }

        .c-textile { background: #e0f2fe; } .c-textile .card-title { color: #0369a1; } .c-textile .card-desc { color: #075985; } .c-textile .icon-box { background: #0369a1; }
        .c-sante { background: #dcfce7; } .c-sante .card-title { color: #047857; } .c-sante .card-desc { color: #065f46; } .c-sante .icon-box { background: #059669; }
        .c-agro { background: #fef3c7; } .c-agro .card-title { color: #b45309; } .c-agro .card-desc { color: #92400e; } .c-agro .icon-box { background: #d97706; }
        .c-btp { background: #dbeafe; } .c-btp .card-title { color: #1d4ed8; } .c-btp .card-desc { color: #1e40af; } .c-btp .icon-box { background: #2563eb; }
        .c-finance { background: #f3e8ff; } .c-finance .card-title { color: #7e22ce; } .c-finance .card-desc { color: #5b21b6; } .c-finance .icon-box { background: #9333ea; }
        .c-it { background: #f1f5f9; } .c-it .card-title { color: #334155; } .c-it .card-desc { color: #1e293b; } .c-it .icon-box { background: #475569; }
        .c-hotel { background: #fce7f3; } .c-hotel .card-title { color: #be185d; } .c-hotel .card-desc { color: #9d174d; } .c-hotel .icon-box { background: #db2777; }
        .c-soft { background: #ffedd5; } .c-soft .card-title { color: #c2410c; } .c-soft .card-desc { color: #9a3412; } .c-soft .icon-box { background: #ea580c; }

        /* ===== ROBOT & CHAT MODAL ===== */
        .chat-modal { display: none; position: fixed; bottom: 100px; right: 20px; width: 380px; background: white; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 99999; overflow: hidden; flex-direction: column; }
        .chat-modal.active { display: flex; animation: fadeInUp 0.4s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .robot-icon { background: #0a2e5a; width: 65px; height: 65px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 10px 25px rgba(10,46,90,0.3); position: fixed; bottom: 20px; right: 20px; z-index: 9999; transition: transform 0.3s; }
        .robot-icon:hover { transform: scale(1.1); }

        @media (max-width: 1200px) { .cards-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 1024px) { .content-card { flex-direction: column; padding: 50px; } }
        @media (max-width: 768px) { .cards-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="btn-home"><i class="fas fa-home"></i> RETOUR À L'ACCUEIL</a>
        <a href="/#catalogue" class="btn-catalogue"><i class="fas fa-book-open"></i> NOS CATALOGUES</a>
    </nav>

    <header class="hero"><h1>INTRA <span>ENTREPRISE</span></h1></header>

    <main>
        <div class="content-card">
            <div class="content-left">
                <span class="badge-label">EXCLUSIVITÉ & CONFIDENTIALITÉ</span>
                <h2>UNE FORMATION QUI<br><span class="teal">VOUS RESSEMBLE</span></h2>
                <p>Chaque organisation a ses propres réalités. Nos formations <strong>intra-entreprise</strong> sont conçues <strong>sur mesure</strong>, pour répondre précisément à vos besoins et accompagner vos équipes dans leur <strong>montée en compétence</strong>.</p>
                <div class="features">
                    <div class="feature-item"><div class="feature-icon"><i class="fas fa-calendar-alt"></i></div><span class="feature-label">Calendrier flexible</span></div>
                    <div class="feature-item"><div class="feature-icon"><i class="fas fa-file-alt"></i></div><span class="feature-label">Cas pratiques sur vos documents</span></div>
                    <div class="feature-item"><div class="feature-icon"><i class="fas fa-users"></i></div><span class="feature-label">Cohésion d'équipe renforcée</span></div>
                </div>
                <div class="btn-row">
                    <button class="btn-orange" onclick="openChatAndPrefill()">DEMANDER UN DEVIS</button>
                    <a href="/#catalogue" class="btn-outline"><i class="fas fa-arrow-right"></i> NOS CATALOGUES</a>
                </div>
            </div>
            <div class="content-right">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800" alt="Equipe">
            </div>
        </div>

        <div class="section-title-wrap">
            <h3 class="section-title">Découvrez l'ensemble de nos formations par secteur d'activité</h3>
            <div class="section-divider"></div>
        </div>

        <div class="cards-grid">
            <div class="card-secteur c-btp">
                <div class="icon-box"><i class="fas fa-hard-hat"></i></div>
                <div class="card-title">BTP, Industrie & Transport</div>
                <div class="card-desc">Conduite et levage, Supply chain, EIE, HSE, Management environnemental, Espace confiné, Zone ATEX, Maintenance équipement (EPI), analyse des risques, Gestion des produits dangereux, Lean, 5S, etc...</div>
            </div>

            <div class="card-secteur c-agro">
                <div class="icon-box"><i class="fas fa-seedling"></i></div>
                <div class="card-title">Agroalimentaire</div>
                <div class="card-desc">HACCP, ISO 22000, chaîne du froid, transformation, BPA, certification bio, SMETA, RSE, traçabilité, ISO 14001, etc...</div>
            </div>

            <div class="card-secteur c-sante">
                <div class="icon-box"><i class="fas fa-user-md"></i></div>
                <div class="card-title">Santé</div>
                <div class="card-desc">Soins infirmiers, urgences & secours, hygiène hospitalière, sécurité patients, gestion pharmacie, DASRI, ISO 9001, ISO 45001, etc...</div>
            </div>

            <div class="card-secteur c-textile">
                <div class="icon-box"><i class="fas fa-tshirt"></i></div>
                <div class="card-title">Textile</div>
                <div class="card-desc">Production industrielle, couture industrielle, maintenance machines textile, contrôle qualité textile, normes export, gestion de stock, 5S, etc...</div>
            </div>

            <div class="card-secteur c-finance">
                <div class="icon-box"><i class="fas fa-chart-line"></i></div>
                <div class="card-title">Banque & Finance / Commerce</div>
                <div class="card-desc">Analyse financière, comptabilité, gestion des risques, audit, lutte anti-fraude, vente, marketing digital, ISO 37000, etc...</div>
            </div>

            <div class="card-secteur c-soft">
                <div class="icon-box"><i class="fas fa-lightbulb"></i></div>
                <div class="card-title">Soft Skills</div>
                <div class="card-desc">Anglais des affaires, leadership, gestion du stress/temps/conflits, communication, résolution de problème, Excel avancé, etc...</div>
            </div>

            <div class="card-secteur c-it">
                <div class="icon-box"><i class="fas fa-network-wired"></i></div>
                <div class="card-title">Télécom & IT</div>
                <div class="card-desc">Réseaux & télécom, CRM, gestion de projet, leadership, intelligence émotionnelle, analyse des risques, HSE, etc...</div>
            </div>

            <div class="card-secteur c-hotel">
                <div class="icon-box"><i class="fas fa-utensils"></i></div>
                <div class="card-title">Hôtellerie & Restauration</div>
                <div class="card-desc">Hygiène alimentaire (BPH), cuisine collective, service client, bar & service, gestion hôtelière, incendie & extincteurs, etc...</div>
            </div>
        </div>
    </main>

    <div class="robot-icon" id="robotIcon"><i class="fas fa-robot text-white text-3xl"></i></div>
    <div class="chat-modal" id="chatModal">
        <div class="chat-header p-5 bg-[#0a2e5a] text-white flex justify-between items-center">
            <div class="flex items-center gap-3"><div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-xl">🤖</div><span class="font-bold">Support Tout Help</span></div>
            <button onclick="closeChatModal()" class="text-2xl">&times;</button>
        </div>
        <div class="chat-body p-4" id="chatBody" style="height: 300px; overflow-y: auto;"><div id="chatMessagesArea"></div></div>
        <div id="chatInitForm" class="p-6 border-t">
            <input type="text" id="initNom" placeholder="Nom complet *" class="w-full border-2 p-3 rounded-xl mb-3 outline-none focus:border-blue-600 font-medium">
            <input type="email" id="initEmail" placeholder="Email *" class="w-full border-2 p-3 rounded-xl mb-3 outline-none focus:border-blue-600 font-medium">
            <textarea id="initMessage" rows="2" placeholder="Message *" class="w-full border-2 p-3 rounded-xl mb-4 outline-none focus:border-blue-600 font-medium resize-none"></textarea>
            <button class="w-full bg-[#0a2e5a] text-white py-4 rounded-xl font-black uppercase text-xs tracking-widest" onclick="submitInitForm()">Démarrer</button>
        </div>
    </div>

    <footer style="text-align: center; padding: 50px; color: #94a3b8; font-family: 'Barlow Condensed'; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; font-size: 12px;">
        &copy; 2026 Tout Help. Tous droits réservés.
    </footer>

    <script>
        function openChatModal() { document.getElementById('chatModal').classList.add('active'); }
        function closeChatModal() { document.getElementById('chatModal').classList.remove('active'); }
        document.getElementById('robotIcon').addEventListener('click', openChatModal);
        
        function openChatAndPrefill() {
            openChatModal();
            setTimeout(() => {
                document.getElementById('initMessage').value = "Bonjour, je souhaite discuter d'une formation intra-entreprise sur mesure. Pouvez-vous me contacter ?";
            }, 300);
        }

        async function submitInitForm() {
            const nom = document.getElementById('initNom').value.trim();
            const email = document.getElementById('initEmail').value.trim();
            const msg = document.getElementById('initMessage').value.trim();
            if(!nom || !email || !msg) return alert('Champs requis');

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                const res = await fetch('/contact/send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ nom, email, message: msg, telephone: '' })
                });
                const result = await res.json();
                if(result.success) {
                    document.getElementById('chatInitForm').style.display = 'none';
                    document.getElementById('chatMessagesArea').innerHTML = `<div class="bg-blue-50 p-3 rounded-xl text-sm mb-2 font-medium"><b>Moi:</b> ${msg}</div><div class="bg-slate-100 p-3 rounded-xl text-sm mb-2 font-medium">Merci ${nom}, votre demande a été envoyée.</div>`;
                }
            } catch(e) { alert('Erreur réseau'); }
        }
    </script>
</body>
</html>