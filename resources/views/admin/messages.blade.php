<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📩 Conversations
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($conversations->count() > 0)
                        <div id="conversationsList" class="space-y-2">
                            @foreach($conversations as $conv)
                                <div class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition conversation-item"
                                     data-email="{{ $conv->email_client }}"
                                     data-nom="{{ $conv->nom_complet }}">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="font-bold">{{ $conv->nom_complet }}</h3>
                                            <p class="text-sm text-gray-600">{{ $conv->email_client }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($conv->last_message_at)->diffForHumans() }}</span>
                                            <span class="badge-unread bg-yellow-500 text-white text-xs px-2 py-1 rounded-full ml-2" style="display: none;">●</span>
                                        </div>
                                    </div>
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
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-semibold">Conversation</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div id="modalMessages" class="h-96 overflow-y-auto p-4 space-y-3">
                <!-- Messages chargés ici -->
            </div>
            <div class="border-t p-4">
                <textarea id="replyText" rows="3" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Écrivez votre réponse..."></textarea>
                <button onclick="sendReply()" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    ✉️ Envoyer la réponse
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentEmail = '';
        
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', () => {
                currentEmail = item.dataset.email;
                const nom = item.dataset.nom;
                document.getElementById('modalTitle').innerHTML = `💬 Conversation avec ${nom}`;
                loadConversation(currentEmail);
                document.getElementById('conversationModal').classList.remove('hidden');
                document.getElementById('conversationModal').classList.add('flex');
                
                // Cacher le badge non lu
                const badge = item.querySelector('.badge-unread');
                if (badge) badge.style.display = 'none';
            });
        });
        
        async function loadConversation(email) {
            const response = await fetch(`/admin/messages/conversation/${encodeURIComponent(email)}`);
            const messages = await response.json();
            
            const container = document.getElementById('modalMessages');
            container.innerHTML = messages.map(msg => `
                <div class="${msg.reponse_admin ? 'bg-green-50 border-l-4 border-green-500' : 'bg-gray-100'} p-3 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <strong class="${msg.reponse_admin ? 'text-green-700' : 'text-gray-800'}">${msg.reponse_admin ? '📌 Support' : msg.nom_complet}</strong>
                        <small class="text-xs text-gray-500">${new Date(msg.created_at).toLocaleString()}</small>
                    </div>
                    <p class="text-gray-700">${escapeHtml(msg.message)}</p>
                    ${msg.reponse_admin ? `<p class="text-sm text-green-600 mt-1">⬆️ Réponse: ${escapeHtml(msg.reponse_admin)}</p>` : ''}
                </div>
            `).join('');
            
            container.scrollTop = container.scrollHeight;
        }
        
        async function sendReply() {
            const reply = document.getElementById('replyText').value.trim();
            if (!reply) {
                alert('Veuillez écrire une réponse');
                return;
            }
            
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
                loadConversation(currentEmail);
                showAdminNotification('✅ Réponse envoyée !');
            } else {
                alert('Erreur lors de l\'envoi de la réponse');
            }
        }
        
        function closeModal() {
            document.getElementById('conversationModal').classList.add('hidden');
            document.getElementById('conversationModal').classList.remove('flex');
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function showAdminNotification(msg) {
            let notif = document.getElementById('adminNotif');
            if (!notif) {
                notif = document.createElement('div');
                notif.id = 'adminNotif';
                notif.style.cssText = 'position:fixed;top:20px;right:20px;padding:12px 20px;background:#4CAF50;color:white;border-radius:8px;z-index:9999;font-size:14px;';
                document.body.appendChild(notif);
            }
            notif.textContent = msg;
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
                    const noConv = document.getElementById('noConversations');
                    
                    if (newList && currentList) {
                        currentList.innerHTML = newList.innerHTML;
                        // Réattacher les événements aux nouveaux éléments
                        document.querySelectorAll('.conversation-item').forEach(item => {
                            item.addEventListener('click', () => {
                                currentEmail = item.dataset.email;
                                const nom = item.dataset.nom;
                                document.getElementById('modalTitle').innerHTML = `💬 Conversation avec ${nom}`;
                                loadConversation(currentEmail);
                                document.getElementById('conversationModal').classList.remove('hidden');
                                document.getElementById('conversationModal').classList.add('flex');
                                
                                const badge = item.querySelector('.badge-unread');
                                if (badge) badge.style.display = 'none';
                            });
                        });
                    } else if (noConv && newList === null) {
                        // Aucune conversation
                    }
                })
                .catch(error => console.error('Erreur refresh:', error));
        }
        
        // WebSocket temps réel pour l'admin
        function initAdminEcho() {
            if (window.Echo) {
                console.log('Admin Echo prêt');
                
                window.Echo.channel('new-messages').listen('NewMessageReceived', (event) => {
                    console.log('Admin - Nouveau message reçu:', event);
                    showAdminNotification('📩 Nouveau message de ' + event.nom_complet);
                    
                    // Recharger la liste des conversations
                    refreshConversationsList();
                    
                    // Si la conversation est ouverte, on recharge les messages
                    if (currentEmail === event.email_client) {
                        loadConversation(currentEmail);
                    }
                    
                    // Mettre à jour le badge non lu dans la liste
                    const item = document.querySelector(`.conversation-item[data-email="${event.email_client}"]`);
                    if (item && currentEmail !== event.email_client) {
                        const badge = item.querySelector('.badge-unread');
                        if (badge) badge.style.display = 'inline-block';
                    }
                });
            } else {
                setTimeout(initAdminEcho, 1000);
            }
        }
        
        // Démarrer l'écoute WebSocket
        initAdminEcho();
        
        // Auto-refresh de la liste toutes les 10 secondes (fallback)
        setInterval(() => {
            refreshConversationsList();
        }, 10000);
    </script>
</x-app-layout>