@extends('layouts.admin')

@section('page-title', 'Discussions')
@section('page-subtitle', 'Gérez vos conversations avec les clients')

@section('content')

@php
    // ===== COMPTEURS CORRECTS basés sur reponse_admin =====
    $totalConv   = $conversations->count();
    $nonRepondus = $conversations->filter(function($conv) {
        $last = \App\Models\Message::where('email_client', $conv->email_client)->latest()->first();
        return !$last || !$last->reponse_admin || $last->reponse_admin === '';
    })->count();
    $archivees = $conversations->where('closed', true)->count();
    $repondues = $conversations->where('closed', false)->filter(function($conv) {
        $last = \App\Models\Message::where('email_client', $conv->email_client)->latest()->first();
        return $last && $last->reponse_admin && $last->reponse_admin !== '';
    })->count();
@endphp

{{-- ===== BARRE DE STATS ===== --}}
<div style="display:flex;gap:10px;margin-bottom:14px;flex-wrap:wrap;">

    <div style="background:white;border:1px solid #e9ecef;border-radius:10px;padding:10px 16px;display:flex;align-items:center;gap:10px;flex:1;min-width:120px;">
        <div style="width:34px;height:34px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="16" height="16" fill="none" stroke="#1d4ed8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <div>
            <div style="font-size:18px;font-weight:700;color:#1f2937;line-height:1;">{{ $totalConv }}</div>
            <div style="font-size:11px;color:#6b7280;margin-top:1px;">Total</div>
        </div>
    </div>

    <div style="background:white;border:1px solid #e9ecef;border-radius:10px;padding:10px 16px;display:flex;align-items:center;gap:10px;flex:1;min-width:120px;">
        <div style="width:34px;height:34px;border-radius:8px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="16" height="16" fill="none" stroke="#b45309" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        </div>
        <div>
            <div style="font-size:18px;font-weight:700;color:#b45309;line-height:1;">{{ $nonRepondus }}</div>
            <div style="font-size:11px;color:#6b7280;margin-top:1px;">Sans réponse</div>
        </div>
    </div>

    <div style="background:white;border:1px solid #e9ecef;border-radius:10px;padding:10px 16px;display:flex;align-items:center;gap:10px;flex:1;min-width:120px;">
        <div style="width:34px;height:34px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="16" height="16" fill="none" stroke="#15803d" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div style="font-size:18px;font-weight:700;color:#15803d;line-height:1;">{{ $repondues }}</div>
            <div style="font-size:11px;color:#6b7280;margin-top:1px;">Répondues</div>
        </div>
    </div>

    <div style="background:white;border:1px solid #e9ecef;border-radius:10px;padding:10px 16px;display:flex;align-items:center;gap:10px;flex:1;min-width:120px;">
        <div style="width:34px;height:34px;border-radius:8px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="16" height="16" fill="none" stroke="#6b7280" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="21 8 21 21 3 21 3 8"/><rect stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x="1" y="3" width="22" height="5"/><line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="10" y1="12" x2="14" y2="12"/></svg>
        </div>
        <div>
            <div style="font-size:18px;font-weight:700;color:#4b5563;line-height:1;">{{ $archivees }}</div>
            <div style="font-size:11px;color:#6b7280;margin-top:1px;">Archivées</div>
        </div>
    </div>

</div>

