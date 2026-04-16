<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📩 Conversations
            </h2>
            <div class="flex items-center gap-3">
                <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800 transition">
                    <i class="fas fa-check-double"></i> Tout marquer lu
                </button>
                <button onclick="closeAllConversations()" class="text-sm text-orange-600 hover:text-orange-800 transition">
                    <i class="fas fa-lock"></i> Clôturer sélection
                </button>
                <span id="totalUnread" class="text-sm bg-red-500 text-white px-2 py-1 rounded-full min-w-[60px] text-center">0 non lus</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Barre de recherche -->
                    <div class="mb-4">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" id="searchConversation" 
                                   placeholder="Rechercher par nom, email ou message..." 
                                   class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="flex gap-2 mb-4 pb-4 border-b flex-wrap">
                        <button onclick="filterConversations('all')" id="filterAll" class="filter-btn px-3 py-1 rounded-full text-sm bg-gray-200 text-gray-700 hover:bg-gray-300 transition">📋 Toutes</button>
                        <button onclick="filterConversations('unread')" id="filterUnread" class="filter-btn px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition">🔴 Non lues</button>
                        <button onclick="filterConversations('unanswered')" id="filterUnanswered" class="filter-btn px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition">⏳ Sans réponse</button>
                        <button onclick="filterConversations('closed')" id="filterClosed" class="filter-btn px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition">🔒 Clôturées</button>
                    </div>

                    @if($conversations->count() > 0)
                        <div id="conversationsList" class="space-y-2">
                            @foreach($conversations as $conv)
                                @php
                                    $lastMessage = \App\Models\Message::where('email_client', $conv->email_client)->latest()->first();
                                    $hasResponse = $lastMessage && $lastMessage->reponse_admin && $lastMessage->reponse_admin !== '';
                                    $isUnread = ($conv->unread_count ?? 0) > 0;
                                    $isClosed = $conv->closed ?? false;
                                @endphp
                                <div class="conversation-item border rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-all duration-200 group
                                    {{ $isUnread ? 'border-l-4 border-l-red-500 bg-red-50' : '' }}
                                    {{ !$hasResponse && !$isClosed ? 'border-l-4 border-l-yellow-500 bg-yellow-50' : '' }}
                                    {{ $isClosed ? 'opacity-70 bg-gray-100 border-l-4 border-l-gray-500' : '' }}"
                                     data-email="{{ $conv->email_client }}"
                                     data-nom="{{ $conv->nom_complet }}"
                                     data-closed="{{ $isClosed ? 'true' : 'false' }}"
                                     data-has-response="{{ $hasResponse ? 'true' : 'false' }}">
                                    
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full 
                                                    {{ $isUnread ? 'bg-red-100' : ($isClosed ? 'bg-gray-200' : ($hasResponse ? 'bg-green-100' : 'bg-yellow-100')) }} 
                                                    flex items-center justify-center">
                                                    <i class="fas {{ $isClosed ? 'fa-lock' : ($hasResponse ? 'fa-check-circle' : 'fa-clock') }} 
                                                        {{ $isUnread ? 'text-red-600' : ($isClosed ? 'text-gray-500' : ($hasResponse ? 'text-green-600' : 'text-yellow-600')) }}"></i>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <h3 class="font-bold text-gray-800">{{ $conv->nom_complet }}</h3>
                                                        @if($isClosed)
                                                            <span class="text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">🔒 Clôturée</span>
                                                        @elseif(!$hasResponse)
                                                            <span class="text-xs bg-yellow-500 text-white px-2 py-0.5 rounded-full">⏳ Sans réponse</span>
                                                        @else
                                                            <span class="text-xs bg-green-500 text-white px-2 py-0.5 rounded-full">✓ Répondu</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-gray-500">{{ $conv->email_client }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($conv->last_message_at)->diffForHumans() }}</span>
                                            <div class="flex items-center gap-1 mt-1 justify-end">
                                                @if($isUnread)
                                                    <span class="badge-unread bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $conv->unread_count ?? 0 }}</span>
                                                @endif
                                                <button onclick="event.stopPropagation(); toggleCloseConversation('{{ $conv->email_client }}', {{ $isClosed ? 'false' : 'true' }})" 
                                                        class="text-xs {{ $isClosed ? 'text-green-600 hover:text-green-800' : 'text-gray-400 hover:text-orange-600' }} transition ml-2">
                                                    <i class="fas {{ $isClosed ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                                    {{ $isClosed ? 'Rouvrir' : 'Clôturer' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($lastMessage)
                                        <div class="mt-2 text-xs text-gray-500 border-t pt-2">
                                            <i class="fas fa-reply fa-flip-horizontal mr-1"></i>
                                            {{ \Str::limit($lastMessage->message, 80) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p id="noConversations" class="text-gray-500 text-center py-8">Aucune conversation pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal conversation -->
    <div id="conversationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4">
            <div class="border-b px-6 py-4 flex justify-between items-center bg-gray-50 rounded-t-lg">
                <h3 id="modalTitle" class="text-lg font-semibold flex items-center gap-2">
                    <i class="fas fa-comment-dots text-green-600"></i>
                    Conversation
                </h3>
                <div class="flex items-center gap-3">
                    <button onclick="toggleCloseCurrentConversation()" id="closeConvBtn" class="text-sm text-orange-600 hover:text-orange-800 transition">
                        <i class="fas fa-lock"></i> Clôturer
                    </button>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl transition">&times;</button>
                </div>
            </div>
            
            <div id="modalMessages" class="h-96 overflow-y-auto p-4 space-y-3 bg-gray-50">
                <!-- Messages chargés ici -->
            </div>
            
            <div class="border-t p-4 bg-white rounded-b-lg" id="replyContainer">
                <div class="flex gap-2">
                    <textarea id="replyText" rows="2" class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Écrivez votre réponse..."></textarea>
                    <button onclick="sendReply()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Envoyer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes blink {
            0%, 100% { background-color: #fef3c7; }
            50% { background-color: #fde68a; }
        }
        
        .badge-unread {
            transition: all 0.2s ease;
            display: inline-block;
            animation: pulse 1s ease-in-out infinite;
        }
        .conversation-item.highlight {
            animation: blink 1s ease-in-out 2;
        }
        #adminNotif {
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .conversation-item {
            transition: all 0.2s ease;
        }
        .conversation-item:hover {
            transform: translateX(4px);
        }
        .filter-active {
            background-color: #3b82f6 !important;
            color: white !important;
        }
    </style>

    <script>
        // ========== VARIABLES GLOBALES ==========
        let currentEmail = '';
        let currentClosed = false;
        let unreadCounts = {};
        let lastSoundTime = {};
        let refreshInterval = null;
        let currentFilter = 'all';
        let closedConversations = new Set();

        // Initialiser closedConversations depuis les éléments existants
        document.querySelectorAll('.conversation-item').forEach(item => {
            if (item.dataset.closed === 'true') {
                closedConversations.add(item.dataset.email);
            }
        });

        // ========== RECHERCHE ==========
        const searchInput = document.getElementById('searchConversation');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.conversation-item').forEach(item => {
                    const text = item.innerText.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });
        }

        // ========== NOTIFICATION SONORE ==========
        let audioUnlocked = false;
        
        function unlockAudio() {
            if (audioUnlocked) return;
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                ctx.resume();
                audioUnlocked = true;
            } catch(e) {}
        }

        function playNotificationSound(email) {
            const now = Date.now();
            if (lastSoundTime[email] && (now - lastSoundTime[email]) < 2000) {
                return;
            }
            lastSoundTime[email] = now;
            
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                if (ctx.state === 'suspended') {
                    ctx.resume().then(() => playBeep(ctx));
                } else {
                    playBeep(ctx);
                }
            } catch(e) {}
        }

        function playBeep(ctx) {
            try {
                const oscillator = ctx.createOscillator();
                const gainNode = ctx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);
                oscillator.frequency.value = 880;
                gainNode.gain.value = 0.15;
                oscillator.start();
                gainNode.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.3);
                oscillator.stop(ctx.currentTime + 0.3);
            } catch(e) {}
        }

        document.body.addEventListener('click', unlockAudio, { once: true });

        // ========== GESTION DES BADGES ==========
        function updateTotalUnread() {
            let total = 0;
            for (let email in unreadCounts) {
                total += unreadCounts[email];
            }
            const totalSpan = document.getElementById('totalUnread');
            if (totalSpan) {
                totalSpan.textContent = total + (total > 1 ? ' non lus' : ' non lu');
                totalSpan.style.backgroundColor = total > 0 ? '#ef4444' : '#6b7280';
            }
            document.title = total > 0 ? `(${total}) 📩 Conversations` : '📩 Conversations';
        }

        function updateBadgeDisplay(email) {
            const item = document.querySelector(`.conversation-item[data-email="${email}"]`);
            if (item) {
                const badge = item.querySelector('.badge-unread');
                const count = unreadCounts[email] || 0;
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
            updateTotalUnread();
        }

        function incrementUnreadBadge(email, nom) {
            unreadCounts[email] = (unreadCounts[email] || 0) + 1;
            updateBadgeDisplay(email);
            
            if (currentEmail !== email) {
                playNotificationSound(email);
                showAdminNotification(`📩 ${nom} vous a envoyé un message`);
                
                const item = document.querySelector(`.conversation-item[data-email="${email}"]`);
                if (item) {
                    item.classList.add('highlight');
                    setTimeout(() => item.classList.remove('highlight'), 1000);
                }
            }
        }

        function resetUnreadBadge(email) {
            if (unreadCounts[email]) {
                unreadCounts[email] = 0;
                updateBadgeDisplay(email);
            }
        }

        // ========== GESTION DES CONVERSATIONS CLÔTURÉES ==========
        function toggleCloseConversation(email, close) {
            fetch('/admin/messages/toggle-close', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email_client: email, closed: close })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (close) {
                        closedConversations.add(email);
                        showAdminNotification('🔒 Conversation clôturée');
                    } else {
                        closedConversations.delete(email);
                        showAdminNotification('🔓 Conversation rouverte');
                    }
                    // Rafraîchir la liste sans incrémenter le badge
                    refreshConversationsList();
                } else {
                    showAdminNotification('Erreur: ' + (data.message || 'Inconnue'), 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAdminNotification('Erreur de connexion', 'error');
            });
        }

        function toggleCloseCurrentConversation() {
            const isClosed = closedConversations.has(currentEmail);
            toggleCloseConversation(currentEmail, !isClosed);
            closeModal();
        }

        function closeAllConversations() {
            if (confirm('Clôturer toutes les conversations ?')) {
                document.querySelectorAll('.conversation-item').forEach(item => {
                    const email = item.dataset.email;
                    if (!closedConversations.has(email)) {
                        toggleCloseConversation(email, true);
                    }
                });
            }
        }

        function markAllAsRead() {
            for (let email in unreadCounts) {
                unreadCounts[email] = 0;
                updateBadgeDisplay(email);
            }
            showAdminNotification('✓ Tous les messages ont été marqués comme lus');
        }

        // ========== FILTRES ==========
        function filterConversations(filter) {
            currentFilter = filter;
            
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('filter-active');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            });
            
            const activeBtn = document.getElementById(`filter${filter.charAt(0).toUpperCase() + filter.slice(1)}`);
            if (activeBtn) {
                activeBtn.classList.add('filter-active');
                activeBtn.classList.remove('bg-gray-100', 'text-gray-600');
            }
            
            document.querySelectorAll('.conversation-item').forEach(item => {
                const email = item.dataset.email;
                const isUnread = (unreadCounts[email] || 0) > 0;
                const isClosed = closedConversations.has(email);
                const hasResponse = item.dataset.hasResponse === 'true';
                const isUnanswered = !hasResponse && !isClosed;
                
                let show = false;
                switch(filter) {
                    case 'all': show = true; break;
                    case 'unread': show = isUnread; break;
                    case 'unanswered': show = isUnanswered; break;
                    case 'closed': show = isClosed; break;
                }
                item.style.display = show ? 'block' : 'none';
            });
        }

        // ========== GESTION DES CONVERSATIONS ==========
        function attachConversationEvents() {
            document.querySelectorAll('.conversation-item').forEach(item => {
                const newItem = item.cloneNode(true);
                item.parentNode.replaceChild(newItem, item);
                
                newItem.addEventListener('click', (e) => {
                    if (e.target.closest('button')) return;
                    currentEmail = newItem.dataset.email;
                    currentClosed = closedConversations.has(currentEmail);
                    const nom = newItem.dataset.nom;
                    
                    document.getElementById('modalTitle').innerHTML = `<i class="fas fa-comment-dots text-green-600"></i> Conversation avec ${nom}`;
                    const closeBtn = document.getElementById('closeConvBtn');
                    closeBtn.innerHTML = currentClosed ? '<i class="fas fa-lock-open"></i> Rouvrir' : '<i class="fas fa-lock"></i> Clôturer';
                    closeBtn.style.color = currentClosed ? '#10b981' : '#ea580c';
                    
                    loadConversation(currentEmail);
                    document.getElementById('conversationModal').classList.remove('hidden');
                    document.getElementById('conversationModal').classList.add('flex');
                    resetUnreadBadge(currentEmail);
                });
            });
        }

        async function loadConversation(email) {
            try {
                const response = await fetch(`/admin/messages/conversation/${encodeURIComponent(email)}`);
                const messages = await response.json();
                
                // Reset le badge quand on ouvre la conversation
                resetUnreadBadge(email);
                
                const container = document.getElementById('modalMessages');
                const isClosed = closedConversations.has(email);
                const replyContainer = document.getElementById('replyContainer');
                
                if (replyContainer) {
                    replyContainer.style.display = isClosed ? 'none' : 'block';
                }
                
                if (messages.length === 0) {
                    container.innerHTML = '<div class="text-center text-gray-500 py-8">Aucun message dans cette conversation</div>';
                    return;
                }
                
                container.innerHTML = messages.map(msg => `
                    <div class="flex ${msg.reponse_admin ? 'justify-end' : 'justify-start'}">
                        <div class="max-w-[80%] ${msg.reponse_admin ? 'bg-green-100' : 'bg-white'} rounded-lg p-3 shadow-sm">
                            <div class="flex items-center gap-2 mb-1">
                                <strong class="${msg.reponse_admin ? 'text-green-700' : 'text-gray-800'} text-sm">
                                    ${msg.reponse_admin ? '<i class="fas fa-headset"></i> Support' : escapeHtml(msg.nom_complet)}
                                </strong>
                                <small class="text-xs text-gray-400">${new Date(msg.created_at).toLocaleString()}</small>
                            </div>
                            <p class="text-gray-700">${escapeHtml(msg.message)}</p>
                            ${msg.reponse_admin && msg.reponse_admin !== msg.message ? `<p class="text-sm text-green-600 mt-2 pt-2 border-t border-green-200">⬆️ ${escapeHtml(msg.reponse_admin)}</p>` : ''}
                        </div>
                    </div>
                `).join('');
                
                container.scrollTop = container.scrollHeight;
            } catch (error) {
                console.error('Erreur chargement:', error);
            }
        }
        
        async function sendReply() {
            if (closedConversations.has(currentEmail)) {
                showAdminNotification('Cette conversation est clôturée', 'error');
                return;
            }
            
            const reply = document.getElementById('replyText').value.trim();
            if (!reply) {
                showAdminNotification('Veuillez écrire une réponse', 'error');
                return;
            }
            
            const sendBtn = document.querySelector('#conversationModal button');
            const originalText = sendBtn.innerHTML;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
            sendBtn.disabled = true;
            
            try {
                const response = await fetch('/admin/messages/reply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email_client: currentEmail,
                        reponse_admin: reply
                    })
                });
                
                if (response.ok) {
                    document.getElementById('replyText').value = '';
                    await loadConversation(currentEmail);
                    
                    // Reset le badge après réponse
                    resetUnreadBadge(currentEmail);
                    
                    const item = document.querySelector(`.conversation-item[data-email="${currentEmail}"]`);
                    if (item) {
                        item.dataset.hasResponse = 'true';
                        item.classList.remove('border-l-yellow-500', 'bg-yellow-50');
                        item.classList.add('border-l-green-500', 'bg-green-50');
                        
                        const badge = item.querySelector('.badge-unread');
                        if (badge) badge.style.display = 'none';
                    }
                    showAdminNotification('✅ Réponse envoyée !');
                    refreshConversationsList();
                } else {
                    showAdminNotification('Erreur lors de l\'envoi', 'error');
                }
            } catch (error) {
                showAdminNotification('Erreur de connexion', 'error');
            } finally {
                sendBtn.innerHTML = originalText;
                sendBtn.disabled = false;
            }
        }
        
        function closeModal() {
            document.getElementById('conversationModal').classList.add('hidden');
            document.getElementById('conversationModal').classList.remove('flex');
            document.getElementById('replyText').value = '';
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function showAdminNotification(msg, type = 'success') {
            let notif = document.getElementById('adminNotif');
            if (!notif) {
                notif = document.createElement('div');
                notif.id = 'adminNotif';
                notif.style.cssText = 'position:fixed;top:20px;right:20px;padding:12px 20px;border-radius:8px;z-index:9999;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:10000';
                document.body.appendChild(notif);
            }
            notif.textContent = msg;
            notif.style.backgroundColor = type === 'success' ? '#10b981' : '#ef4444';
            notif.style.color = 'white';
            notif.style.display = 'block';
            setTimeout(() => notif.style.display = 'none', 3000);
        }
        
        function refreshConversationsList() {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newList = doc.getElementById('conversationsList');
                    const currentList = document.getElementById('conversationsList');
                    
                    if (newList && currentList) {
                        currentList.innerHTML = newList.innerHTML;
                        attachConversationEvents();
                        filterConversations(currentFilter);
                        
                        document.querySelectorAll('.conversation-item').forEach(item => {
                            const email = item.dataset.email;
                            if (item.dataset.closed === 'true') {
                                closedConversations.add(email);
                            } else {
                                closedConversations.delete(email);
                            }
                        });
                    }
                })
                .catch(error => console.error('Erreur refresh:', error));
        }
        
        // ========== WEBSOCKET ==========
        function initAdminEcho() {
            if (window.Echo) {
                window.Echo.channel('new-messages').listen('NewMessageReceived', (event) => {
                    const isClosed = closedConversations.has(event.email_client);
                    incrementUnreadBadge(event.email_client, event.nom_complet);
                    refreshConversationsList();
                    if (currentEmail === event.email_client && !isClosed) {
                        loadConversation(currentEmail);
                    } else if (currentEmail === event.email_client && isClosed) {
                        showAdminNotification(`⚠️ Message de ${event.nom_complet} mais conversation clôturée`, 'error');
                    }
                });
            } else {
                setTimeout(initAdminEcho, 1000);
            }
        }
        
        // ========== INITIALISATION ==========
        attachConversationEvents();
        initAdminEcho();
        
        setInterval(() => refreshConversationsList(), 10000);
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.getElementById('conversationModal').classList.contains('flex')) {
                closeModal();
            }
        });
        
        filterConversations('all');
    </script>
</x-app-layout>