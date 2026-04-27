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
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
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

        /* ===== CHAT MODAL COMPLET ===== */
        .chat-modal { display: none; position: fixed; bottom: 100px; right: 20px; width: 380px; background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 99999; overflow: hidden; flex-direction: column; }
        .chat-modal.active { display: flex; animation: fadeInUp 0.3s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .chat-header { background: linear-gradient(135deg, #e63946, #ff6b6b, #f8c291); color: white; padding: 15px 18px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
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
        .robot-icon { position: fixed; bottom: 20px; right: 20px; background: #0a2e5a; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 5px 20px rgba(10,46,90,0.4); transition: all 0.3s ease; z-index: 9999; }
        .robot-icon:hover { transform: scale(1.1); }
        .robot-icon i { font-size: 28px; color: white; }
        .robot-badge { position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 11px; font-weight: bold; min-width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0 4px; border: 2px solid white; animation: badgePulse 0.6s ease-in-out; }
        @keyframes badgePulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); background-color: #ef4444; } }
        .chat-footer { background: white; padding: 6px; text-align: center; font-size: 11px; color: #e63946; border-top: 1px solid #ffe0e0; flex-shrink: 0; }
        .hidden { display: none !important; }

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

    <!-- ROBOT FLOTTANT -->
    <div class="robot-icon" id="robotIcon">
        <i class="fas fa-robot"></i>
        <span id="robotBadge" class="robot-badge" style="display:none;">0</span>
    </div>

    <!-- CHAT MODAL -->
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
        <div class="chat-body" id="chatBody"><div class="chat-messages-area" id="chatMessagesArea"></div></div>
        <div id="chatInputArea" class="chat-input-area" style="display:none;">
            <textarea id="chatTextarea" class="chat-textarea" rows="1" placeholder="Écrivez votre message..." maxlength="1000"></textarea>
            <button id="chatSendBtn" class="chat-send-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M22 2 11 13M22 2 15 22l-4-9-9-4 20-7z"/></svg></button>
        </div>
        <div id="chatInitForm" class="chat-init-form">
            <div style="text-align:center;margin-bottom:12px;"><p style="font-size:13px;color:#e63946;">Bonjour ! 👋 Pour commencer, présentez-vous :</p></div>
            <input type="text" id="initNom" placeholder="Votre nom complet *" maxlength="150" autocomplete="name">
            <input type="email" id="initEmail" placeholder="Votre email *" maxlength="150" autocomplete="email">
            <input type="tel" id="initTel" placeholder="Votre téléphone (optionnel)" maxlength="30" autocomplete="tel">
            <textarea id="initMessage" rows="2" placeholder="Votre message *" style="resize:none;" maxlength="1000"></textarea>
            <button class="chat-init-btn" id="initSendBtn" onclick="submitInitForm()"><i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation</button>
        </div>
        <div id="changeIdentityBar" style="display:none;background:white;border-top:1px solid #ffe0e0;"><button class="change-identity-btn" onclick="resetChat()"><i class="fas fa-user-edit mr-1"></i> Nouvelle conversation</button></div>
        <div class="chat-footer">Réponse dans les plus brefs délais · Tout Help</div>
    </div>

    <footer style="text-align: center; padding: 50px; color: #94a3b8; font-family: 'Barlow Condensed'; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; font-size: 12px;">
        &copy; 2026 Tout Help. Tous droits réservés.
    </footer>

    <script>
    (function() {
        "use strict";
        
        let currentEmail = '', currentNom = '', unreadCount = 0, audioCtx = null;
        let pusherListenerSet = false, pollInterval = null, isLoading = false, isSending = false;
        let lastMessagesHash = '', wsRetryCount = 0, pusherChannel = null;
        let soundEnabled = true, audioEnabled = false, pendingSounds = [];
        
        const MAX_MESSAGE_LENGTH = 1000, MAX_NAME_LENGTH = 150, MAX_EMAIL_LENGTH = 150, MAX_PHONE_LENGTH = 30;
        const rateLimits = new Map();
        let requestTimestamps = [];
        const RATE_LIMIT_DELAY = 5000, MAX_REQUESTS_PER_MINUTE = 12;
        
        function escapeHtml(str) { if (!str) return ''; const d = document.createElement('div'); d.textContent = String(str); return d.innerHTML; }
        function sanitize(str, max) { if (!str) return ''; return String(str).replace(/<[^>]*>/g, '').substring(0, max || 1000).trim(); }
        function formatTime(d) { if (!d) return ''; try { return new Date(d).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'}); } catch(e) { return ''; } }
        
        function isValidEmail(email) { if (!email) return false; const trimmed = email.trim(); if (trimmed.length > MAX_EMAIL_LENGTH) return false; const emailRegex = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]{0,63}@[a-zA-Z0-9][a-zA-Z0-9.-]{0,252}\.[a-zA-Z]{2,}$/; return emailRegex.test(trimmed); }
        function isValidName(name) { if (!name) return false; const trimmed = name.trim(); if (trimmed.length > MAX_NAME_LENGTH) return false; if (trimmed.length < 2) return false; const nameRegex = /^[a-zA-ZÀ-ÿ\s'\-]{2,150}$/; return nameRegex.test(trimmed); }
        function isValidMessage(msg) { if (!msg) return false; const trimmed = msg.trim(); if (trimmed.length < 2) return false; if (trimmed.length > MAX_MESSAGE_LENGTH) return false; return true; }
        
        function isRateLimited(email) {
            const now = Date.now();
            while (requestTimestamps.length > 0 && requestTimestamps[0] < now - 60000) requestTimestamps.shift();
            if (requestTimestamps.length >= MAX_REQUESTS_PER_MINUTE) { alert('Trop de tentatives. Patientez une minute.'); return true; }
            const last = rateLimits.get(email) || 0;
            if (now - last < RATE_LIMIT_DELAY) { alert('Patientez quelques secondes.'); return true; }
            rateLimits.set(email, now);
            requestTimestamps.push(now);
            return false;
        }
        
        function initAudio() { if (!audioCtx) { try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch(e) {} } }
        function enableAudio() {
            if (audioEnabled) return;
            initAudio();
            if (audioCtx && audioCtx.state === 'suspended') {
                audioCtx.resume().then(() => { audioEnabled = true; pendingSounds.forEach(() => playNotifSound()); pendingSounds = []; }).catch(() => {});
            } else if (audioCtx && audioCtx.state === 'running') { audioEnabled = true; }
        }
        function playNotifSound() {
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
        
        document.addEventListener('click', enableAudio, { once: true });
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
            modal.classList.add('active');
            unreadCount = 0;
            updateBadge();
            if (currentEmail) { loadMessages(true); startPolling(); }
            scrollChatToBottom();
        }
        function closeChatModal() { document.getElementById('chatModal').classList.remove('active'); stopPolling(); }
        document.getElementById('robotIcon').addEventListener('click', openChatModal);
        
        function openChatAndPrefill() {
            openChatModal();
            setTimeout(() => {
                const msgField = document.getElementById('initMessage');
                if (msgField) msgField.value = "Bonjour, je souhaite discuter d'une formation intra-entreprise sur mesure. Pouvez-vous me contacter ?";
            }, 300);
        }
        
        function scrollChatToBottom() { setTimeout(() => { const b = document.getElementById('chatBody'); if (b) b.scrollTop = b.scrollHeight; }, 100); }
        function startPolling() { stopPolling(); if (!currentEmail) return; pollInterval = setInterval(() => { if (currentEmail && document.getElementById('chatModal').classList.contains('active')) loadMessages(false); }, 6000); }
        function stopPolling() { if (pollInterval) { clearInterval(pollInterval); pollInterval = null; } }
        
        function generateMessagesHash(messages) { if (!messages || messages.length === 0) return ''; const last = messages[messages.length - 1]; return `${last?.id || ''}-${last?.updated_at || ''}-${messages.length}`; }
        
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
        
        let lastRefreshTime = 0;
        
        async function loadMessages(force = false) {
            if (!currentEmail || isLoading) return;
            const now = Date.now();
            if (now - lastRefreshTime < 500 && !force) return;
            lastRefreshTime = now;
            isLoading = true;
            try {
                const url = `/api/messages?email=${encodeURIComponent(currentEmail)}&_=${now}`;
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, cache: 'no-store' });
                if (!res.ok) throw new Error();
                const msgs = await res.json();
                const messages = Array.isArray(msgs) ? msgs : [];
                const hash = generateMessagesHash(messages);
                const isNew = hash !== lastMessagesHash;
                if (isNew || force) {
                    lastMessagesHash = hash;
                    const isChatOpen = document.getElementById('chatModal').classList.contains('active');
                    if (!isChatOpen && isNew && !force) { unreadCount++; updateBadge(); showRobotNotification(); playNotifSound(); }
                    renderMessages(messages);
                    if (isChatOpen) scrollChatToBottom();
                }
            } catch(e) { console.warn('loadMessages error:', e); }
            finally { isLoading = false; }
        }
        
        async function sendMessageAPI(nom, email, telephone, message) {
            if (!isValidName(nom)) return { success: false, message: 'Nom invalide.' };
            if (!isValidEmail(email)) return { success: false, message: 'Email invalide.' };
            if (!isValidMessage(message)) return { success: false, message: 'Message invalide.' };
            if (isRateLimited(email)) return { success: false, message: '' };
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrf) return { success: false, message: 'Erreur de sécurité.' };
            const cleanNom = sanitize(nom, MAX_NAME_LENGTH);
            const cleanEmail = email.trim().substring(0, MAX_EMAIL_LENGTH);
            const cleanMsg = sanitize(message, MAX_MESSAGE_LENGTH);
            try {
                const res = await fetch('/contact/send', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: JSON.stringify({ nom: cleanNom, email: cleanEmail, message: cleanMsg, telephone: telephone || '' })
                });
                if (!res.ok) throw new Error();
                return await res.json();
            } catch(e) { return { success: false, message: 'Erreur réseau.' }; }
        }
        
        async function submitInitForm() {
            if (isSending) return;
            const nom = document.getElementById('initNom').value.trim();
            const email = document.getElementById('initEmail').value.trim();
            const tel = document.getElementById('initTel').value.trim();
            const msg = document.getElementById('initMessage').value.trim();
            if (!nom || !email || !msg) { alert('Merci de remplir tous les champs obligatoires.'); return; }
            const btn = document.getElementById('initSendBtn');
            isSending = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Envoi...';
            btn.disabled = true;
            const result = await sendMessageAPI(nom, email, tel, msg);
            if (result.success) {
                currentEmail = email;
                currentNom = nom;
                document.getElementById('chatInitForm').style.display = 'none';
                document.getElementById('chatInputArea').style.display = 'flex';
                document.getElementById('changeIdentityBar').style.display = 'block';
                await new Promise(r => setTimeout(r, 500));
                lastMessagesHash = '';
                await loadMessages(true);
                startPolling();
                setupPusherListener();
                playNotifSound();
            } else if (result.message) { alert(result.message); }
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation';
            btn.disabled = false;
            isSending = false;
        }
        
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
            if (result.success) { lastMessagesHash = ''; await loadMessages(true); }
            else if (result.message) { alert(result.message); ta.value = msg; }
            btn.disabled = false;
            isSending = false;
        }
        
        function resetChat() {
            currentEmail = ''; currentNom = ''; lastMessagesHash = ''; unreadCount = 0;
            updateBadge(); stopPolling(); pusherListenerSet = false;
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
        
        function setupPusherListener() {
            if (pusherListenerSet) return;
            function initPusher() {
                if (typeof Pusher === 'undefined') { wsRetryCount++; if (wsRetryCount < 30) setTimeout(initPusher, 500); return; }
                const pusherKey = '{{ env("PUSHER_APP_KEY") }}';
                const pusherCluster = '{{ env("PUSHER_APP_CLUSTER") }}';
                if (!pusherKey) { console.warn('Pusher non configuré'); return; }
                try {
                    const pusher = new Pusher(pusherKey, { cluster: pusherCluster, encrypted: true });
                    if (pusherChannel) { try { pusherChannel.unbind_all(); } catch(e) {} }
                    pusherChannel = pusher.subscribe('new-messages');
                    pusherChannel.bind('App\\Events\\NewMessageReceived', (event) => {
                        if (currentEmail && currentEmail === event.email_client) {
                            lastMessagesHash = '';
                            loadMessages(true);
                            const modal = document.getElementById('chatModal');
                            if (modal && modal.classList.contains('active')) { playNotifSound(); }
                            else { unreadCount++; updateBadge(); showRobotNotification(); playNotifSound(); }
                        }
                    });
                    pusherListenerSet = true;
                    console.log('✅ Pusher connecté');
                } catch(e) { console.error('Erreur Pusher:', e); }
            }
            initPusher();
        }
        
        window.openChatAndPrefill = openChatAndPrefill;
        window.submitInitForm = submitInitForm;
        window.sendQuickMessage = sendQuickMessage;
        window.closeChatModal = closeChatModal;
        window.resetChat = resetChat;
        
        document.getElementById('chatSendBtn')?.addEventListener('click', sendQuickMessage);
        document.getElementById('chatTextarea')?.addEventListener('keypress', (e) => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendQuickMessage(); } });
        document.getElementById('chatTextarea')?.addEventListener('input', function() { this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 100) + 'px'; });
    })();
    </script>
</body>
</html>