<div class="whatsapp-container">
    <div class="wa-header">
        <div class="wa-title">
            Messages
            @if($nonRepondus > 0)
                <span class="wa-header-badge">{{ $nonRepondus }} non répondu{{ $nonRepondus > 1 ? 's' : '' }}</span>
            @endif
        </div>
        <div class="wa-count">{{ $totalConv }}</div>
    </div>

    <div class="wa-layout">
        <!-- ===== SIDEBAR GAUCHE ===== -->
        <div class="wa-sidebar" id="waSidebar">
            <div class="wa-tabs">
                <div class="wa-tab active" data-filter="all">
                    Active
                    @if($nonRepondus > 0)
                        <span class="wa-tab-badge">{{ $nonRepondus }}</span>
                    @endif
                </div>
                <div class="wa-tab" data-filter="archived">
                    Archivé
                    @if($archivees > 0)
                        <span class="wa-tab-badge wa-tab-badge-gray">{{ $archivees }}</span>
                    @endif
                </div>
            </div>
            <div class="wa-search">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input type="text" id="searchConv" placeholder="Rechercher une conversation...">
                <button id="clearSearch" class="wa-clear-search" style="display:none;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="searchResultsCount" class="wa-search-count" style="display:none;"></div>

            <div class="wa-conversations" id="waConversations">
                @if($conversations->count() > 0)
                    @foreach($conversations as $conv)
                        @php
                            $lastMsg     = \App\Models\Message::where('email_client', $conv->email_client)->latest()->first();
                            $isArchived  = $conv->closed ?? false;
                            $hasResponse = $lastMsg && $lastMsg->reponse_admin && $lastMsg->reponse_admin !== '';
                            $status      = $isArchived ? 'Archivé' : ($hasResponse ? 'Répondu' : 'Sans réponse');
                            $initials    = collect(explode(' ', $conv->nom_complet))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
                            $colors      = ['135deg,#f78ca2,#e91e8c','135deg,#84fab0,#08aeea','135deg,#a18cd1,#fbc2eb','135deg,#ffecd2,#fcb69f','135deg,#c2e9fb,#a1c4fd','135deg,#fddb92,#d1fdff'];
                            $color       = $colors[crc32($conv->email_client) % count($colors)];
                            $telephone   = 'Non renseigné';
                            if (!empty($lastMsg->telephone))  $telephone = $lastMsg->telephone;
                            elseif (!empty($lastMsg->phone))  $telephone = $lastMsg->phone;
                            elseif (!empty($lastMsg->tel))    $telephone = $lastMsg->tel;
                            elseif (!empty($lastMsg->numero)) $telephone = $lastMsg->numero;
                            elseif (!empty($conv->telephone)) $telephone = $conv->telephone;
                            elseif (!empty($conv->phone))     $telephone = $conv->phone;
                            // Conversation "non lue" = pas de réponse admin et pas archivée
                            $isUnread = !$hasResponse && !$isArchived;
                        @endphp
                        <div class="wa-conv-item {{ $isUnread ? 'is-unread' : '' }}"
                             data-email="{{ $conv->email_client }}"
                             data-name="{{ $conv->nom_complet }}"
                             data-initials="{{ $initials }}"
                             data-color="{{ $color }}"
                             data-phone="{{ $telephone }}"
                             data-archived="{{ $isArchived ? 'true' : 'false' }}"
                             data-has-response="{{ $hasResponse ? 'true' : 'false' }}"
                             data-unread="false">
                            <div class="wa-avatar" style="background: linear-gradient({{ $color }})">{{ $initials }}</div>
                            <div class="wa-conv-info">
                                <div class="wa-conv-name">{{ $conv->nom_complet }}</div>
                                <div class="wa-conv-msg" data-raw="{{ $lastMsg ? Str::limit($lastMsg->message, 38) : 'Aucun message' }}">
                                    {{ $lastMsg ? Str::limit($lastMsg->message, 38) : 'Aucun message' }}
                                </div>
                            </div>
                            <div class="wa-conv-meta">
                                <div class="wa-time">{{ $lastMsg ? $lastMsg->created_at->format('H:i') : '' }}</div>
                                <div class="wa-badge {{ $isArchived ? 'archived' : ($hasResponse ? 'replied' : 'unanswered') }}">
                                    {{ $status }}
                                </div>
                                @if($isUnread)
                                    <div class="wa-unread-dot"></div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="wa-empty-conversations">
                        <div class="wa-empty-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                            </svg>
                        </div>
                        <p>Aucune conversation pour le moment</p>
                        <span>Les messages des clients apparaîtront ici</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- ===== ZONE CHAT ===== -->
        <div class="wa-chat" id="waChat">
            <div class="wa-placeholder" id="waPlaceholder">
                <div class="wa-placeholder-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                </div>
                <p>Sélectionnez une conversation</p>
                @if($nonRepondus > 0)
                    <span class="wa-placeholder-hint">{{ $nonRepondus }} message{{ $nonRepondus > 1 ? 's' : '' }} en attente de réponse</span>
                @endif
            </div>

            <div id="waActiveChat" style="display:none;" class="wa-active">
                <div class="wa-chat-header">
                    <button id="waBackBtn" class="wa-back">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                    </button>
                    <div class="wa-contact">
                        <div class="wa-contact-avatar" id="waHeaderAvatar"></div>
                        <div>
                            <div class="wa-contact-name" id="waContactName">Chargement...</div>
                            <div class="wa-contact-status">
                                <span class="wa-status-dot"></span> <span id="waContactEmail">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="wa-header-actions">
                        <button id="waNotifBtn" class="wa-icon-btn wa-notif-btn" title="Activer les notifications">
                            <svg id="waNotifIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                        </button>
                        <button id="waArchiveBtn" class="wa-icon-btn" title="Archiver / Désarchiver">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="21 8 21 21 3 21 3 8" />
                                <rect x="1" y="3" width="22" height="5" />
                                <line x1="10" y1="12" x2="14" y2="12" />
                            </svg>
                        </button>
                        <button id="waInfoBtn" class="wa-icon-btn" title="Informations client">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="wa-messages" id="waMessages"></div>

                <div class="wa-input" id="waInput">
                    <textarea id="waMessageInput" rows="1" placeholder="Entrez votre message..."></textarea>
                    <button id="waSendBtn" class="wa-send">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="white">
                            <path d="M22 2 11 13M22 2 15 22l-4-9-9-4 20-7z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PANEL INFOS CLIENT -->
