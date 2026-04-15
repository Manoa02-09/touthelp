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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .chat-modal {
            display: none;
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 380px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            z-index: 1000;
            overflow: hidden;
        }
        .chat-modal.active {
            display: block;
            animation: fadeInUp 0.3s ease;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .chat-header {
            background: #1a3c34;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        .chat-body {
            padding: 20px;
            max-height: 500px;
            overflow-y: auto;
        }
        .chat-footer {
            background: #f3f4f6;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: gray;
        }
        
        /* Robot flottant - CORRIGÉ */
        .robot-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            left: auto !important;
            background: #1a3c34;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            z-index: 9999;
        }
        .robot-icon:hover {
            transform: scale(1.1);
            background: #0f2b24;
        }
        .robot-icon i {
            font-size: 30px;
            color: white;
        }
        .robot-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: bold;
            min-width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            border: 2px solid white;
        }
        .form-input, .form-textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.2s;
        }
        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: #1a3c34;
        }
        .btn-send {
            background: #1a3c34;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-send:hover {
            background: #0f2b24;
        }
        .btn-send:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .message-item {
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hidden { display: none; }
        
        /* Scrollbar personnalisée */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- En-tête avec logo et menu -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Logo" class="h-12">
                <span class="text-xl font-bold text-green-900">TOUT HELP</span>
            </div>
            <nav class="hidden md:flex space-x-6">
                <a href="#" class="text-gray-700 hover:text-green-800">ACCUEIL</a>
                <a href="#" class="text-gray-700 hover:text-green-800">À PROPOS</a>
                <a href="#" class="text-gray-700 hover:text-green-800">EXPERTISE</a>
                <a href="#" class="text-gray-700 hover:text-green-800">CATALOGUE</a>
                <a href="#" class="text-gray-700 hover:text-green-800">BLOG</a>
                <a href="#" class="text-gray-700 hover:text-green-800">CONTACT</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="#" class="bg-green-800 text-white px-4 py-2 rounded-full text-sm hover:bg-green-900">S'INSCRIRE</a>
            </div>
        </div>
    </header>

    <!-- Section héro -->
    <section class="relative bg-cover bg-center h-[500px]" style="background-image: url('{{ asset('images/krae0EbF.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative container mx-auto px-4 h-full flex flex-col justify-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">ENSEMBLE FAISONS DE LA PERFORMANCE UNE CULTURE</h1>
            <p class="text-xl mb-6">Expert en Solutions RH et Accompagnement sur-mesure à Madagascar.</p>
            <div class="flex flex-wrap gap-4">
                <input type="text" placeholder="Trouver une formation..." class="px-4 py-3 rounded-lg w-64 text-gray-800">
                <button class="bg-green-700 px-6 py-3 rounded-lg hover:bg-green-800">Rechercher</button>
            </div>
        </div>
    </section>

    <!-- Icône robot flottant -->
    <div class="robot-icon" id="robotIcon">
        <i class="fas fa-robot"></i>
        <span id="robotBadge" class="robot-badge hidden">0</span>
    </div>

    <!-- Modal de chat -->
    <div class="chat-modal" id="chatModal">
        <div class="chat-header">
            <i class="fas fa-headset mr-2"></i> Support client
        </div>
        <div class="chat-body" id="chatBody">
            <div id="chatMessages" class="mb-4 space-y-3 hidden"></div>
            
            <form id="chatForm">
                @csrf
                <input type="text" name="nom" id="nom" placeholder="Votre nom" class="form-input" required>
                <input type="email" name="email" id="email" placeholder="Votre email" class="form-input" required>
                <input type="text" name="telephone" id="telephone" placeholder="Votre téléphone" class="form-input">
                <textarea name="message" id="message" rows="3" placeholder="Votre message..." class="form-textarea" required></textarea>
                <button type="submit" id="sendBtn" class="btn-send">Envoyer</button>
            </form>
        </div>
        <div class="chat-footer">
            Nous vous répondrons dans les plus brefs délais.
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    
    <script>
    // Configuration Echo
    document.addEventListener('DOMContentLoaded', function() {
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
        }
    });

    // DOM Elements
    const robotIcon = document.getElementById('robotIcon');
    const chatModal = document.getElementById('chatModal');
    const chatForm = document.getElementById('chatForm');
    const chatBody = document.getElementById('chatBody');
    const messagesContainer = document.getElementById('chatMessages');
    
    let currentEmail = '';
    let currentNom = '';
    let webSocketSetup = false;
    let unreadMessages = new Set();

    // Fonction de notification sonore améliorée
    let audioUnlocked = false;
    
    function unlockAudio() {
        if (audioUnlocked) return;
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            ctx.resume();
            audioUnlocked = true;
            console.log('Audio débloqué');
        } catch(e) {}
    }
    
    function playNotificationSound() {
        try {
            // Tenter de débloquer l'audio au premier appel
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            if (ctx.state === 'suspended') {
                ctx.resume().then(() => {
                    playBeep(ctx);
                });
            } else {
                playBeep(ctx);
            }
        } catch(e) {
            console.log('Son non supporté');
        }
    }
    
    function playBeep(ctx) {
        try {
            const oscillator = ctx.createOscillator();
            const gainNode = ctx.createGain();
            oscillator.connect(gainNode);
            gainNode.connect(ctx.destination);
            oscillator.frequency.value = 880;
            gainNode.gain.value = 0.2;
            oscillator.start();
            gainNode.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.3);
            oscillator.stop(ctx.currentTime + 0.3);
        } catch(e) {}
    }

    // Débloquer l'audio au premier clic sur la page
    document.body.addEventListener('click', unlockAudio, { once: true });
    robotIcon.addEventListener('click', unlockAudio);

    // Badge management
    function updateRobotBadge() {
        const badge = document.getElementById('robotBadge');
        if (badge) {
            const count = unreadMessages.size;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    function incrementUnreadCount(messageId) {
        if (!unreadMessages.has(messageId)) {
            unreadMessages.add(messageId);
            updateRobotBadge();
        }
    }

    function resetUnreadCount() {
        unreadMessages.clear();
        updateRobotBadge();
    }

    // Modal controls
    robotIcon.addEventListener('click', () => {
        chatModal.classList.toggle('active');
        if (chatModal.classList.contains('active')){
            resetUnreadCount();
            if (currentEmail) loadMessages(currentEmail);
        }
    });

    window.addEventListener('click', (e) => {
        if (!chatModal.contains(e.target) && !robotIcon.contains(e.target)) {
            chatModal.classList.remove('active');
        }
    });

    // WebSocket setup
    function setupWebSocket(email) {
        if (webSocketSetup || !email || !window.Echo) return;
        
        const channelHash = CryptoJS.MD5(email).toString();
        
        window.Echo.private(`chat.${channelHash}`)
            .listen('NewMessageReceived', (event) => {
                if (!chatModal.classList.contains('active') || currentEmail !== event.email_client) {
                    incrementUnreadCount(event.id);
                    playNotificationSound();
                }
                if (currentEmail === event.email_client) {
                    loadMessages(currentEmail, true);
                }
            });
        
        webSocketSetup = true;
    }

    // Load messages
    async function loadMessages(email, silent = false) {
        if (!email) return;
        
        try {
            const response = await fetch(`/api/messages?email=${encodeURIComponent(email)}`);
            const messages = await response.json();
            
            if (messages.length > 0 && messagesContainer) {
                messagesContainer.classList.remove('hidden');
                
                let html = '';
                messages.forEach((msg) => {
                    html += `
                        <div class="message-item bg-gray-100 p-3 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <strong class="text-sm">${escapeHtml(msg.nom_complet)}</strong>
                                <small class="text-xs text-gray-500">${new Date(msg.created_at).toLocaleString()}</small>
                            </div>
                            <p class="text-sm text-gray-700">${escapeHtml(msg.message)}</p>
                            ${msg.reponse_admin ? `
                                <div class="mt-2 p-2 bg-green-50 rounded text-sm text-green-800">
                                    <i class="fas fa-reply mr-1"></i> <strong>Support :</strong> ${escapeHtml(msg.reponse_admin)}
                                </div>
                            ` : ''}
                        </div>
                    `;
                });
                
                messagesContainer.innerHTML = html;
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                switchToConversationView(email, messages[0].nom_complet);
                setupWebSocket(email);
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    }

    // Switch to conversation view
    function switchToConversationView(email, nom) {
        currentEmail = email;
        currentNom = nom;
        
        if (chatForm) chatForm.style.display = 'none';
        
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
            document.getElementById('quickSendBtn').addEventListener('click', sendQuickMessage);
            document.getElementById('quickMessage').addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendQuickMessage();
                }
            });
        }
        
        let changeIdentityBtn = document.getElementById('changeIdentity');
        if (!changeIdentityBtn) {
            changeIdentityBtn = document.createElement('button');
            changeIdentityBtn.id = 'changeIdentity';
            changeIdentityBtn.className = 'text-xs text-gray-500 mt-2 hover:text-gray-700 block transition';
            changeIdentityBtn.innerHTML = '<i class="fas fa-user-edit"></i> Changer d\'identité';
            changeIdentityBtn.onclick = resetToFullForm;
            chatBody.appendChild(changeIdentityBtn);
        }
        
        quickForm.style.display = 'block';
        changeIdentityBtn.style.display = 'block';
    }
    
    // Send quick message
    async function sendQuickMessage() {
        const messageInput = document.getElementById('quickMessage');
        const message = messageInput.value.trim();
        
        if (!message || !currentEmail) {
            showNotification('Veuillez écrire un message', 'error');
            return;
        }
        
        const sendBtn = document.getElementById('quickSendBtn');
        const originalHtml = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        sendBtn.disabled = true;
        
        try {
            const response = await fetch('/contact/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    nom: currentNom,
                    email: currentEmail,
                    telephone: document.getElementById('telephone')?.value || '',
                    message: message
                })
            });
            
            const data = await response.json();
            if (data.success) {
                messageInput.value = '';
                await loadMessages(currentEmail);
                showNotification('Message envoyé !');
            }
        } catch (error) {
            showNotification('Erreur de connexion', 'error');
        } finally {
            sendBtn.innerHTML = originalHtml;
            sendBtn.disabled = false;
        }
    }
    
    // Reset to full form
    function resetToFullForm() {
        currentEmail = '';
        currentNom = '';
        webSocketSetup = false;
        if (chatForm) chatForm.style.display = 'block';
        const quickForm = document.getElementById('quickForm');
        const changeIdentity = document.getElementById('changeIdentity');
        if (quickForm) quickForm.style.display = 'none';
        if (changeIdentity) changeIdentity.style.display = 'none';
        if (messagesContainer) {
            messagesContainer.innerHTML = '';
            messagesContainer.classList.add('hidden');
        }
        resetUnreadCount();
    }

    // Full form submit
    if (chatForm) {
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const nom = document.getElementById('nom').value;
            const email = document.getElementById('email').value;
            const telephone = document.getElementById('telephone').value;
            const message = document.getElementById('message').value;
            
            if (!nom || !email || !message) {
                showNotification('Veuillez remplir tous les champs', 'error');
                return;
            }
            
            const submitBtn = chatForm.querySelector('button');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
            submitBtn.disabled = true;
            
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
                
                const data = await response.json();
                if (data.success) {
                    await loadMessages(email);
                    setupWebSocket(email);
                    showNotification('Message envoyé !');
                }
            } catch (error) {
                showNotification('Erreur', 'error');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
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
            notif.style.cssText = 'position:fixed;bottom:100px;right:20px;padding:10px 15px;border-radius:8px;z-index:1002;font-size:13px;background:#333;color:white;animation:fadeInUp 0.3s ease';
            document.body.appendChild(notif);
        }
        notif.textContent = msg;
        notif.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
        notif.style.display = 'block';
        setTimeout(() => notif.style.display = 'none', 3000);
    }
    </script>
</body>
</html>