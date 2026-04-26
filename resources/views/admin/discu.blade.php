@extends('layouts.admin')

@section('page-title', 'Discussions')
@section('page-subtitle', 'Conversations clients en temps réel')

@section('content')

@php
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

<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

{{-- ═══════════════════════════════════════════
     STATS RAPIDES
═══════════════════════════════════════════ --}}
<div class="dc-stats">
    <div class="dc-stat">
        <div class="dc-stat-icon" style="background:rgba(37,99,168,.1);color:#2563a8">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <div>
            <div class="dc-stat-num">{{ $totalConv }}</div>
            <div class="dc-stat-lbl">Conversations</div>
        </div>
    </div>

    <div class="dc-stat {{ $nonRepondus > 0 ? 'urgent' : '' }}">
        <div class="dc-stat-icon" style="background:rgba(232,114,42,.1);color:#e8722a">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        </div>
        <div>
            <div class="dc-stat-num" style="{{ $nonRepondus > 0 ? 'color:#e8722a' : '' }}">{{ $nonRepondus }}</div>
            <div class="dc-stat-lbl">Sans réponse</div>
        </div>
        @if($nonRepondus > 0)<div class="dc-stat-pulse"></div>@endif
    </div>

    <div class="dc-stat">
        <div class="dc-stat-icon" style="background:rgba(45,156,79,.1);color:#2d9c4f">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="dc-stat-num" style="color:#2d9c4f">{{ $repondues }}</div>
            <div class="dc-stat-lbl">Répondues</div>
        </div>
    </div>

    <div class="dc-stat">
        <div class="dc-stat-icon" style="background:var(--bg-surface-2,#f8fafc);color:var(--text-muted,#94a3b8)">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="21 8 21 21 3 21 3 8"/><rect stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x="1" y="3" width="22" height="5"/><line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="10" y1="12" x2="14" y2="12"/></svg>
        </div>
        <div>
            <div class="dc-stat-num">{{ $archivees }}</div>
            <div class="dc-stat-lbl">Archivées</div>
        </div>
    </div>

    {{-- Indicateur connexion temps réel --}}
    <div class="dc-realtime-badge" id="realtimeBadge">
        <div class="dc-rt-dot"></div>
        <span>Temps réel</span>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MESSENGER
═══════════════════════════════════════════ --}}
<div class="dc-messenger" id="dcMessenger">

    {{-- ── SIDEBAR ── --}}
    <div class="dc-sidebar" id="dcSidebar">

        {{-- Header sidebar --}}
        <div class="dc-sb-head">
            <div class="dc-sb-title">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Messages
                @if($nonRepondus > 0)
                    <span class="dc-urgent-chip">{{ $nonRepondus }}</span>
                @endif
            </div>
        </div>

        {{-- Tabs --}}
        <div class="dc-tabs">
            <button class="dc-tab active" data-filter="all">
                Actives
                @if($nonRepondus > 0)<span class="dc-tab-badge">{{ $nonRepondus }}</span>@endif
            </button>
            <button class="dc-tab" data-filter="archived">
                Archivées
                @if($archivees > 0)<span class="dc-tab-badge muted">{{ $archivees }}</span>@endif
            </button>
        </div>

        {{-- Search --}}
        <div class="dc-search-wrap">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" id="convSearch" placeholder="Rechercher…" maxlength="80" autocomplete="off" spellcheck="false">
            <button id="convSearchClear" style="display:none" class="dc-search-clear" aria-label="Effacer">
                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Liste conversations --}}
        <div class="dc-conv-list" id="waConversations">
            @if($conversations->count() > 0)
                @foreach($conversations as $conv)
                    @php
                        $lastMsg     = \App\Models\Message::where('email_client', $conv->email_client)->latest()->first();
                        $isArchived  = $conv->closed ?? false;
                        $hasResponse = $lastMsg && $lastMsg->reponse_admin && $lastMsg->reponse_admin !== '';
                        $initials    = collect(explode(' ', $conv->nom_complet))->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->join('');
                        $colors = [['#2563a8','#3b82c4'],['#d63384','#e8722a'],['#2d9c4f','#1a8fa0'],['#e8722a','#d63384'],['#1a8fa0','#2563a8'],['#7c3aed','#d63384']];
                        $cp = $colors[crc32($conv->email_client) % count($colors)];
                        $phone = 'Non renseigné';
                        if (!empty($lastMsg->telephone)) $phone = $lastMsg->telephone;
                        elseif (!empty($lastMsg->phone)) $phone = $lastMsg->phone;
                        elseif (!empty($conv->telephone)) $phone = $conv->telephone;
                        $isUnread = !$hasResponse && !$isArchived;
                    @endphp
                    <div class="dc-conv-item {{ $isUnread ? 'is-unread' : '' }}"
                         data-email="{{ e($conv->email_client) }}"
                         data-name="{{ e($conv->nom_complet) }}"
                         data-initials="{{ $initials }}"
                         data-color1="{{ $cp[0] }}"
                         data-color2="{{ $cp[1] }}"
                         data-phone="{{ e($phone) }}"
                         data-archived="{{ $isArchived ? 'true' : 'false' }}"
                         data-has-response="{{ $hasResponse ? 'true' : 'false' }}"
                         data-raw-preview="{{ $lastMsg ? Str::limit(strip_tags($lastMsg->message), 50) : '' }}">

                        <div class="dc-conv-av" style="background:linear-gradient(135deg,{{ $cp[0] }},{{ $cp[1] }})">
                            {{ $initials }}
                        </div>

                        <div class="dc-conv-body">
                            <div class="dc-conv-row1">
                                <span class="dc-conv-name">{{ e($conv->nom_complet) }}</span>
                                <span class="dc-conv-time">{{ $lastMsg ? $lastMsg->created_at->format('H:i') : '' }}</span>
                            </div>
                            <div class="dc-conv-preview">
                                {{ $lastMsg ? Str::limit(strip_tags($lastMsg->message), 45) : 'Aucun message' }}
                            </div>
                            <div class="dc-conv-row3">
                                <span class="dc-status-chip {{ $isArchived ? 'archived' : ($hasResponse ? 'replied' : 'pending') }}">
                                    @if(!$isArchived && !$hasResponse)
                                        <span class="dc-chip-dot"></span>
                                    @endif
                                    {{ $isArchived ? 'Archivé' : ($hasResponse ? 'Répondu' : 'En attente') }}
                                </span>
                                @if($isUnread)<div class="dc-unread-dot"></div>@endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="dc-empty-conv">
                    <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <p>Aucune conversation</p>
                    <span>Les messages clients apparaîtront ici</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── CHAT ZONE ── --}}
    <div class="dc-chat-zone" id="dcChat">

        {{-- Placeholder --}}
        <div class="dc-placeholder" id="dcPlaceholder">
            <div class="dc-placeholder-visual">
                <div class="dc-ph-rings">
                    <div class="dc-ph-ring r1"></div>
                    <div class="dc-ph-ring r2"></div>
                    <div class="dc-ph-ring r3"></div>
                </div>
                <div class="dc-ph-icon">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
            </div>
            <p class="dc-ph-title">Sélectionnez une conversation</p>
            <p class="dc-ph-sub">
                @if($nonRepondus > 0)
                    <span class="dc-ph-urgent">⚡ {{ $nonRepondus }} message{{ $nonRepondus > 1 ? 's' : '' }} en attente de réponse</span>
                @else
                    Toutes vos conversations sont traitées ✓
                @endif
            </p>
        </div>

        {{-- Chat actif --}}
        <div id="dcActiveChat" style="display:none" class="dc-active-chat">

            {{-- Chat header --}}
            <div class="dc-chat-head">
                <button id="waBackBtn" class="dc-back-btn" aria-label="Retour">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                </button>

                <div class="dc-head-contact">
                    <div class="dc-head-avatar" id="waHeaderAvatar"></div>
                    <div>
                        <div class="dc-head-name" id="waContactName">—</div>
                        <div class="dc-head-status">
                            <span class="dc-online-dot" id="dcOnlineDot"></span>
                            <span id="waContactEmail">—</span>
                        </div>
                    </div>
                </div>

                <div class="dc-head-actions">
                    <button id="waNotifBtn" class="dc-head-btn" title="Activer les notifications sonores">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </button>
                    <button id="waArchiveBtn" class="dc-head-btn" title="Archiver / Désarchiver">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                    </button>
                    <button id="waInfoBtn" class="dc-head-btn" title="Fiche client">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Zone messages --}}
            <div class="dc-messages-area" id="waMessages"></div>

            {{-- Input zone --}}
            <div class="dc-input-zone" id="waInput">
                <div class="dc-input-wrap">
                    <textarea
                        id="waMessageInput"
                        rows="1"
                        placeholder="Répondre au client… (Entrée pour envoyer)"
                        maxlength="2000"
                        aria-label="Message"
                    ></textarea>
                    <div class="dc-input-meta">
                        <span class="dc-char-count"><span id="waCharCount">0</span>/2000</span>
                        <span class="dc-input-hint">Maj+Entrée pour nouvelle ligne</span>
                    </div>
                </div>
                <button id="waSendBtn" class="dc-send-btn" aria-label="Envoyer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 2 11 13M22 2 15 22l-4-9-9-4 20-7z"/></svg>
                </button>
            </div>

            {{-- Anti-spam warning --}}
            <div class="dc-spam-warning" id="dcSpamWarn" style="display:none">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <span id="dcSpamText">Merci de patienter avant de renvoyer.</span>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     INFO PANEL CLIENT