<div id="waInfoPanel" class="wa-info-panel">
    <div class="wa-info-panel-head">
        <span>Informations client</span>
        <button id="waCloseInfo">✕</button>
    </div>
    <div class="wa-info-panel-body">
        <div class="wa-info-avatar-large" id="waInfoAvatarLarge"></div>
        <div class="wa-info-name-block">
            <div id="waInfoName" class="wa-info-fullname">-</div>
            <div class="wa-info-sub">Client</div>
        </div>
        <div class="wa-info-rows">
            <div class="wa-info-row">
                <div class="wa-info-dot"></div>
                <div>
                    <div class="wa-info-label">Email</div>
                    <div class="wa-info-val" id="waInfoEmail">-</div>
                </div>
            </div>
            <div class="wa-info-row">
                <div class="wa-info-dot"></div>
                <div>
                    <div class="wa-info-label">Téléphone</div>
                    <div class="wa-info-val" id="waInfoPhone">-</div>
                </div>
            </div>
            <div class="wa-info-row" style="border:none">
                <div class="wa-info-dot"></div>
                <div>
                    <div class="wa-info-label">Statut</div>
                    <div id="waInfoStatus" class="wa-badge" style="margin-top:4px">-</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="waInfoOverlay" class="wa-info-overlay"></div>

<!-- TOAST NOTIFICATION -->
<div id="waToast" class="wa-toast">
    <div class="wa-toast-avatar" id="waToastAvatar"></div>
    <div class="wa-toast-body">
        <div class="wa-toast-name" id="waToastName"></div>
        <div class="wa-toast-msg" id="waToastMsg"></div>
    </div>
    <button class="wa-toast-close" onclick="hideToast()">✕</button>
</div>

