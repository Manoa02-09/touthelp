<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $catalogue->titre }} - Tout Help</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
        }
        .title-italic {
            font-style: italic;
            text-transform: uppercase;
        }
        .card-dark {
            background: #0f172a;
            border-radius: 2.5rem;
            color: white;
        }
        .card-color-1 {
            background: #fff7ed;
            border: 2px solid #fdba74;
            border-radius: 2rem;
        }
        .card-color-2 {
            background: #f0f9ff;
            border: 2px solid #bae6fd;
            border-radius: 2rem;
        }
        .programme-section {
            background: #0f172a;
            border-radius: 3.5rem;
            color: #f8fafc;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.4);
        }
        .programme-rich-text {
            column-count: 2;
            column-gap: 4rem;
            width: 100%;
        }
        .programme-rich-text h3, 
        .programme-rich-text ul {
            break-inside: avoid;
        }
        @media (max-width: 1024px) {
            .programme-rich-text {
                column-count: 1;
            }
        }
        .programme-rich-text h3 {
            color: #fb923c !important;
            font-weight: 900;
            text-transform: uppercase;
            margin-top: 0;
            margin-bottom: 1.2rem;
            font-size: 1.2rem;
            letter-spacing: 0.05em;
        }
        .programme-rich-text ul {
            padding-left: 0;
            margin-bottom: 2.5rem;
            list-style: none;
        }
        .programme-rich-text li {
            margin-bottom: 0.8rem;
            color: #cbd5e1;
            position: relative;
            padding-left: 1.5rem;
            font-size: 1rem;
            line-height: 1.5;
        }
        .programme-rich-text li::before {
            content: "-";
            position: absolute;
            left: 0;
            color: #38bdf8;
            font-weight: bold;
        }
        .programme-rich-text strong {
            color: #fb923c;
        }

        /* ========== STYLES COMPLETS DU CHAT (ajout des bulles) ========== */
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
        .chat-close-btn { background: rgba(255,255,255,0.15); border: none; color: white; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; font-size: 14px; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
        .chat-body { flex: 1; overflow-y: auto; background-color: #fef9f9; max-height: 380px; }
        .chat-messages-area { padding: 14px; display: flex; flex-direction: column; gap: 8px; min-height: 100px; }
        .chat-input-area { background: white; border-top: 1px solid #ffe0e0; padding: 10px 12px; display: flex; gap: 8px; align-items: flex-end; }
        .chat-textarea { flex: 1; border: 1px solid #ffe0e0; border-radius: 20px; padding: 9px 14px; font-size: 13px; resize: none; outline: none; max-height: 100px; }
        .chat-send-btn { width: 38px; height: 38px; background: linear-gradient(135deg, #e63946, #ff6b6b); border: none; border-radius: 50%; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .chat-init-form { padding: 16px; }
        .chat-init-form input, .chat-init-form textarea { width: 100%; padding: 9px 12px; margin-bottom: 10px; border: 1px solid #ffe0e0; border-radius: 10px; font-size: 13px; outline: none; background: white; }
        .chat-init-btn { width: 100%; background: linear-gradient(135deg, #e63946, #ff6b6b); color: white; border: none; padding: 10px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; }
        .change-identity-btn { font-size: 11px; color: #e63946; background: none; border: none; cursor: pointer; text-align: center; width: 100%; padding: 6px; display: block; }
        .robot-icon { position: fixed; bottom: 20px; right: 20px; background: linear-gradient(135deg, #e63946, #ff6b6b); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 5px 20px rgba(230,57,70,0.4); transition: all 0.3s ease; z-index: 9999; }
        .robot-icon:hover { transform: scale(1.1); }
        .robot-icon i { font-size: 28px; color: white; }
        .robot-badge { position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 11px; font-weight: bold; min-width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0 4px; border: 2px solid white; animation: badgePulse 0.6s ease-in-out; }
        @keyframes badgePulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); background-color: #ef4444; } }
        .chat-footer { background: white; padding: 6px; text-align: center; font-size: 11px; color: #e63946; border-top: 1px solid #ffe0e0; }

        /* Bulles de discussion */
        .bubble-sent { display: flex; justify-content: flex-end; }
        .bubble-sent-inner { background: linear-gradient(135deg, #e63946, #ff6b6b); color: white; border-radius: 18px 18px 4px 18px; padding: 9px 13px; max-width: 75%; box-shadow: 0 2px 6px rgba(230,57,70,0.25); word-break: break-word; }
        .bubble-received { display: flex; justify-content: flex-start; align-items: flex-end; gap: 8px; }
        .bubble-received-avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #e63946, #ff6b6b); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; color: white; font-weight: 600; flex-shrink: 0; }
        .bubble-received-inner { background: white; color: #1f2937; border-radius: 18px 18px 18px 4px; padding: 9px 13px; max-width: 75%; box-shadow: 0 2px 6px rgba(0,0,0,0.07); word-break: break-word; }
        .bubble-text { font-size: 13px; line-height: 1.45; }
        .bubble-time { font-size: 10px; margin-top: 3px; text-align: right; opacity: 0.65; }
        .bubble-time-left { font-size: 10px; margin-top: 3px; opacity: 0.55; }
        .pending-tag { text-align: center; font-size: 11px; color: #e63946; background: rgba(255,255,255,0.9); border-radius: 20px; padding: 4px 12px; margin: 6px auto; width: fit-content; border: 1px solid #ffe0e0; }
    </style>
</head>
<body class="min-h-screen pb-20">

    <div class="container mx-auto px-6 py-10 max-w-6xl">
        
        <div class="flex justify-start mb-12">
            <a href="/" class="bg-[#f97316] hover:bg-orange-500 text-white text-[11px] font-black uppercase tracking-widest px-8 py-4 rounded-2xl transition-all shadow-xl shadow-orange-200 flex items-center gap-3">
                <i class="fas fa-arrow-left"></i> Retour au catalogue
            </a>
        </div>

        <div class="text-center mb-16">
            <span class="inline-block px-5 py-2 rounded-full bg-orange-100 text-orange-600 text-[10px] font-black uppercase mb-6 border border-orange-200">
                Formation Certifiante
            </span>
            <h1 class="text-4xl md:text-6xl font-black title-italic leading-tight mb-6 text-[#0f172a]">
                {{ $catalogue->titre }}
            </h1>
            <div class="w-32 h-2 bg-[#30a1de] mx-auto rounded-full"></div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10 mb-16">
            <div class="w-full lg:w-2/3 flex flex-col gap-8">
                <div class="card-dark p-10 shadow-2xl">
                    <h2 class="text-[#30a1de] text-xs font-black uppercase tracking-[0.2em] mb-4">Description du programme</h2>
                    <p class="text-slate-300 text-xl font-light leading-relaxed">
                        {{ $catalogue->description }}
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="card-color-1 p-8 flex-1">
                        <div class="w-12 h-12 rounded-2xl bg-orange-500 flex items-center justify-center text-white mb-6">
                            <i class="fas fa-bullseye text-xl"></i>
                        </div>
                        <h3 class="font-black text-[#0f172a] text-lg mb-3 uppercase italic">Objectifs</h3>
                        <p class="text-slate-700 text-sm">{!! nl2br(e($catalogue->objectifs)) !!}</p>
                    </div>

                    <div class="card-color-2 p-8 flex-1">
                        <div class="w-12 h-12 rounded-2xl bg-[#30a1de] flex items-center justify-center text-white mb-6">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <h3 class="font-black text-[#0f172a] text-lg mb-3 uppercase italic">Public visé</h3>
                        <p class="text-slate-700 text-sm">{!! nl2br(e($catalogue->public_vise)) !!}</p>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-[3rem] p-10 border-[3px] border-[#0f172a] shadow-[15px_15px_0px_0px_#30a1de] flex flex-col h-full">
                    <h3 class="text-2xl font-black title-italic mb-6 text-[#0f172a]">DOCUMENTATION</h3>
                    <div class="space-y-4">
                        @if($catalogue->fichier_pdf)
                        <a href="{{ asset('storage/'.$catalogue->fichier_pdf) }}" class="flex items-center justify-center gap-4 w-full bg-[#30a1de] text-white font-black py-5 rounded-2xl shadow-lg">
                            <i class="fas fa-file-pdf"></i> SYLLABUS PDF
                        </a>
                        @endif
                        <button onclick="openChatAndPrefill()" class="flex items-center justify-center gap-4 w-full bg-[#0f172a] text-white font-black py-5 rounded-2xl cursor-pointer">
                            <i class="fas fa-paper-plane"></i> DEMANDER UN DEVIS
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if($catalogue->programme)
        <div class="w-full">
            <div class="programme-section p-10 md:p-20">
                <div class="flex flex-col items-center gap-6 mb-16 text-center">
                    <div class="bg-[#30a1de] text-white w-20 h-20 rounded-[2rem] flex items-center justify-center text-3xl shadow-xl shadow-blue-500/20">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl md:text-5xl font-black title-italic uppercase">Le Programme Détaillé</h2>
                        <p class="text-blue-400 font-bold uppercase tracking-widest text-[10px] mt-2">Structure complète en colonnes</p>
                    </div>
                </div>

                <div class="programme-rich-text prose prose-invert max-w-none">
                    {!! $catalogue->programme !!}
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- BULLE CHAT -->
    <div class="robot-icon" id="robotIcon" role="button" aria-label="Ouvrir le support">
        <i class="fas fa-robot"></i>
        <span id="robotBadge" class="robot-badge" style="display:none;" aria-live="polite">0</span>
    </div>

    <div class="chat-modal" id="chatModal" role="dialog" aria-modal="true" aria-label="Chat support">
        <div class="chat-header">
            <div class="chat-header-left">
                <div class="chat-header-avatar">🤖</div>
                <div>
                    <div class="chat-header-name">Support Tout Help</div>
                    <div class="chat-header-status" style="font-size: 11px; opacity: 0.8; display: flex; align-items: center; gap: 4px;">
                        <span class="chat-status-dot" style="width: 6px; height: 6px; background: #4ade80; border-radius: 50%; display: inline-block;"></span> En ligne
                    </div>
                </div>
            </div>
            <button class="chat-close-btn" onclick="closeChatModal()" aria-label="Fermer le chat">✕</button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="chat-messages-area" id="chatMessagesArea"></div>
        </div>
        <div id="chatInputArea" class="chat-input-area" style="display:none;">
            <textarea id="chatTextarea" class="chat-textarea" rows="1" placeholder="Écrivez votre message..." maxlength="1000" aria-label="Votre message"></textarea>
            <button id="chatSendBtn" class="chat-send-btn" aria-label="Envoyer">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M22 2 11 13M22 2 15 22l-4-9-9-4 20-7z"/></svg>
            </button>
        </div>
        <div id="chatInitForm" class="chat-init-form">
            <div style="text-align:center;margin-bottom:12px;"><p style="font-size:13px;color:#e63946;">Bonjour ! 👋 Pour commencer, présentez-vous :</p></div>
            <input type="text"  id="initNom"     placeholder="Votre nom complet *"         maxlength="150" autocomplete="name"  aria-label="Nom">
            <input type="email" id="initEmail"   placeholder="Votre email *"                maxlength="150" autocomplete="email" aria-label="Email">
            <input type="tel"   id="initTel"     placeholder="Votre téléphone (optionnel)" maxlength="30"  autocomplete="tel"   aria-label="Téléphone">
            <textarea           id="initMessage" rows="2" placeholder="Votre message *" style="resize:none;" maxlength="1000" aria-label="Message"></textarea>
            <button class="chat-init-btn" id="initSendBtn" onclick="submitInitForm()">
                <i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation
            </button>
        </div>
        <div id="changeIdentityBar" style="display:none;background:white;border-top:1px solid #ffe0e0;">
            <button class="change-identity-btn" onclick="resetChat()"><i class="fas fa-user-edit mr-1"></i> Nouvelle conversation</button>
        </div>
        <div class="chat-footer">Réponse dans les plus brefs délais · Tout Help</div>
    </div>

    @vite(['resources/js/app.js'])
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>
(function() {
    "use strict";
    
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
        while (requestTimestamps.length > 0 && requestTimestamps[0] < now - 60000) {
            requestTimestamps.shift();
        }
        if (requestTimestamps.length >= MAX_REQUESTS_PER_MINUTE) {
            flashError('Trop de tentatives. Veuillez patienter une minute.');
            return true;
        }
        const last = rateLimits.get(email) || 0;
        if (now - last < RATE_LIMIT_DELAY) {
            flashError('Merci de patienter quelques secondes avant de renvoyer.');
            return true;
        }
        rateLimits.set(email, now);
        requestTimestamps.push(now);
        return false;
    }
    
    function checkRateLimit(email) { return !isRateLimited(email); }
    
    let audioEnabled = false;
    let pendingSounds = [];
    
    function initAudio() {
        if (!audioCtx) {
            try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch(e) {}
        }
    }
    
    function enableAudio() {
        if (audioEnabled) return;
        initAudio();
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume().then(() => {
                audioEnabled = true;
                pendingSounds.forEach(() => playNotifSound());
                pendingSounds = [];
            }).catch(e => console.warn('Audio resume failed:', e));
        } else if (audioCtx && audioCtx.state === 'running') {
            audioEnabled = true;
        }
    }
    
    function playNotifSound() {
        if (!audioEnabled) {
            pendingSounds.push(true);
            return;
        }
        try {
            initAudio();
            if (!audioCtx || audioCtx.state !== 'running') return;
            const now = audioCtx.currentTime;
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
        } catch(e) { console.warn('Erreur lecture son:', e); }
    }
    
    document.addEventListener('click', enableAudio, { once: true });
    document.getElementById('robotIcon')?.addEventListener('click', enableAudio);
    
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
        setTimeout(() => robot.classList.remove('robot-notification'), 1000);
    }
    
    function openChatModal() {
        console.log("openChatModal appelée");
        const modal = document.getElementById('chatModal');
        if (!modal) {
            console.error("chatModal introuvable");
            return;
        }
        modal.classList.add('active');
        unreadCount = 0;
        updateBadge();
        if (currentEmail) { loadMessages(true); startPolling(); }
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
        closeChatModal();
    });
    
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        closeChatModal();
    });
    
    function scrollChatToBottom() {
        setTimeout(() => {
            const b = document.getElementById('chatBody');
            if (b) b.scrollTop = b.scrollHeight;
        }, 100);
    }
    
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
    
    function generateMessagesHash(messages) {
        if (!messages || messages.length === 0) return '';
        const lastMsg = messages[messages.length - 1];
        return `${lastMsg?.id || ''}-${lastMsg?.updated_at || ''}-${messages.length}`;
    }
    
    function renderMessages(messages) {
        const area = document.getElementById('chatMessagesArea');
        if (!messages || messages.length === 0) {
            area.innerHTML = '<div class="pending-tag">⏳ En attente de réponse...</div>';
            return;
        }
        const frag = document.createDocumentFragment();
        for (let i = 0; i < messages.length; i++) {
            const m = messages[i];
            const sentDiv = document.createElement('div');
            sentDiv.className = 'bubble-sent';
            const sentInner = document.createElement('div');
            sentInner.className = 'bubble-sent-inner';
            const sentTxt = document.createElement('div');
            sentTxt.className = 'bubble-text';
            sentTxt.textContent = escapeHtml(m.message);
            const sentTime = document.createElement('div');
            sentTime.className = 'bubble-time';
            sentTime.textContent = formatTime(m.created_at);
            sentInner.append(sentTxt, sentTime);
            sentDiv.appendChild(sentInner);
            frag.appendChild(sentDiv);
            if (m.reponse_admin && m.reponse_admin.trim()) {
                const recvDiv = document.createElement('div');
                recvDiv.className = 'bubble-received';
                const av = document.createElement('div');
                av.className = 'bubble-received-avatar';
                av.textContent = 'TH';
                const recvInner = document.createElement('div');
                recvInner.className = 'bubble-received-inner';
                const recvTxt = document.createElement('div');
                recvTxt.className = 'bubble-text';
                recvTxt.textContent = escapeHtml(m.reponse_admin);
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
                lastMessagesHash = currentHash;
                if (messages.length > 0) lastMessageId = messages[messages.length - 1]?.id;
                const isChatOpen = document.getElementById('chatModal').classList.contains('active');
                if (!isChatOpen && isNewContent && !force) {
                    unreadCount++;
                    updateBadge();
                    showRobotNotification();
                    playNotifSound();
                }
                renderMessages(messages);
                if (isChatOpen) scrollChatToBottom();
            }
        } catch(e) { console.warn('[Chat] loadMessages error:', e.message); }
        finally { isLoading = false; }
    }
    
    async function sendMessageAPI(nom, email, telephone, message) {
        if (!isValidName(nom)) return { success: false, message: 'Nom invalide (2-150 caractères, lettres uniquement).' };
        if (!isValidEmail(email)) return { success: false, message: 'Email invalide.' };
        if (!isValidPhone(telephone)) return { success: false, message: 'Téléphone invalide.' };
        if (!isValidMessage(message)) return { success: false, message: 'Message invalide (2-1000 caractères, pas de code HTML).' };
        if (!checkRateLimit(email)) return { success: false, message: '' };
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrf) return { success: false, message: 'Erreur de sécurité. Rechargez la page.' };
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
                body: JSON.stringify({ nom: cleanNom, email: cleanEmail, telephone: cleanTel, message: cleanMsg })
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return await res.json();
        } catch(e) {
            console.warn('[Chat] sendMessageAPI error:', e.message);
            return { success: false, message: 'Erreur réseau. Vérifiez votre connexion.' };
        }
    }
    
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
            } else {
                wsRetryCount++;
                if (wsRetryCount < 30) setTimeout(trySetup, 500);
            }
        }
        trySetup();
    }
    
    setupEchoListener();
    
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
    
    // ========== FONCTION CORRIGÉE POUR LE BOUTON "DEMANDER UN DEVIS" ==========
    window.openChatAndPrefill = function() {
        console.log("openChatAndPrefill exécutée");
        const titreFormation = document.querySelector('h1')?.innerText || "cette formation";
        const messageDevis = `Bonjour, je souhaite discuter d'une formation "${titreFormation}". Pouvez-vous me contacter pour un devis ?`;
        
        // Ouvrir le chat
        openChatModal();
        
        // Attendre que le DOM du chat soit prêt (avec une marge importante)
        setTimeout(() => {
            const initForm = document.getElementById('chatInitForm');
            const inputArea = document.getElementById('chatInputArea');
            
            if (initForm && initForm.style.display !== 'none') {
                const msgField = document.getElementById('initMessage');
                if (msgField) {
                    msgField.value = messageDevis;
                    msgField.focus();
                    msgField.style.backgroundColor = '#fff0f0';
                    setTimeout(() => { if (msgField) msgField.style.backgroundColor = ''; }, 1000);
                    console.log("Message pré-rempli dans le formulaire d'init");
                } else {
                    console.warn("initMessage non trouvé");
                }
            } 
            else if (inputArea && inputArea.style.display !== 'none') {
                const chatField = document.getElementById('chatTextarea');
                if (chatField) {
                    chatField.value = messageDevis;
                    if (currentEmail) {
                        setTimeout(() => sendQuickMessage(), 300);
                        console.log("Message pré-rempli et envoyé (conversation active)");
                    } else {
                        flashError("Veuillez d'abord vous identifier via le formulaire de conversation.");
                    }
                } else {
                    console.warn("chatTextarea non trouvé");
                }
            } else {
                console.warn("Aucune zone de saisie visible, réessai dans 500ms");
                // Second essai après un délai supplémentaire
                setTimeout(() => {
                    const initForm2 = document.getElementById('chatInitForm');
                    const inputArea2 = document.getElementById('chatInputArea');
                    if (initForm2 && initForm2.style.display !== 'none') {
                        const msgField = document.getElementById('initMessage');
                        if (msgField) msgField.value = messageDevis;
                    } else if (inputArea2 && inputArea2.style.display !== 'none') {
                        const chatField = document.getElementById('chatTextarea');
                        if (chatField) chatField.value = messageDevis;
                    }
                }, 800);
            }
        }, 600);
    };
    
    window.scrollPartenaire = function() {};
    window.submitInitForm = submitInitForm;
    window.sendQuickMessage = sendQuickMessage;
    window.closeChatModal = closeChatModal;
    window.resetChat = resetChat;
    
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('chatSendBtn')?.addEventListener('click', sendQuickMessage);
        document.getElementById('chatTextarea')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendQuickMessage(); }
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