═══════════════════════════════════════════ --}}
<div id="waInfoPanel" class="dc-info-panel">
    <div class="dc-info-head">
        <span>Fiche client</span>
        <button id="waCloseInfo" aria-label="Fermer">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="dc-info-body">
        <div class="dc-info-av" id="waInfoAvatarLarge"></div>
        <div class="dc-info-name" id="waInfoName">—</div>
        <div class="dc-info-role">Client</div>

        <div class="dc-info-fields">
            <div class="dc-info-row">
                <div class="dc-info-row-icon" style="background:rgba(37,99,168,.1);color:#2563a8">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <div>
                    <div class="dc-info-label">Email</div>
                    <div class="dc-info-val" id="waInfoEmail">—</div>
                </div>
            </div>
            <div class="dc-info-row">
                <div class="dc-info-row-icon" style="background:rgba(45,156,79,.1);color:#2d9c4f">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.69 12 19.79 19.79 0 011.61 3.39 2 2 0 013.6 1.21h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 8.91a16 16 0 006 6l.86-.86a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                </div>
                <div>
                    <div class="dc-info-label">Téléphone</div>
                    <div class="dc-info-val" id="waInfoPhone">—</div>
                </div>
            </div>
            <div class="dc-info-row" style="border:none">
                <div class="dc-info-row-icon" style="background:rgba(232,114,42,.1);color:#e8722a">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                </div>
                <div>
                    <div class="dc-info-label">Statut</div>
                    <div id="waInfoStatus" class="dc-status-chip" style="margin-top:4px">—</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="waInfoOverlay" class="dc-overlay"></div>

{{-- ═══════════════════════════════════════════
     NOTIFICATION TOAST PREMIUM
═══════════════════════════════════════════ --}}
<div id="dcNotifToast" class="dc-notif-toast" role="alert" aria-live="polite">
    <div class="dc-notif-bar"></div>
    <div class="dc-notif-inner">
        <div class="dc-notif-av" id="dcToastAv"></div>
        <div class="dc-notif-text">
            <div class="dc-notif-name" id="dcToastName"></div>
            <div class="dc-notif-msg"  id="dcToastMsg"></div>
        </div>
        <button class="dc-notif-close" id="dcToastClose" aria-label="Fermer">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="dc-notif-progress" id="dcToastProgress"></div>
</div>

{{-- ═══════════════════════════════════════════
     PERMISSION NOTIFICATION (browser)
═══════════════════════════════════════════ --}}
<div class="dc-notif-permission" id="dcPermBanner" style="display:none">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
    <span>Activez les notifications pour ne manquer aucun message</span>
    <button id="dcAllowNotif">Activer</button>
    <button id="dcDismissNotif">Plus tard</button>
</div>

{{-- ═══════════════════════════════════════════
     STYLES
═══════════════════════════════════════════ --}}
<style>
/* ── Tokens locaux ────────────────────────── */
:root {
    --brand-blue:   #2563a8;
    --brand-blue-l: #3b82c4;
    --brand-pink:   #d63384;
    --brand-orange: #e8722a;
    --brand-green:  #2d9c4f;
    --brand-teal:   #1a8fa0;
}

/* ── Keyframes ─────────────────────────────── */
@keyframes dc-fade-up   { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
@keyframes dc-slide-in  { from{opacity:0;transform:translateX(80px) scale(.95)} to{opacity:1;transform:translateX(0) scale(1)} }
@keyframes dc-pulse-dot { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.5);opacity:.6} }
@keyframes dc-ring-pulse{ 0%{transform:scale(1);opacity:.3} 100%{transform:scale(2.2);opacity:0} }
@keyframes dc-bubble-in { from{opacity:0;transform:scale(.92) translateY(6px)} to{opacity:1;transform:scale(1) translateY(0)} }
@keyframes dc-progress  { from{width:100%} to{width:0%} }
@keyframes dc-spin      { to{transform:rotate(360deg)} }
@keyframes dc-flash     { 0%,100%{background:transparent} 50%{background:rgba(37,99,168,.08)} }

/* ── Stats row ─────────────────────────────── */
.dc-stats {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}

.dc-stat {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 14px;
    padding: 12px 18px;
    flex: 1;
    min-width: 130px;
    transition: all .2s ease;
    position: relative;
    overflow: hidden;
}

.dc-stat:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.08); }
.dc-stat.urgent { border-color:rgba(232,114,42,.35); background:rgba(232,114,42,.04); }