<style>
    /* ===== CONTAINER ===== */
    .whatsapp-container {
        height: calc(100vh - 230px);
        min-height: 480px;
        display: flex;
        flex-direction: column;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    /* ===== HEADER ===== */
    .wa-header {
        padding: 12px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }
    .wa-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .wa-header-badge {
        background: #fef3c7;
        color: #b45309;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
    }
    .wa-count {
        background: #e5e7eb;
        color: #6b7280;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    /* ===== LAYOUT ===== */
    .wa-layout { display: flex; flex: 1; overflow: hidden; position: relative; }

    /* ===== SIDEBAR ===== */
    .wa-sidebar {
        width: 300px;
        background: #fff;
        border-right: 1px solid #e9ecef;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        flex-shrink: 0;
    }

    /* ===== TABS ===== */
    .wa-tabs {
        display: flex;
        gap: 4px;
        margin: 10px 12px 4px;
        background: #f3f4f6;
        border-radius: 20px;
        padding: 3px;
    }
    .wa-tab {
        flex: 1;
        text-align: center;
        padding: 5px 4px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    .wa-tab.active { background: white; color: #1a3c34; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .wa-tab-badge {
        background: #ef4444;
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 20px;
        line-height: 1.4;
    }
    .wa-tab-badge-gray { background: #9ca3af; }

    /* ===== SEARCH ===== */
    .wa-search {
        margin: 6px 10px;
        background: #f3f4f6;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        color: #9ca3af;
        transition: box-shadow 0.2s;
    }
    .wa-search:focus-within {
        box-shadow: 0 0 0 2px rgba(26,60,52,0.15);
        background: white;
    }
    .wa-search input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 13px;
        outline: none;
        color: #1f2937;
    }
    .wa-search input::placeholder { color: #9ca3af; }
    .wa-clear-search {
        background: none; border: none; cursor: pointer;
        color: #9ca3af; display: flex; align-items: center;
        justify-content: center; padding: 2px; border-radius: 50%;
    }
    .wa-clear-search:hover { color: #1a3c34; }
    .wa-search-count {
        font-size: 11px; color: #1a3c34;
        padding: 2px 12px 4px; font-weight: 500;
    }

    /* ===== LISTE CONVERSATIONS ===== */
    .wa-conversations { flex: 1; overflow-y: auto; padding: 4px 0; }

    .wa-empty-conversations {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; text-align: center;
        padding: 60px 20px; color: #9ca3af;
    }
    .wa-empty-icon { opacity: 0.4; margin-bottom: 12px; }

    .wa-conv-item {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px; cursor: pointer;
        border-radius: 10px; margin: 2px 6px;
        transition: background 0.15s;
        border: 1px solid transparent; position: relative;
    }
    .wa-conv-item:hover  { background: #f3f4f6; }
    .wa-conv-item.active { background: #ecfdf5; border-color: rgba(26,60,52,0.12); }

    /* Non répondu = surligné ambre */
    .wa-conv-item.is-unread {
        background: #fffbeb;
        border-color: rgba(245,158,11,0.25);
    }
    .wa-conv-item.is-unread .wa-conv-name { font-weight: 700; color: #1f2937; }
    .wa-conv-item.is-unread .wa-conv-msg  { color: #6b7280; }

    /* Non lu dynamique (JS — nouveau message temps réel) */
    .wa-conv-item.has-unread {
        background: #f0fdf4;
        border-color: rgba(26,60,52,0.2);
    }
    .wa-conv-item.has-unread .wa-conv-name { font-weight: 700; color: #1a3c34; }

    /* Dot pulsant statique (Blade — sans réponse) */
    .wa-unread-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #f59e0b; flex-shrink: 0;
        animation: pulse-dot 1.8s ease-in-out infinite;
    }

    /* Dot pulsant dynamique (JS — nouveau message) */
    .wa-new-badge {
        position: absolute; top: 6px; left: 6px;
        width: 9px; height: 9px; border-radius: 50%;
        background: #1a3c34; border: 2px solid white;
        animation: pulse-dot 1.8s ease-in-out infinite;
    }

    @keyframes pulse-dot {
        0%,100% { transform: scale(1); opacity: 1; }
        50%      { transform: scale(1.45); opacity: 0.6; }
    }

    .wa-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 14px; font-weight: 600; flex-shrink: 0;
    }
    .wa-conv-info { flex: 1; min-width: 0; }
    .wa-conv-name { font-size: 13px; font-weight: 500; color: #1f2937; }
    .wa-conv-msg  { font-size: 12px; color: #9ca3af; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px; }
    .wa-conv-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
    .wa-time      { font-size: 10px; color: #9ca3af; }

    /* ===== BADGES ===== */
    .wa-badge           { font-size: 9px; padding: 2px 7px; border-radius: 10px; font-weight: 600; white-space: nowrap; }
    .wa-badge.unanswered { background: #fef3c7; color: #b45309; }
    .wa-badge.replied    { background: #dcfce7; color: #15803d; }
    .wa-badge.archived   { background: #f3f4f6; color: #6b7280; }

    /* Surbrillance recherche */
    mark.wa-highlight {
        background: rgba(26,60,52,0.15);
        color: #1a3c34; border-radius: 3px;
        padding: 0 2px; font-weight: 600;
    }

    /* ===== ZONE CHAT ===== */
    .wa-chat {
        flex: 1; display: flex; flex-direction: column;
        background: #efeae2;
        background-image: radial-gradient(circle at 10px 10px, rgba(0,0,0,0.02) 1px, transparent 1px);
        background-size: 20px 20px;
        position: relative;
    }
    .wa-placeholder {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        color: #9ca3af; gap: 8px;
    }
    .wa-placeholder-icon { opacity: 0.3; }
    .wa-placeholder p    { font-size: 14px; font-weight: 500; color: #6b7280; }
    .wa-placeholder-hint { font-size: 12px; color: #b45309; background: #fef3c7; padding: 3px 10px; border-radius: 20px; }

    .wa-active { display: flex; flex-direction: column; height: 100%; }

    /* ===== CHAT HEADER ===== */
    .wa-chat-header {
        background: #fff; padding: 10px 16px;
        display: flex; align-items: center; gap: 10px;
        border-bottom: 1px solid #e9ecef; flex-shrink: 0;
    }
    .wa-back {
        display: none; background: #f3f4f6; border: none;
        width: 34px; height: 34px; border-radius: 50%;
        cursor: pointer; color: #1f2937;
        align-items: center; justify-content: center; flex-shrink: 0;
    }
    .wa-back:hover { background: #e5e7eb; }
    .wa-contact { display: flex; align-items: center; gap: 10px; flex: 1; }
    .wa-contact-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 14px; font-weight: 500; flex-shrink: 0;
    }
    .wa-contact-name   { font-size: 14px; font-weight: 600; color: #1f2937; }
    .wa-contact-status { font-size: 11px; color: #6b7280; display: flex; align-items: center; gap: 4px; }
    .wa-status-dot     { width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block; }
    .wa-header-actions { display: flex; gap: 6px; }

    .wa-icon-btn {
        width: 34px; height: 34px; border-radius: 8px;
        background: #f3f4f6; border: none; cursor: pointer;
        color: #6b7280; display: flex; align-items: center;
        justify-content: center; transition: all 0.15s;
    }
    .wa-icon-btn:hover   { background: #e5e7eb; color: #1a3c34; }
    .wa-notif-btn.notif-on { background: #dcfce7; color: #15803d; }

    /* ===== MESSAGES ===== */
    .wa-messages {
        flex: 1; overflow-y: auto; padding: 16px;
        display: flex; flex-direction: column; gap: 10px;
    }
    .msg-left  { display: flex; justify-content: flex-start; }
    .msg-right { display: flex; justify-content: flex-end; }
    .bubble-left {
        background: white; border-radius: 18px 18px 18px 4px;
        padding: 9px 13px; max-width: 65%;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
    }
    .bubble-right {
        background: #1a3c34; border-radius: 18px 18px 4px 18px;
        padding: 9px 13px; max-width: 65%;
        box-shadow: 0 1px 3px rgba(26,60,52,0.2);
    }
    .msg-name       { font-size: 10px; font-weight: 600; margin-bottom: 3px; color: #1a3c34; }
    .msg-name-right { font-size: 10px; font-weight: 600; margin-bottom: 3px; color: rgba(255,255,255,0.75); }
    .msg-text       { font-size: 13px; color: #1f2937; line-height: 1.45; }
    .msg-text-right { font-size: 13px; color: white; line-height: 1.45; }
    .msg-time       { font-size: 10px; margin-top: 4px; text-align: right; color: #9ca3af; }
    .msg-time-right { font-size: 10px; margin-top: 4px; text-align: right; color: rgba(255,255,255,0.6); }

    /* ===== INPUT ===== */
    .wa-input {
        background: #fff; padding: 10px 14px;
        display: flex; gap: 10px; align-items: center;
        border-top: 1px solid #e9ecef; flex-shrink: 0;
    }
    .wa-input textarea {
        flex: 1; border: 1px solid #e9ecef;
        background: #f9fafb; padding: 9px 14px;
        border-radius: 20px; font-size: 13px; resize: none;
        outline: none; color: #1f2937; max-height: 120px;
        transition: border-color 0.2s;
    }
    .wa-input textarea:focus { border-color: #1a3c34; background: white; }
    .wa-send {
        width: 40px; height: 40px; border-radius: 50%;
        background: #1a3c34; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: transform 0.15s, opacity 0.15s; color: white;
    }
    .wa-send:hover  { opacity: 0.88; }
    .wa-send:active { transform: scale(0.92); }

    /* ===== INFO PANEL ===== */
    .wa-info-panel {
        position: fixed; top: 0; right: 0; height: 100%; width: 280px;
        background: white; transform: translateX(100%);
        transition: transform 0.25s cubic-bezier(0.4,0,0.2,1);
        z-index: 1000; display: flex; flex-direction: column;
        box-shadow: -4px 0 24px rgba(0,0,0,0.08);
    }
    .wa-info-panel.open { transform: translateX(0); }
    .wa-info-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.15); z-index: 999;
    }
    .wa-info-overlay.open { display: block; }
    .wa-info-panel-head {
        background: #1a3c34; padding: 16px 18px;
        display: flex; justify-content: space-between;
        align-items: center; color: white; font-size: 14px; font-weight: 500;
    }
    .wa-info-panel-head button {
        background: rgba(255,255,255,0.15); border: none;
        color: white; font-size: 16px; cursor: pointer;
        width: 26px; height: 26px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .wa-info-panel-body {
        padding: 20px; display: flex; flex-direction: column;
        align-items: center; overflow-y: auto;
    }
    .wa-info-avatar-large {
        width: 68px; height: 68px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 22px; font-weight: 500; margin-bottom: 12px;
    }
    .wa-info-name-block { text-align: center; margin-bottom: 16px; }
    .wa-info-fullname   { font-size: 16px; font-weight: 600; color: #1f2937; }
    .wa-info-sub        { font-size: 12px; color: #9ca3af; margin-top: 2px; }
    .wa-info-rows       { width: 100%; }
    .wa-info-row        { display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f0f2f5; }
    .wa-info-dot        { width: 8px; height: 8px; border-radius: 50%; background: #1a3c34; flex-shrink: 0; margin-top: 4px; }
    .wa-info-label      { font-size: 11px; color: #9ca3af; margin-bottom: 2px; }
    .wa-info-val        { font-size: 13px; color: #1f2937; font-weight: 500; word-break: break-all; }

    /* ===== TOAST ===== */
    .wa-toast {
        position: fixed; bottom: 24px; right: 24px;
        background: #1a3c34; border-radius: 14px;
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px; z-index: 2000; max-width: 320px;
        transform: translateY(120%) scale(0.95); opacity: 0;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;
        pointer-events: none; color: white;
        box-shadow: 0 4px 16px rgba(26,60,52,0.3);
    }
    .wa-toast.show { transform: translateY(0) scale(1); opacity: 1; pointer-events: all; }
    .wa-toast-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 600; flex-shrink: 0;
    }
    .wa-toast-body  { flex: 1; min-width: 0; }
    .wa-toast-name  { font-size: 13px; font-weight: 600; }
    .wa-toast-msg   { font-size: 12px; opacity: 0.8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
    .wa-toast-close { background: rgba(255,255,255,0.15); border: none; cursor: pointer; color: white; font-size: 13px; padding: 3px 7px; border-radius: 6px; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .wa-sidebar {
            position: absolute; width: 100%; height: 100%;
            z-index: 10; transform: translateX(0);
            transition: transform 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        .wa-sidebar.hide-mobile { transform: translateX(-100%); }
        .wa-back { display: flex !important; }
        .wa-info-panel { width: 100%; }
        .wa-toast { bottom: 12px; right: 12px; left: 12px; max-width: none; }
    }
    @media (max-width: 480px) {
        .wa-header { padding: 10px 12px; }
        .wa-messages { padding: 12px 10px; }
        .bubble-left, .bubble-right { max-width: 80%; }
    }

    /* ===== SCROLLBAR ===== */
    .wa-conversations::-webkit-scrollbar,
    .wa-messages::-webkit-scrollbar { width: 4px; }
    .wa-conversations::-webkit-scrollbar-thumb,
    .wa-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
</style>

<script>
    /* ============================================================
       ÉTAT GLOBAL
       ============================================================ */
    let currentEmail    = '';
    let currentArchived = false;
    let currentInitials = '';
    let currentColor    = '';
    let currentPhone    = '';
    let archivedSet     = new Set();
    let unreadSet       = new Set();
    let isMobile        = window.innerWidth <= 768;
    let searchTerm      = '';
    let notifEnabled    = false;
    let soundEnabled    = true;
    let toastTimer      = null;
    let audioCtx        = null;

    window.addEventListener('resize', () => { isMobile = window.innerWidth <= 768; });

    /* ============================================================
       AUDIO
       ============================================================ */
    function initAudio() {
        if (!audioCtx) {
            try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch(e) {}
        }
    }
    function playBeep() {
        if (!soundEnabled) return;
        try {
            initAudio();
            if (!audioCtx) return;
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const now = audioCtx.currentTime;
            const o1 = audioCtx.createOscillator(), g1 = audioCtx.createGain();
            o1.connect(g1); g1.connect(audioCtx.destination);
            o1.type = 'sine'; o1.frequency.value = 880;
            g1.gain.setValueAtTime(0.22, now);
            g1.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
            o1.start(now); o1.stop(now + 0.4);
            const o2 = audioCtx.createOscillator(), g2 = audioCtx.createGain();
            o2.connect(g2); g2.connect(audioCtx.destination);
            o2.type = 'sine'; o2.frequency.value = 1100;
            g2.gain.setValueAtTime(0.14, now + 0.12);
            g2.gain.exponentialRampToValueAtTime(0.001, now + 0.52);
            o2.start(now + 0.12); o2.stop(now + 0.52);
        } catch(e) {}
    }
    document.addEventListener('click', initAudio, { once: true });

    /* ============================================================
       NOTIFICATIONS NAVIGATEUR
       ============================================================ */
    function requestNotifPermission() {
        if (!('Notification' in window)) return;
        Notification.requestPermission().then(perm => {
            notifEnabled = perm === 'granted';
            updateNotifBtn();
        });
    }
    function updateNotifBtn() {
        const btn = document.getElementById('waNotifBtn');
        if (!btn) return;
        btn.classList.toggle('notif-on', notifEnabled);
        btn.title = notifEnabled
            ? (soundEnabled ? 'Notifications activées' : 'Son coupé')
            : 'Activer les notifications';
    }
    function sendBrowserNotif(name, message) {
        if (!notifEnabled || document.hasFocus()) return;
        try { new Notification(`💬 ${name}`, { body: message, tag: 'wa-msg', renotify: true }); } catch(e) {}
    }

    /* ============================================================
       TOAST IN-APP
       ============================================================ */
    function showToast(name, message, initials, color) {
        const toast = document.getElementById('waToast');
        document.getElementById('waToastName').textContent = name;
        document.getElementById('waToastMsg').textContent  = message;
        const av = document.getElementById('waToastAvatar');
        av.textContent      = initials;
        av.style.background = `linear-gradient(${color})`;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(hideToast, 5000);
    }
    function hideToast() { document.getElementById('waToast').classList.remove('show'); }

    /* ============================================================
       RECHERCHE AVEC SURBRILLANCE
       ============================================================ */
    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
    function escapeRegex(str) { return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }
    function highlightText(text, term) {
        if (!term || term.trim().length < 1) return escapeHtml(text);
        return escapeHtml(text).replace(
            new RegExp(`(${escapeRegex(term.trim())})`, 'gi'),
            '<mark class="wa-highlight">$1</mark>'
        );
    }

    function applySearch(term) {
        searchTerm = term;
        const items    = document.querySelectorAll('.wa-conv-item');
        const clearBtn = document.getElementById('clearSearch');
        const countEl  = document.getElementById('searchResultsCount');
        if (clearBtn) clearBtn.style.display = term ? 'flex' : 'none';

        let matchCount = 0;
        items.forEach(item => {
            const name   = item.dataset.name || '';
            const rawMsg = item.querySelector('.wa-conv-msg')?.dataset.raw
                        || item.querySelector('.wa-conv-msg')?.textContent || '';
            const matches = !term || name.toLowerCase().includes(term.toLowerCase());

            if (matches) {
                item.style.display = '';
                matchCount++;
                const nameEl = item.querySelector('.wa-conv-name');
                const msgEl  = item.querySelector('.wa-conv-msg');
                if (nameEl) nameEl.innerHTML = highlightText(name, term);
                if (msgEl)  msgEl.innerHTML  = highlightText(rawMsg, term);
            } else {
                item.style.display = 'none';
            }
        });

        if (countEl) {
            countEl.style.display = term ? 'block' : 'none';
            countEl.textContent   = matchCount
                ? `${matchCount} résultat${matchCount > 1 ? 's' : ''} trouvé${matchCount > 1 ? 's' : ''}`
                : 'Aucun résultat';
        }
    }

    /* ============================================================
       INDICATEUR NOUVEAU MESSAGE (DYNAMIQUE)
       ============================================================ */
    function markAsUnread(email) {
        unreadSet.add(email);
        const item = document.querySelector(`.wa-conv-item[data-email="${CSS.escape(email)}"]`);
        if (!item) return;
        item.classList.add('has-unread');
        if (!item.querySelector('.wa-new-badge')) {
            const dot = document.createElement('div');
            dot.className = 'wa-new-badge';
            item.prepend(dot);
        }
    }
    function clearUnread(email) {
        unreadSet.delete(email);
        const item = document.querySelector(`.wa-conv-item[data-email="${CSS.escape(email)}"]`);
        if (!item) return;
        item.classList.remove('has-unread');
        item.querySelector('.wa-new-badge')?.remove();
    }

    /* ============================================================
       INFO PANEL
       ============================================================ */
    const infoPanel   = document.getElementById('waInfoPanel');
    const infoOverlay = document.getElementById('waInfoOverlay');
    document.getElementById('waInfoBtn').onclick   = () => { infoPanel.classList.add('open'); infoOverlay.classList.add('open'); };
    document.getElementById('waCloseInfo').onclick = closeInfo;
    infoOverlay.onclick = closeInfo;
    function closeInfo() { infoPanel.classList.remove('open'); infoOverlay.classList.remove('open'); }

    /* ============================================================
       BOUTON NOTIF
       ============================================================ */
    document.getElementById('waNotifBtn').onclick = () => {
        if (notifEnabled) {
            soundEnabled = !soundEnabled;
            updateNotifBtn();
        } else {
            requestNotifPermission();
            soundEnabled = true;
        }
    };

    /* ============================================================
       ARCHIVE
       ============================================================ */
    document.getElementById('waArchiveBtn').onclick = () => {
        if (!currentEmail) return;
        archiveConv(currentEmail, !currentArchived);
    };
    function archiveConv(email, archive) {
        fetch('/admin/messages/toggle-close', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ email_client: email, closed: archive })
        }).then(r => r.json()).then(() => {
            if (archive) archivedSet.add(email); else archivedSet.delete(email);
            refreshList();
            if (currentEmail === email) {
                currentArchived = archive;
                document.getElementById('waInput').style.display = archive ? 'none' : 'flex';
                updateArchiveBtn();
            }
        });
    }
    function updateArchiveBtn() {
        const btn = document.getElementById('waArchiveBtn');
        if (!btn) return;
        btn.title            = currentArchived ? 'Désarchiver' : 'Archiver';
        btn.style.background = currentArchived ? '#fef2f2' : '#f3f4f6';
        btn.style.color      = currentArchived ? '#dc2626'  : '#6b7280';
    }

    /* ============================================================
       CHARGER CONVERSATION
       ============================================================ */
    async function loadConv(email) {
        try {
            const res      = await fetch(`/admin/messages/conversation/${encodeURIComponent(email)}`);
            const messages = await res.json();
            const container = document.getElementById('waMessages');

            if (!messages.length) {
                container.innerHTML = '<div style="text-align:center;color:#aaa;padding:40px;font-size:13px">Aucun message</div>';
                return;
            }

            let html = '';
            for (let msg of messages) {
                const phone = msg.telephone || msg.phone || msg.tel || msg.numero || '';
                if (phone && phone !== 'Non renseigné') currentPhone = phone;

                html += `<div class="msg-left"><div class="bubble-left">
                    <div class="msg-name">${escapeHtml(msg.nom_complet)}</div>
                    <div class="msg-text">${escapeHtml(msg.message)}</div>
                    <div class="msg-time">${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                </div></div>`;

                if (msg.reponse_admin && msg.reponse_admin.trim()) {
                    html += `<div class="msg-right"><div class="bubble-right">
                        <div class="msg-name-right">Vous</div>
                        <div class="msg-text-right">${escapeHtml(msg.reponse_admin)}</div>
                        <div class="msg-time-right">${new Date(msg.updated_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                    </div></div>`;
                }
            }
            container.innerHTML = html;
            container.scrollTop = container.scrollHeight;
            if (currentPhone) document.getElementById('waInfoPhone').innerText = currentPhone;
        } catch(e) { console.error('loadConv:', e); }
    }

    /* ============================================================
       ENVOYER MESSAGE
       ============================================================ */
    async function sendMsg() {
        if (archivedSet.has(currentEmail)) return;
        const input = document.getElementById('waMessageInput');
        const msg   = input.value.trim();
        if (!msg) return;
        input.value = '';
        input.style.height = 'auto';
        try {
            const res = await fetch('/admin/messages/reply', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ email_client: currentEmail, reponse_admin: msg })
            });
            if (res.ok) { await loadConv(currentEmail); refreshList(); }
        } catch(e) { console.error('sendMsg:', e); }
    }

    /* ============================================================
       RAFRAÎCHIR LA LISTE
       ============================================================ */
    function refreshList() {
        fetch(window.location.href).then(r => r.text()).then(html => {
            const doc     = new DOMParser().parseFromString(html, 'text/html');
            const newList = doc.getElementById('waConversations');
            const oldList = document.getElementById('waConversations');
            if (!newList || !oldList) return;
            oldList.innerHTML = newList.innerHTML;
            attachEvents();
            document.querySelectorAll('.wa-conv-item').forEach(el => {
                const email = el.dataset.email;
                if (el.dataset.archived === 'true') archivedSet.add(email);
                else archivedSet.delete(email);
                if (unreadSet.has(email)) markAsUnread(email);
            });
            if (searchTerm) applySearch(searchTerm);
        });
    }

    /* ============================================================
       ATTACHER EVENTS
       ============================================================ */
    function attachEvents() {
        document.querySelectorAll('.wa-conv-item').forEach(el => {
            const msgEl = el.querySelector('.wa-conv-msg');
            if (msgEl && !msgEl.dataset.raw) msgEl.dataset.raw = msgEl.textContent.trim();

            const newEl = el.cloneNode(true);
            el.parentNode.replaceChild(newEl, el);

            newEl.onclick = () => {
                document.querySelectorAll('.wa-conv-item').forEach(i => i.classList.remove('active'));
                newEl.classList.add('active');

                currentEmail    = newEl.dataset.email;
                currentInitials = newEl.dataset.initials;
                currentColor    = newEl.dataset.color;
                currentPhone    = newEl.dataset.phone;
                currentArchived = archivedSet.has(currentEmail);

                clearUnread(currentEmail);

                // Header
                const headerAv = document.getElementById('waHeaderAvatar');
                headerAv.textContent      = currentInitials;
                headerAv.style.background = `linear-gradient(${currentColor})`;
                document.getElementById('waContactName').innerText  = newEl.dataset.name;
                document.getElementById('waContactEmail').innerText = currentEmail;

                // Panel info
                const infoAv = document.getElementById('waInfoAvatarLarge');
                infoAv.textContent      = currentInitials;
                infoAv.style.background = `linear-gradient(${currentColor})`;
                document.getElementById('waInfoName').innerText  = newEl.dataset.name;
                document.getElementById('waInfoEmail').innerText = currentEmail;
                document.getElementById('waInfoPhone').innerText = currentPhone || 'Non renseigné';

                const isArchived  = archivedSet.has(currentEmail);
                const hasResponse = newEl.dataset.hasResponse === 'true';
                const statusClass = isArchived ? 'archived' : (hasResponse ? 'replied' : 'unanswered');
                const statusText  = isArchived ? 'Archivé'  : (hasResponse ? 'Répondu'  : 'Sans réponse');
                const statusEl    = document.getElementById('waInfoStatus');
                statusEl.className   = `wa-badge ${statusClass}`;
                statusEl.textContent = statusText;

                document.getElementById('waPlaceholder').style.display  = 'none';
                document.getElementById('waActiveChat').style.display    = 'flex';
                document.getElementById('waInput').style.display         = currentArchived ? 'none' : 'flex';
                updateArchiveBtn();
                loadConv(currentEmail);

                if (isMobile) document.getElementById('waSidebar').classList.add('hide-mobile');
            };
        });

        // Tabs
        document.querySelectorAll('.wa-tab').forEach(tab => {
            tab.onclick = () => {
                document.querySelectorAll('.wa-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const filter = tab.dataset.filter;
                document.querySelectorAll('.wa-conv-item').forEach(item => {
                    const isArc = item.dataset.archived === 'true';
                    item.style.display = (filter === 'all' && !isArc) || (filter === 'archived' && isArc) ? '' : 'none';
                });
            };
        });

        // Search
        const searchInput = document.getElementById('searchConv');
        if (searchInput) searchInput.oninput = () => applySearch(searchInput.value);
        const clearBtn = document.getElementById('clearSearch');
        if (clearBtn) clearBtn.onclick = () => { searchInput.value = ''; applySearch(''); };
    }

    /* ============================================================
       BOUTON RETOUR MOBILE + ENVOI
       ============================================================ */
    document.getElementById('waBackBtn').onclick = () =>
        document.getElementById('waSidebar').classList.remove('hide-mobile');

    document.getElementById('waSendBtn').onclick = sendMsg;
    document.getElementById('waMessageInput').addEventListener('keypress', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); }
    });
    document.getElementById('waMessageInput').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    /* ============================================================
       REALTIME
       ============================================================ */
    function handleNewMessage(emailClient, senderName, messageText) {
        if (emailClient !== currentEmail) {
            playBeep();
            markAsUnread(emailClient);
            const item     = document.querySelector(`.wa-conv-item[data-email="${CSS.escape(emailClient)}"]`);
            const initials = item?.dataset.initials || '??';
            const color    = item?.dataset.color    || '135deg,#a18cd1,#fbc2eb';
            const name     = senderName || item?.dataset.name || emailClient;
            showToast(name, messageText || 'Nouveau message', initials, color);
            sendBrowserNotif(name, messageText || 'Nouveau message');
        }
        refreshList();
        if (emailClient === currentEmail && !archivedSet.has(emailClient)) loadConv(emailClient);
    }

    function initWs() {
        if (window.Echo) {
            window.Echo.channel('new-messages').listen('NewMessageReceived', e => {
                handleNewMessage(e.email_client, e.nom_complet || e.name, e.message);
            });
        } else {
            setTimeout(initWs, 1000);
        }
    }

    /* ============================================================
       INIT
       ============================================================ */
    attachEvents();
    initWs();
    setInterval(refreshList, 15000);
</script>
@endsection