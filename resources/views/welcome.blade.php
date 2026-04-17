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
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        .chat-modal.active { display: block; animation: fadeInUp 0.3s ease; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .chat-header { background: #1a3c34; color: white; padding: 15px; text-align: center; font-weight: bold; }
        .chat-body { padding: 20px; max-height: 500px; overflow-y: auto; }
        .chat-footer { background: #f3f4f6; padding: 10px; text-align: center; font-size: 12px; color: gray; }
        
        /* Robot à DROITE */
        .robot-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
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
        .robot-icon:hover { transform: scale(1.1); background: #0f2b24; }
        .robot-icon i { font-size: 30px; color: white; }
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
        }
        .form-input:focus, .form-textarea:focus { outline: none; border-color: #1a3c34; }
        .btn-send {
            background: #1a3c34;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
        .btn-send:hover { background: #0f2b24; }
        .btn-send:disabled { opacity: 0.6; cursor: not-allowed; }
        .hidden { display: none; }
        
        @keyframes messageAppear {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .chat-message { animation: messageAppear 0.2s ease-out; }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
    </style>
</head>

<body class="bg-gray-50">

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

    <div class="robot-icon" id="robotIcon">
        <i class="fas fa-robot"></i>
        <span id="robotBadge" class="robot-badge hidden">0</span>
    </div>

    <div class="chat-modal" id="chatModal">
        <div class="chat-header">
            <i class="fas fa-headset mr-2"></i> Support client
        </div>
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
        <div class="chat-footer">
            Nous vous répondrons dans les plus brefs délais.
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    
    <script>
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

    // ========== AFFICHAGE DES MESSAGES ==========
    // Message envoyé (client) à DROITE en vert
    // Message reçu (support) à GAUCHE en gris
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
    </script>
</body>
</html>