.dc-stat-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.dc-stat-num  { font-family:'Outfit',sans-serif; font-size:22px; font-weight:800; color:var(--text-primary,#0f1923); line-height:1; }
.dc-stat-lbl  { font-size:11px; color:var(--text-muted,#94a3b8); font-weight:500; margin-top:2px; }

.dc-stat-pulse {
    position:absolute; top:10px; right:10px;
    width:10px; height:10px; border-radius:50%;
    background:var(--brand-orange);
    animation:dc-pulse-dot 1.4s ease-in-out infinite;
}

/* Realtime badge */
.dc-realtime-badge {
    display:flex; align-items:center; gap:7px;
    padding:8px 16px; border-radius:20px;
    background:var(--bg-surface,#fff); border:1px solid var(--border-color,#e2e8f0);
    font-size:12px; font-weight:600; color:var(--text-secondary,#4a5568);
    white-space:nowrap; flex-shrink:0; margin-left:auto;
    transition:all .2s;
}

.dc-rt-dot {
    width:8px; height:8px; border-radius:50%;
    background:#94a3b8;
    transition:background .3s;
}

.dc-realtime-badge.connected   .dc-rt-dot { background:var(--brand-green); box-shadow:0 0 6px rgba(45,156,79,.5); animation:dc-pulse-dot 2s ease-in-out infinite; }
.dc-realtime-badge.connected   { border-color:rgba(45,156,79,.3); color:var(--brand-green); }
.dc-realtime-badge.disconnected .dc-rt-dot { background:#ef4444; }
.dc-realtime-badge.disconnected { border-color:rgba(239,68,68,.3); color:#ef4444; }

/* ── Messenger layout ──────────────────────── */
.dc-messenger {
    display:flex;
    height:calc(100vh - 260px);
    min-height:500px;
    background:var(--bg-surface,#fff);
    border:1px solid var(--border-color,#e2e8f0);
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 4px 24px rgba(0,0,0,.07);
    animation:dc-fade-up .35s ease;
}

/* ── SIDEBAR ──────────────────────────────── */
.dc-sidebar {
    width:310px;
    flex-shrink:0;
    display:flex;
    flex-direction:column;
    border-right:1px solid var(--border-color,#e2e8f0);
    background:var(--bg-surface,#fff);
    overflow:hidden;
    transition:transform .25s cubic-bezier(.4,0,.2,1);
}

.dc-sb-head {
    padding:16px 18px 14px;
    border-bottom:1px solid var(--border-subtle,#eef2f7);
    flex-shrink:0;
    background:var(--bg-surface-2,#f8fafc);
}

.dc-sb-title {
    font-family:'Outfit',sans-serif;
    font-size:16px; font-weight:800;
    color:var(--text-primary,#0f1923);
    display:flex; align-items:center; gap:8px;
}

.dc-sb-title svg { color:var(--brand-blue); }

.dc-urgent-chip {
    background:linear-gradient(135deg,var(--brand-orange),var(--brand-pink));
    color:white; font-size:10.5px; font-weight:700;
    padding:3px 9px; border-radius:20px;
    animation:dc-pulse-dot 2s ease-in-out infinite;
}

/* Tabs */
.dc-tabs {
    display:flex; gap:4px;
    padding:10px 12px 6px;
    flex-shrink:0;
}

.dc-tab {
    flex:1; padding:7px 8px;
    border:none; border-radius:10px;
    font-size:12.5px; font-weight:500;
    cursor:pointer; color:var(--text-muted,#94a3b8);
    background:transparent; transition:all .18s ease;
    display:flex; align-items:center; justify-content:center; gap:6px;
    font-family:'DM Sans',sans-serif;
}

.dc-tab:hover   { background:var(--bg-surface-2,#f8fafc); color:var(--text-secondary,#4a5568); }
.dc-tab.active  { background:rgba(37,99,168,.1); color:var(--brand-blue); font-weight:600; }

.dc-tab-badge { background:var(--brand-orange); color:white; font-size:10px; font-weight:700; min-width:18px; height:18px; border-radius:9px; display:inline-flex; align-items:center; justify-content:center; padding:0 5px; }
.dc-tab-badge.muted { background:var(--text-muted,#94a3b8); }

/* Search */
.dc-search-wrap {
    display:flex; align-items:center; gap:8px;
    padding:7px 12px 7px;
    margin:0 10px 8px;
    background:var(--bg-surface-2,#f8fafc);
    border:1.5px solid var(--border-color,#e2e8f0);
    border-radius:12px;
    color:var(--text-muted,#94a3b8);
    transition:all .18s ease;
    flex-shrink:0;
}

.dc-search-wrap:focus-within { border-color:var(--brand-blue); background:var(--bg-surface,#fff); box-shadow:0 0 0 3px rgba(37,99,168,.1); }

.dc-search-wrap input {
    flex:1; border:none; background:transparent;
    font-size:12.5px; color:var(--text-primary,#0f1923); outline:none;
    font-family:'DM Sans',sans-serif;
    width:100%; padding:0;
    box-shadow:none !important;
}

.dc-search-wrap input::placeholder { color:var(--text-muted,#94a3b8); }
.dc-search-clear { background:none; border:none; cursor:pointer; color:var(--text-muted,#94a3b8); display:flex; align-items:center; padding:2px; border-radius:4px; transition:color .15s; }
.dc-search-clear:hover { color:var(--brand-blue); }

/* Conv list */
.dc-conv-list { flex:1; overflow-y:auto; padding:4px 6px; }
.dc-conv-list::-webkit-scrollbar { width:4px; }
.dc-conv-list::-webkit-scrollbar-thumb { background:var(--border-color,#e2e8f0); border-radius:4px; }
.dc-conv-list::-webkit-scrollbar-thumb:hover { background:var(--brand-blue-l,#3b82c4); }

/* Conv item */
.dc-conv-item {
    display:flex; align-items:flex-start; gap:10px;
    padding:11px 10px; border-radius:12px;
    cursor:pointer; border:1px solid transparent;
    margin-bottom:2px;
    transition:all .16s ease;
    position:relative;
}

.dc-conv-item:hover  { background:var(--bg-surface-2,#f8fafc); }
.dc-conv-item.active { background:rgba(37,99,168,.07); border-color:rgba(37,99,168,.2); }
.dc-conv-item.is-unread { background:rgba(232,114,42,.05); border-color:rgba(232,114,42,.2); }
.dc-conv-item.has-new   { animation:dc-flash .6s ease; }

.dc-conv-av {
    width:42px; height:42px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    color:white; font-size:14px; font-weight:700;
    flex-shrink:0; font-family:'Outfit',sans-serif;
    box-shadow:0 2px 8px rgba(0,0,0,.12);
}

.dc-conv-body { flex:1; min-width:0; }

.dc-conv-row1 { display:flex; justify-content:space-between; align-items:center; margin-bottom:2px; }
.dc-conv-name { font-size:13px; font-weight:600; color:var(--text-primary,#0f1923); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:140px; }
.dc-conv-time { font-size:10.5px; color:var(--text-muted,#94a3b8); flex-shrink:0; }
.dc-conv-preview { font-size:12px; color:var(--text-muted,#94a3b8); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:4px; }

.dc-conv-row3 { display:flex; align-items:center; justify-content:space-between; }

/* Status chips */
.dc-status-chip {
    display:inline-flex; align-items:center; gap:4px;
    font-size:10px; font-weight:600; padding:2px 7px; border-radius:10px;
    white-space:nowrap;
}
.dc-status-chip.pending  { background:rgba(232,114,42,.12); color:var(--brand-orange); }
.dc-status-chip.replied  { background:rgba(45,156,79,.12);  color:var(--brand-green); }
.dc-status-chip.archived { background:var(--bg-surface-2,#f8fafc); color:var(--text-muted,#94a3b8); }

.dc-chip-dot { width:5px; height:5px; border-radius:50%; background:var(--brand-orange); animation:dc-pulse-dot 1.5s ease-in-out infinite; }

.dc-unread-dot { width:8px; height:8px; border-radius:50%; background:var(--brand-orange); animation:dc-pulse-dot 1.5s ease-in-out infinite; flex-shrink:0; }

/* Search highlight */
mark.dc-hl { background:rgba(37,99,168,.18); color:var(--brand-blue); border-radius:3px; padding:0 2px; font-weight:700; }

/* Empty */
.dc-empty-conv { display:flex; flex-direction:column; align-items:center; text-align:center; padding:60px 20px; color:var(--text-muted,#94a3b8); gap:8px; }
.dc-empty-conv svg { opacity:.35; }
.dc-empty-conv p   { font-size:14px; font-weight:600; color:var(--text-secondary,#4a5568); }
.dc-empty-conv span { font-size:12px; }

/* ── CHAT ZONE ─────────────────────────────── */
.dc-chat-zone {
    flex:1; display:flex; flex-direction:column;
    background:var(--bg-base,#f0f4f8);
    background-image:radial-gradient(circle at 18px 18px, rgba(37,99,168,.025) 1px, transparent 1px);
    background-size:22px 22px;
    position:relative; min-width:0;
}

/* Placeholder */
.dc-placeholder {
    flex:1; display:flex; flex-direction:column;
    align-items:center; justify-content:center; gap:12px;
    color:var(--text-muted,#94a3b8);
}

.dc-placeholder-visual { position:relative; width:100px; height:100px; display:flex; align-items:center; justify-content:center; margin-bottom:4px; }

.dc-ph-rings { position:absolute; inset:0; }
.dc-ph-ring { position:absolute; inset:0; border-radius:50%; border:1.5px solid rgba(37,99,168,.15); animation:dc-ring-pulse 3s ease-in-out infinite; }
.dc-ph-ring.r2 { animation-delay:.6s; }
.dc-ph-ring.r3 { animation-delay:1.2s; }

.dc-ph-icon {
    width:60px; height:60px; border-radius:50%;
    background:var(--bg-surface,#fff);
    box-shadow:0 6px 20px rgba(0,0,0,.1);
    display:flex; align-items:center; justify-content:center;
    color:var(--text-muted,#94a3b8);
    z-index:1;
}

.dc-ph-title { font-family:'Outfit',sans-serif; font-size:17px; font-weight:700; color:var(--text-secondary,#4a5568); }
.dc-ph-sub   { font-size:12.5px; text-align:center; }
.dc-ph-urgent { color:var(--brand-orange); background:rgba(232,114,42,.1); padding:5px 14px; border-radius:20px; display:inline-flex; align-items:center; gap:4px; font-weight:600; }

/* Active chat */
.dc-active-chat { display:flex; flex-direction:column; height:100%; }

/* Chat header */
.dc-chat-head {
    display:flex; align-items:center; gap:12px;
    padding:13px 18px;
    background:var(--bg-surface,#fff);
    border-bottom:1px solid var(--border-color,#e2e8f0);
    flex-shrink:0;
    box-shadow:0 1px 4px rgba(0,0,0,.04);
}

.dc-back-btn {
    display:none; width:34px; height:34px;
    background:var(--bg-surface-2,#f8fafc);
    border:1.5px solid var(--border-color,#e2e8f0);
    border-radius:10px; cursor:pointer;
    color:var(--text-secondary,#4a5568);
    align-items:center; justify-content:center;
    flex-shrink:0; transition:all .15s;
}
.dc-back-btn:hover { background:var(--border-color,#e2e8f0); color:var(--text-primary,#0f1923); }

.dc-head-contact { display:flex; align-items:center; gap:12px; flex:1; min-width:0; }

.dc-head-avatar {
    width:40px; height:40px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    color:white; font-size:15px; font-weight:700;
    flex-shrink:0; font-family:'Outfit',sans-serif;
    box-shadow:0 3px 10px rgba(0,0,0,.15);
}

.dc-head-name { font-size:14.5px; font-weight:700; color:var(--text-primary,#0f1923); font-family:'Outfit',sans-serif; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

.dc-head-status { display:flex; align-items:center; gap:5px; font-size:12px; color:var(--text-muted,#94a3b8); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.dc-online-dot  { width:7px; height:7px; border-radius:50%; background:var(--brand-green); flex-shrink:0; box-shadow:0 0 6px rgba(45,156,79,.5); animation:dc-pulse-dot 2.5s ease-in-out infinite; }

.dc-head-actions { display:flex; gap:6px; flex-shrink:0; }

.dc-head-btn {
    width:34px; height:34px; border-radius:10px;
    background:var(--bg-surface-2,#f8fafc);
    border:1.5px solid var(--border-color,#e2e8f0);
    cursor:pointer; color:var(--text-secondary,#4a5568);
    display:flex; align-items:center; justify-content:center;
    transition:all .18s ease;
}
.dc-head-btn:hover { background:rgba(37,99,168,.1); color:var(--brand-blue); border-color:rgba(37,99,168,.25); }
.dc-head-btn.sound-on { background:rgba(45,156,79,.1); color:var(--brand-green); border-color:rgba(45,156,79,.25); }
.dc-head-btn.arch-on  { background:rgba(239,68,68,.1); color:#ef4444; border-color:rgba(239,68,68,.2); }

/* Messages */
.dc-messages-area { flex:1; overflow-y:auto; padding:18px; display:flex; flex-direction:column; gap:10px; }
.dc-messages-area::-webkit-scrollbar { width:4px; }
.dc-messages-area::-webkit-scrollbar-thumb { background:var(--border-color,#e2e8f0); border-radius:4px; }
.dc-messages-area::-webkit-scrollbar-thumb:hover { background:var(--brand-blue); }

/* Bubbles */
.dc-msg-left  { display:flex; justify-content:flex-start; animation:dc-bubble-in .22s ease; }
.dc-msg-right { display:flex; justify-content:flex-end;   animation:dc-bubble-in .22s ease; }

.dc-bubble-l {
    background:var(--bg-surface,#fff);
    border-radius:18px 18px 18px 4px;
    padding:10px 14px; max-width:65%;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
    border:1px solid var(--border-subtle,#eef2f7);
}

.dc-bubble-r {
    background:linear-gradient(135deg,var(--brand-blue),var(--brand-blue-l,#3b82c4));
    border-radius:18px 18px 4px 18px;
    padding:10px 14px; max-width:65%;
    box-shadow:0 3px 12px rgba(37,99,168,.25);
}

.dc-msg-sender   { font-size:11px; font-weight:700; color:var(--brand-blue); margin-bottom:4px; }
.dc-msg-sender-r { font-size:11px; font-weight:700; color:rgba(255,255,255,.7); margin-bottom:4px; }
.dc-msg-text     { font-size:13.5px; color:var(--text-primary,#0f1923); line-height:1.5; word-break:break-word; }
.dc-msg-text-r   { font-size:13.5px; color:white; line-height:1.5; word-break:break-word; }
.dc-msg-time     { font-size:10px; color:var(--text-muted,#94a3b8); margin-top:5px; text-align:right; }
.dc-msg-time-r   { font-size:10px; color:rgba(255,255,255,.55); margin-top:5px; text-align:right; display:flex; align-items:center; justify-content:flex-end; gap:3px; }

/* Loading spinner in messages */
.dc-msg-loader { display:flex; align-items:center; justify-content:center; padding:40px; color:var(--text-muted,#94a3b8); gap:8px; font-size:13px; }
.dc-msg-loader svg { animation:dc-spin .8s linear infinite; }

/* Input zone */
.dc-input-zone {
    background:var(--bg-surface,#fff);
    padding:12px 16px;
    display:flex; gap:10px; align-items:flex-end;
    border-top:2px solid var(--border-color,#e2e8f0);
    flex-shrink:0;
}

.dc-input-wrap { flex:1; position:relative; }

.dc-input-wrap textarea {
    width:100%;
    border:1.5px solid var(--border-color,#e2e8f0);
    background:var(--bg-surface-2,#f8fafc);
    border-radius:14px; padding:11px 14px 30px;
    font-size:13.5px; line-height:1.5;
    resize:none; outline:none;
    color:var(--text-primary,#0f1923);
    max-height:150px; min-height:46px;
    transition:all .2s ease;
    font-family:'DM Sans',sans-serif;
    box-shadow:none !important;
}

.dc-input-wrap textarea:focus { border-color:var(--brand-blue); background:var(--bg-surface,#fff); box-shadow:0 0 0 3px rgba(37,99,168,.1) !important; }
.dc-input-wrap textarea::placeholder { color:var(--text-muted,#94a3b8); }
.dc-input-wrap textarea:disabled { opacity:.45; cursor:not-allowed; }

.dc-input-meta { position:absolute; bottom:8px; left:12px; right:12px; display:flex; align-items:center; justify-content:space-between; pointer-events:none; }
.dc-char-count { font-size:10px; color:var(--text-muted,#94a3b8); font-family:'DM Sans',sans-serif; }
.dc-input-hint { font-size:10px; color:var(--text-muted,#94a3b8); opacity:.7; }

.dc-send-btn {
    width:46px; height:46px; border-radius:14px;
    background:linear-gradient(135deg,var(--brand-blue),var(--brand-blue-l,#3b82c4));
    border:none; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    color:white; flex-shrink:0;
    transition:all .2s ease;
    box-shadow:0 4px 14px rgba(37,99,168,.35);
}
.dc-send-btn:hover  { transform:translateY(-2px); box-shadow:0 8px 22px rgba(37,99,168,.45); }
.dc-send-btn:active { transform:scale(.96); }
.dc-send-btn:disabled { opacity:.45; cursor:not-allowed; transform:none; }
.dc-send-btn.sending { animation:dc-pulse-dot .8s ease-in-out infinite; }

/* Anti-spam warning */
.dc-spam-warning {
    display:flex; align-items:center; gap:8px;
    margin:0 16px 8px;
    padding:8px 14px; border-radius:10px;
    background:rgba(232,114,42,.1); border:1px solid rgba(232,114,42,.25);
    font-size:12px; color:var(--brand-orange); font-weight:500;
    flex-shrink:0;
}

/* ── INFO PANEL ─────────────────────────────── */
.dc-info-panel {
    position:fixed; top:0; right:0; height:100%;
    width:300px;
    background:var(--bg-surface,#fff);
    transform:translateX(100%);
    transition:transform .28s cubic-bezier(.4,0,.2,1);
    z-index:500;
    display:flex; flex-direction:column;
    box-shadow:-6px 0 30px rgba(0,0,0,.1);
    border-left:1px solid var(--border-color,#e2e8f0);
}
.dc-info-panel.open { transform:translateX(0); }

.dc-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.25); z-index:499; backdrop-filter:blur(2px); }
.dc-overlay.open { display:block; }

.dc-info-head {
    background:linear-gradient(135deg,var(--brand-blue),var(--brand-blue-l,#3b82c4));
    padding:18px 20px;
    display:flex; justify-content:space-between; align-items:center;
    color:white; font-family:'Outfit',sans-serif; font-size:15px; font-weight:700;
    flex-shrink:0;
}
.dc-info-head button { background:rgba(255,255,255,.15); border:none; color:white; width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:background .15s; }
.dc-info-head button:hover { background:rgba(255,255,255,.25); }

.dc-info-body { padding:24px; display:flex; flex-direction:column; align-items:center; overflow-y:auto; flex:1; }

.dc-info-av { width:72px; height:72px; border-radius:18px; display:flex; align-items:center; justify-content:center; color:white; font-size:24px; font-weight:700; font-family:'Outfit',sans-serif; margin-bottom:12px; box-shadow:0 6px 20px rgba(0,0,0,.12); }
.dc-info-name { font-family:'Outfit',sans-serif; font-size:18px; font-weight:800; color:var(--text-primary,#0f1923); }
.dc-info-role { font-size:12px; color:var(--text-muted,#94a3b8); margin-top:3px; margin-bottom:18px; }

.dc-info-fields { width:100%; }
.dc-info-row { display:flex; align-items:flex-start; gap:12px; padding:11px 0; border-bottom:1px solid var(--border-subtle,#eef2f7); }
.dc-info-row-icon { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.dc-info-label { font-size:10px; color:var(--text-muted,#94a3b8); text-transform:uppercase; letter-spacing:.5px; font-weight:700; margin-bottom:3px; }
.dc-info-val   { font-size:13px; color:var(--text-primary,#0f1923); font-weight:500; word-break:break-all; }

/* ── TOAST NOTIFICATION PREMIUM ──────────────── */
.dc-notif-toast {
    position:fixed; bottom:22px; right:22px;
    width:340px; max-width:calc(100vw - 32px);
    background:var(--bg-surface,#fff);
    border:1px solid var(--border-color,#e2e8f0);
    border-radius:16px;
    box-shadow:0 12px 40px rgba(0,0,0,.15), 0 2px 6px rgba(0,0,0,.06);
    overflow:hidden;
    transform:translateX(calc(100% + 32px));
    opacity:0;
    transition:transform .4s cubic-bezier(.34,1.56,.64,1), opacity .3s ease;
    pointer-events:none;
    z-index:2000;
    border-left:4px solid var(--brand-blue);
}

.dc-notif-toast.show {
    transform:translateX(0);
    opacity:1;
    pointer-events:all;
    animation:dc-slide-in .4s cubic-bezier(.34,1.56,.64,1);
}

.dc-notif-bar { height:3px; background:linear-gradient(90deg,var(--brand-blue),var(--brand-pink),var(--brand-orange)); }

.dc-notif-inner { display:flex; align-items:center; gap:12px; padding:12px 14px; }

.dc-notif-av { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:white; font-size:15px; font-weight:700; flex-shrink:0; font-family:'Outfit',sans-serif; box-shadow:0 3px 10px rgba(0,0,0,.15); }

.dc-notif-text  { flex:1; min-width:0; }
.dc-notif-name  { font-size:13.5px; font-weight:700; color:var(--text-primary,#0f1923); font-family:'Outfit',sans-serif; }
.dc-notif-msg   { font-size:12px; color:var(--text-secondary,#4a5568); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:2px; }

.dc-notif-close {
    background:var(--bg-surface-2,#f8fafc); border:1px solid var(--border-color,#e2e8f0);
    border-radius:8px; cursor:pointer; color:var(--text-muted,#94a3b8);
    width:26px; height:26px; display:flex; align-items:center; justify-content:center;
    flex-shrink:0; transition:all .15s;
}
.dc-notif-close:hover { background:var(--border-color,#e2e8f0); color:var(--text-primary,#0f1923); }

.dc-notif-progress { height:3px; background:linear-gradient(90deg,var(--brand-blue),var(--brand-blue-l,#3b82c4)); width:100%; }
.dc-notif-progress.running { animation:dc-progress 5s linear forwards; }

/* ── PERMISSION BANNER ──────────────────────── */
.dc-notif-permission {
    display:flex; align-items:center; gap:10px;
    background:rgba(37,99,168,.08); border:1px solid rgba(37,99,168,.2);
    border-radius:12px; padding:10px 16px; margin-bottom:16px;
    font-size:12.5px; color:var(--brand-blue); font-weight:500;
    animation:dc-fade-up .3s ease;
}
.dc-notif-permission button { padding:5px 14px; border-radius:8px; border:none; font-size:12px; font-weight:600; cursor:pointer; transition:all .15s; font-family:'DM Sans',sans-serif; }
.dc-notif-permission #dcAllowNotif  { background:var(--brand-blue); color:white; margin-left:auto; }
.dc-notif-permission #dcDismissNotif { background:transparent; color:var(--text-muted,#94a3b8); }
.dc-notif-permission #dcAllowNotif:hover { background:var(--brand-blue-l,#3b82c4); }

/* ── RESPONSIVE ─────────────────────────────── */
@media (max-width:768px) {
    .dc-sidebar { position:absolute; width:100%; height:100%; z-index:10; background:var(--bg-surface,#fff); }
    .dc-sidebar.hide-mobile { transform:translateX(-100%); }
    .dc-back-btn { display:flex !important; }
    .dc-info-panel { width:100%; }
    .dc-notif-toast { bottom:12px; right:12px; left:12px; width:auto; }
    .dc-messenger { height:calc(100vh - 200px); border-radius:14px; }
}

@media (max-width:480px) {
    .dc-stats { gap:8px; }
    .dc-stat  { padding:10px 12px; min-width:110px; }
    .dc-stat-num { font-size:18px; }
}
</style>

{{-- ═══════════════════════════════════════════
     JAVASCRIPT — Fonctionnalités complètes + sécurité
═══════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    /* ════════════════════════════════════════
       ÉTAT GLOBAL
    ════════════════════════════════════════ */
    let currentEmail    = '';
    let currentArchived = false;
    let currentInitials = '';
    let currentColor1   = '';
    let currentColor2   = '';
    let currentPhone    = '';

    const archivedSet = new Set();
    const unreadSet   = new Set();
    let isMobile      = innerWidth <= 768;

    // Sons
    let audioCtx    = null;
    let soundEnabled = true;

    // Anti-spam envoi
    const SEND_COOLDOWN  = 1500;   // ms minimum entre 2 envois
    const SEND_MAX_BURST = 5;       // max envois / 30s
    let lastSendTime     = 0;
    let sendCount        = 0;
    let sendCountReset   = null;
    let sendInProgress   = false;

    // Anti-spam refresh
    let isRefreshing = false;

    // Toast
    let toastTimer      = null;
    let progressTimer   = null;

    // Notification browser
    let browserNotifEnabled = false;

    window.addEventListener('resize', () => { isMobile = innerWidth <= 768; });

    /* ════════════════════════════════════════
       SÉCURITÉ — Utilitaires
    ════════════════════════════════════════ */
    function escHtml(str) {
        if (str === null || str === undefined) return '';
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    }

    function sanitize(str, max = 2000) {
        if (!str) return '';
        // Strip tags, trim, limit
        return String(str)
            .replace(/<[^>]*>/g, '')
            .replace(/[\x00-\x08\x0B\x0C\x0E-\x1F]/g, '')  // control chars
            .substring(0, max)
            .trim();
    }

    function isValidMessage(msg) {
        if (!msg || typeof msg !== 'string') return false;
        const t = msg.trim();
        if (t.length < 1 || t.length > 2000) return false;
        if (/<[^>]*>/.test(t)) return false; // no HTML
        return true;
    }

    /* ════════════════════════════════════════
       ANTI-SPAM — Rate limiter envoi
    ════════════════════════════════════════ */
    function checkSendRateLimit() {
        const now = Date.now();

        // Reset compteur toutes les 30s
        if (!sendCountReset) {
            sendCountReset = setTimeout(() => { sendCount = 0; sendCountReset = null; }, 30000);
        }

        if (now - lastSendTime < SEND_COOLDOWN) {
            showSpamWarning(`Merci de patienter ${((SEND_COOLDOWN - (now - lastSendTime)) / 1000).toFixed(1)}s avant de renvoyer.`);
            return false;
        }

        if (sendCount >= SEND_MAX_BURST) {
            showSpamWarning('Trop de messages envoyés. Attendez 30 secondes.');
            return false;
        }

        return true;
    }

    function showSpamWarning(text) {
        const el  = document.getElementById('dcSpamWarn');
        const txt = document.getElementById('dcSpamText');
        if (!el) return;
        if (txt) txt.textContent = text;
        el.style.display = 'flex';
        clearTimeout(el._t);
        el._t = setTimeout(() => { el.style.display = 'none'; }, 4000);
    }

    /* ════════════════════════════════════════
       AUDIO — Notifications sonores
    ════════════════════════════════════════ */
    function initAudio() {
        if (!audioCtx) {
            try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch(e) {}
        }
    }

    document.addEventListener('click', initAudio, { once: true });

    function playNotifSound() {
        if (!soundEnabled) return;
        try {
            initAudio();
            if (!audioCtx) return;
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const t = audioCtx.currentTime;
            // Double bip mélodieux
            [[880, t, 0.18, t + 0.22], [1100, t + 0.15, 0.12, t + 0.35]].forEach(([freq, start, gain, end]) => {
                const osc = audioCtx.createOscillator();
                const gnd = audioCtx.createGain();
                osc.connect(gnd);
                gnd.connect(audioCtx.destination);
                osc.type = 'sine';
                osc.frequency.value = freq;
                gnd.gain.setValueAtTime(0, start);
                gnd.gain.linearRampToValueAtTime(gain, start + 0.04);
                gnd.gain.exponentialRampToValueAtTime(0.00001, end);
                osc.start(start);
                osc.stop(end + 0.05);
            });
        } catch(e) {}
    }

    /* ════════════════════════════════════════
       BOUTON SON
    ════════════════════════════════════════ */
    document.getElementById('waNotifBtn')?.addEventListener('click', () => {
        soundEnabled = !soundEnabled;
        const btn = document.getElementById('waNotifBtn');
        btn?.classList.toggle('sound-on', soundEnabled);
        btn?.setAttribute('title', soundEnabled ? 'Son activé' : 'Son désactivé');
    });

    /* ════════════════════════════════════════
       TOAST PREMIUM
    ════════════════════════════════════════ */
    function showToast(name, msg, c1, c2) {
        const toast = document.getElementById('dcNotifToast');
        const av    = document.getElementById('dcToastAv');
        if (!toast) return;

        document.getElementById('dcToastName').textContent = escHtml(name).substring(0, 40);
        document.getElementById('dcToastMsg').textContent  = String(msg || '').substring(0, 70);

        if (av) {
            av.textContent      = String(name).substring(0, 2).toUpperCase();
            av.style.background = `linear-gradient(135deg, ${c1}, ${c2})`;
        }

        // Progress bar
        const bar = document.getElementById('dcToastProgress');
        if (bar) { bar.className = 'dc-notif-progress'; void bar.offsetWidth; bar.className = 'dc-notif-progress running'; }

        toast.classList.add('show');
        playNotifSound();

        clearTimeout(toastTimer);
        toastTimer = setTimeout(hideToast, 5500);
    }

    function hideToast() {
        const toast = document.getElementById('dcNotifToast');
        toast?.classList.remove('show');
    }

    window.hideToast = hideToast;
    document.getElementById('dcToastClose')?.addEventListener('click', hideToast);

    /* ════════════════════════════════════════
       NOTIFICATION NAVIGATEUR
    ════════════════════════════════════════ */
    function initBrowserNotif() {
        if (!('Notification' in window)) return;
        if (Notification.permission === 'granted') {
            browserNotifEnabled = true;
            document.getElementById('dcPermBanner')?.remove();
            return;
        }
        if (Notification.permission !== 'denied') {
            // Afficher le banner
            const banner = document.getElementById('dcPermBanner');
            if (banner) banner.style.display = 'flex';
        }
    }

    document.getElementById('dcAllowNotif')?.addEventListener('click', () => {
        Notification.requestPermission().then(p => {
            if (p === 'granted') {
                browserNotifEnabled = true;
                document.getElementById('dcPermBanner')?.remove();
            }
        });
    });

    document.getElementById('dcDismissNotif')?.addEventListener('click', () => {
        document.getElementById('dcPermBanner')?.remove();
    });

    function sendBrowserNotif(name, msg) {
        if (!browserNotifEnabled || document.hasFocus()) return;
        try {
            new Notification(`💬 ${name}`, {
                body: String(msg || '').substring(0, 100),
                icon: '/images/logo.jpg'
            });
        } catch(e) {}
    }

    /* ════════════════════════════════════════
       UNREAD MARKERS
    ════════════════════════════════════════ */
    function markAsUnread(email) {
        unreadSet.add(email);
        const item = document.querySelector(`.dc-conv-item[data-email="${CSS.escape(email)}"]`);
        if (!item) return;
        item.classList.add('has-new');
        if (!item.querySelector('.dc-new-pulse')) {
            const dot = document.createElement('div');
            dot.className = 'dc-new-pulse';
            dot.style.cssText = 'width:8px;height:8px;background:var(--brand-green,#2d9c4f);border-radius:50%;position:absolute;top:8px;left:8px;animation:dc-pulse-dot 1.5s infinite;border:2px solid var(--bg-surface,#fff)';
            item.style.position = 'relative';
            item.prepend(dot);
        }
    }

    function clearUnread(email) {
        unreadSet.delete(email);
        const item = document.querySelector(`.dc-conv-item[data-email="${CSS.escape(email)}"]`);
        if (!item) return;
        item.classList.remove('has-new');
        item.querySelector('.dc-new-pulse')?.remove();
    }

    /* ════════════════════════════════════════
       INFO PANEL
    ════════════════════════════════════════ */
    const infoPanel   = document.getElementById('waInfoPanel');
    const infoOverlay = document.getElementById('waInfoOverlay');

    document.getElementById('waInfoBtn')?.addEventListener('click', () => {
        infoPanel?.classList.add('open');
        infoOverlay?.classList.add('open');
    });
    document.getElementById('waCloseInfo')?.addEventListener('click', closeInfo);
    infoOverlay?.addEventListener('click', closeInfo);

    function closeInfo() {
        infoPanel?.classList.remove('open');
        infoOverlay?.classList.remove('open');
    }

    /* ════════════════════════════════════════
       ARCHIVE
    ════════════════════════════════════════ */
    document.getElementById('waArchiveBtn')?.addEventListener('click', () => {
        if (!currentEmail) return;
        archiveConv(currentEmail, !currentArchived);
    });

    function updateArchiveBtn() {
        const btn = document.getElementById('waArchiveBtn');
        if (!btn) return;
        btn.title = currentArchived ? 'Désarchiver' : 'Archiver';
        btn.classList.toggle('arch-on', currentArchived);
    }

    function archiveConv(email, archive) {
        fetch('/admin/messages/toggle-close', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ email_client: email, closed: archive })
        })
        .then(r => r.json())
        .then(() => {
            if (archive) archivedSet.add(email); else archivedSet.delete(email);
            if (currentEmail === email) {
                currentArchived = archive;
                document.getElementById('waInput').style.display = archive ? 'none' : 'flex';
                updateArchiveBtn();
            }
            refreshConversationsList();
        })
        .catch(e => console.warn('archive error:', e));
    }

    /* ════════════════════════════════════════
       FORMAT TIME
    ════════════════════════════════════════ */
    function formatTime(d) {
        if (!d) return '';
        try { return new Date(d).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); } catch(e) { return ''; }
    }

    /* ════════════════════════════════════════
       CHARGER CONVERSATION
    ════════════════════════════════════════ */
    async function loadConv(email) {
        if (!email) return;
        const container = document.getElementById('waMessages');
        container.innerHTML = `
            <div class="dc-msg-loader">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Chargement…
            </div>`;

        try {
            const res = await fetch(`/admin/messages/conversation/${encodeURIComponent(email)}`, {
                cache: 'no-store',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const msgs = await res.json();

            if (!msgs || msgs.length === 0) {
                container.innerHTML = '<div style="text-align:center;padding:60px;font-size:13px;color:var(--text-muted)">Aucun message dans cette conversation</div>';
                return;
            }

            let html = '';
            for (const m of msgs) {
                const hasAdmin  = m.reponse_admin && m.reponse_admin.trim() !== '';
                const hasClient = m.message && m.message.trim() !== '';

                if (!hasClient && hasAdmin) {
                    html += `<div class="dc-msg-right">
                        <div class="dc-bubble-r">
                            <div class="dc-msg-sender-r">Vous (Admin)</div>
                            <div class="dc-msg-text-r">${escHtml(m.reponse_admin)}</div>
                            <div class="dc-msg-time-r">
                                <span>${formatTime(m.created_at)}</span>
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            </div>
                        </div>
                    </div>`;
                } else if (hasClient) {
                    html += `<div class="dc-msg-left">
                        <div class="dc-bubble-l">
                            <div class="dc-msg-sender">${escHtml(m.nom_complet || 'Client')}</div>
                            <div class="dc-msg-text">${escHtml(m.message)}</div>
                            <div class="dc-msg-time">${formatTime(m.created_at)}</div>
                        </div>
                    </div>`;
                    if (hasAdmin) {
                        html += `<div class="dc-msg-right">
                            <div class="dc-bubble-r">
                                <div class="dc-msg-sender-r">Vous (Admin)</div>
                                <div class="dc-msg-text-r">${escHtml(m.reponse_admin)}</div>
                                <div class="dc-msg-time-r">
                                    <span>${formatTime(m.updated_at)}</span>
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                </div>
                            </div>
                        </div>`;
                    }
                }
            }

            container.innerHTML = html;
            container.scrollTop = container.scrollHeight;

            // Récup tel depuis premier message
            if (msgs[0]?.telephone) {
                currentPhone = msgs[0].telephone;
                const el = document.getElementById('waInfoPhone');
                if (el) el.textContent = msgs[0].telephone;
            }

        } catch(e) {
            container.innerHTML = `<div style="text-align:center;padding:60px;font-size:13px;color:#ef4444">Erreur de chargement — ${escHtml(e.message)}</div>`;
            console.warn('loadConv error:', e);
        }
    }

    /* ════════════════════════════════════════
       ENVOYER MESSAGE + SÉCURITÉ
    ════════════════════════════════════════ */
    async function sendMsg() {
        if (!currentEmail) return;
        if (archivedSet.has(currentEmail)) { showSpamWarning('Conversation archivée — impossible de répondre.'); return; }
        if (sendInProgress) return;

        const input = document.getElementById('waMessageInput');
        const raw   = input?.value || '';
        const msg   = sanitize(raw);

        if (!isValidMessage(msg)) {
            if (!msg.trim()) return;
            showSpamWarning('Message invalide ou trop long.');
            return;
        }

        // Rate limit
        if (!checkSendRateLimit()) return;

        sendInProgress = true;
        lastSendTime   = Date.now();
        sendCount++;

        const btn = document.getElementById('waSendBtn');
        if (btn) { btn.disabled = true; btn.classList.add('sending'); }

        input.value = '';
        input.style.height = 'auto';
        updateCharCount(0);

        try {
            const res = await fetch('/admin/messages/reply', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email_client: currentEmail, reponse_admin: msg })
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();

            if (data.success !== false) {
                await loadConv(currentEmail);
                refreshConversationsList();
            } else {
                input.value = raw;
                updateCharCount(raw.length);
                showSpamWarning(data.message || 'Erreur lors de l\'envoi.');
            }
        } catch(e) {
            console.error('sendMsg error:', e);
            input.value = raw;
            updateCharCount(raw.length);
            showSpamWarning('Erreur réseau. Vérifiez votre connexion.');
        } finally {
            sendInProgress = false;
            if (btn) { btn.disabled = false; btn.classList.remove('sending'); }
        }
    }

    /* ════════════════════════════════════════
       CHAR COUNTER
    ════════════════════════════════════════ */
    function updateCharCount(len) {
        const el = document.getElementById('waCharCount');
        if (!el) return;
        el.textContent = len;
        el.style.color = len > 1900 ? '#ef4444' : len > 1500 ? 'var(--brand-orange,#e8722a)' : 'var(--text-muted,#94a3b8)';
    }

    const textarea = document.getElementById('waMessageInput');
    textarea?.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 150) + 'px';
        const len = this.value.length;
        updateCharCount(len);
        if (len > 2000) this.value = this.value.substring(0, 2000);
    });

    textarea?.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); }
    });

    document.getElementById('waSendBtn')?.addEventListener('click', sendMsg);
    document.getElementById('waBackBtn')?.addEventListener('click', () => {
        document.getElementById('dcSidebar')?.classList.remove('hide-mobile');
    });

    /* ════════════════════════════════════════
       RECHERCHE DANS LA LISTE
    ════════════════════════════════════════ */
    function escapeRegex(str) { return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }

    function applyConvSearch(term) {
        const items   = document.querySelectorAll('.dc-conv-item');
        const clearEl = document.getElementById('convSearchClear');
        if (clearEl) clearEl.style.display = term ? 'flex' : 'none';

        let count = 0;
        items.forEach(item => {
            const name    = item.dataset.name || '';
            const preview = item.querySelector('.dc-conv-preview')?.textContent || '';
            const matches = !term || name.toLowerCase().includes(term.toLowerCase()) || preview.toLowerCase().includes(term.toLowerCase());
            item.style.display = matches ? '' : 'none';
            if (matches && term) {
                count++;
                const nameEl = item.querySelector('.dc-conv-name');
                if (nameEl) {
                    nameEl.innerHTML = escHtml(name).replace(
                        new RegExp(`(${escapeRegex(escHtml(term))})`, 'gi'),
                        '<mark class="dc-hl">$1</mark>'
                    );
                }
            } else {
                const nameEl = item.querySelector('.dc-conv-name');
                if (nameEl) nameEl.textContent = item.dataset.name;
            }
        });
    }

    document.getElementById('convSearch')?.addEventListener('input', function () {
        applyConvSearch(this.value.trim());
    });

    document.getElementById('convSearchClear')?.addEventListener('click', () => {
        const inp = document.getElementById('convSearch');
        if (inp) { inp.value = ''; }
        applyConvSearch('');
    });

    /* ════════════════════════════════════════
       TABS
    ════════════════════════════════════════ */
    function applyTab(filter) {
        document.querySelectorAll('.dc-conv-item').forEach(item => {
            const isArc = item.dataset.archived === 'true';
            item.style.display = (filter === 'all' && !isArc) || (filter === 'archived' && isArc) ? '' : 'none';
        });
    }

    document.querySelectorAll('.dc-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.dc-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            applyTab(this.dataset.filter);
        });
    });

    /* ════════════════════════════════════════
       ATTACHER EVENTS CONV ITEMS
    ════════════════════════════════════════ */
    function attachEvents() {
        document.querySelectorAll('.dc-conv-item').forEach(el => {
            // Store raw preview text
            const prevEl = el.querySelector('.dc-conv-preview');
            if (prevEl && !prevEl.dataset.raw) prevEl.dataset.raw = prevEl.textContent.trim();

            const ne = el.cloneNode(true);
            el.parentNode.replaceChild(ne, el);

            ne.addEventListener('click', function () {
                document.querySelectorAll('.dc-conv-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');

                currentEmail    = this.dataset.email;
                currentInitials = this.dataset.initials;
                currentColor1   = this.dataset.color1;
                currentColor2   = this.dataset.color2;
                currentPhone    = this.dataset.phone;
                currentArchived = archivedSet.has(currentEmail);
                clearUnread(currentEmail);

                // Header
                const hav = document.getElementById('waHeaderAvatar');
                if (hav) { hav.textContent = currentInitials; hav.style.background = `linear-gradient(135deg,${currentColor1},${currentColor2})`; }
                const nameEl  = document.getElementById('waContactName');
                const emailEl = document.getElementById('waContactEmail');
                if (nameEl)  nameEl.textContent  = this.dataset.name;
                if (emailEl) emailEl.textContent = currentEmail;

                // Info panel
                const iav = document.getElementById('waInfoAvatarLarge');
                if (iav) { iav.textContent = currentInitials; iav.style.background = `linear-gradient(135deg,${currentColor1},${currentColor2})`; }
                const iname  = document.getElementById('waInfoName');
                const iemail = document.getElementById('waInfoEmail');
                const iphone = document.getElementById('waInfoPhone');
                if (iname)  iname.textContent  = this.dataset.name;
                if (iemail) iemail.textContent = currentEmail;
                if (iphone) iphone.textContent = currentPhone || 'Non renseigné';

                // Statut
                const isArc  = archivedSet.has(currentEmail);
                const hasRes = this.dataset.hasResponse === 'true';
                const st = document.getElementById('waInfoStatus');
                if (st) {
                    st.className  = `dc-status-chip ${isArc ? 'archived' : (hasRes ? 'replied' : 'pending')}`;
                    st.textContent = isArc ? 'Archivé' : (hasRes ? 'Répondu' : 'En attente');
                }

                document.getElementById('dcPlaceholder').style.display = 'none';
                document.getElementById('dcActiveChat').style.display  = 'flex';
                document.getElementById('waInput').style.display = currentArchived ? 'none' : 'flex';
                updateArchiveBtn();
                loadConv(currentEmail);

                if (isMobile) document.getElementById('dcSidebar')?.classList.add('hide-mobile');
            });
        });
    }

    /* ════════════════════════════════════════
       REFRESH LISTE
    ════════════════════════════════════════ */
    function refreshConversationsList() {
        if (isRefreshing) return;
        isRefreshing = true;

        fetch(window.location.href, { cache: 'no-store' })
            .then(r => r.text())
            .then(html => {
                const doc  = new DOMParser().parseFromString(html, 'text/html');
                const newL = doc.getElementById('waConversations');
                const oldL = document.getElementById('waConversations');
                if (!newL || !oldL) return;

                const scrollPos = oldL.scrollTop;
                oldL.innerHTML  = newL.innerHTML;
                attachEvents();

                // Restaurer sélection
                if (currentEmail) {
                    const sel = oldL.querySelector(`.dc-conv-item[data-email="${CSS.escape(currentEmail)}"]`);
                    sel?.classList.add('active');
                }

                // Restaurer états
                oldL.querySelectorAll('.dc-conv-item').forEach(el => {
                    const em = el.dataset.email;
                    if (el.dataset.archived === 'true') archivedSet.add(em); else archivedSet.delete(em);
                    if (unreadSet.has(em)) markAsUnread(em);
                });

                oldL.scrollTop = scrollPos;

                // Ré-appliquer recherche active
                const searchVal = document.getElementById('convSearch')?.value.trim();
                if (searchVal) applyConvSearch(searchVal);
            })
            .catch(e => console.warn('refresh error:', e))
            .finally(() => { isRefreshing = false; });
    }

    /* ════════════════════════════════════════
       PUSHER — Temps réel
    ════════════════════════════════════════ */
    function updateRealtimeBadge(state) {
        const badge = document.getElementById('realtimeBadge');
        if (!badge) return;
        badge.className = `dc-realtime-badge ${state}`;
        const txt = badge.querySelector('span');
        if (txt) txt.textContent = state === 'connected' ? 'Temps réel' : (state === 'disconnected' ? 'Déconnecté' : 'Connexion…');
    }

    function initPusher() {
        if (typeof Pusher === 'undefined') { setTimeout(initPusher, 600); return; }

        const pusherKey     = '{{ env("PUSHER_APP_KEY") }}';
        const pusherCluster = '{{ env("PUSHER_APP_CLUSTER") }}';

        if (!pusherKey) { console.error('❌ Clé Pusher manquante'); return; }

        const pusher  = new Pusher(pusherKey, { cluster: pusherCluster, encrypted: true });
        const channel = pusher.subscribe('new-messages');

        pusher.connection.bind('connected',    () => updateRealtimeBadge('connected'));
        pusher.connection.bind('disconnected', () => updateRealtimeBadge('disconnected'));
        pusher.connection.bind('connecting',   () => updateRealtimeBadge('connecting'));

        channel.bind('App\\Events\\NewMessageReceived', function (data) {
            const email   = data.email_client;
            const name    = data.nom_complet || email;
            const message = data.message || 'Nouveau message';

            if (email !== currentEmail) {
                markAsUnread(email);
                const item = document.querySelector(`.dc-conv-item[data-email="${CSS.escape(email)}"]`);
                const c1 = item?.dataset.color1 || '#2563a8';
                const c2 = item?.dataset.color2 || '#3b82c4';
                showToast(name, message, c1, c2);
                sendBrowserNotif(name, message);
                refreshConversationsList();
            } else {
                loadConv(currentEmail);
            }
        });

        //console.log('✅ Pusher initialisé');
    }

    /* ════════════════════════════════════════
       INIT
    ════════════════════════════════════════ */
    attachEvents();
    initPusher();
    initBrowserNotif();

    // Auto-refresh toutes les 30s (fallback si Pusher échoue)
    setInterval(refreshConversationsList, 30000);

})();
</script>

@endsection