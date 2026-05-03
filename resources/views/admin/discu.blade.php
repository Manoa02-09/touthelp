@extends('layouts.admin')

@section('page-title', 'Discussions')
@section('page-subtitle', 'Messagerie clients en temps réel')

@section('content')

@php
    /* ── Conversations triées par message le plus récent ── */
    $totalConv   = $conversations->count();

    /* On enrichit chaque conversation avec le timestamp du dernier message */
    $convsEnriched = $conversations->map(function($conv) {
        $last = \App\Models\Message::where('email_client', $conv->email_client)
                    ->latest()->first();
        $conv->_last_message   = $last;
        $conv->_last_at        = $last?->updated_at ?? $last?->created_at ?? $conv->created_at;
        $conv->_has_response   = $last && $last->reponse_admin && $last->reponse_admin !== '';
        return $conv;
    })->sortByDesc('_last_at')->values();

    $nonRepondus = $convsEnriched->filter(fn($c) => !$c->_has_response && !($c->closed ?? false))->count();
    $archivees   = $convsEnriched->where('closed', true)->count();
    $repondues   = $convsEnriched->where('closed', false)->filter(fn($c) => $c->_has_response)->count();
@endphp

<script src="https://js.pusher.com/8.4.0/pusher.min.js" defer></script>

{{-- ══════════════════════════════════════════
     PERMISSION NOTIFICATIONS (banner discret)
══════════════════════════════════════════ --}}
<div class="ms-perm" id="msPerm" style="display:none" role="alert">
    <div class="ms-perm-inner">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <span>Activez les notifications pour ne manquer aucun message</span>
        <div class="ms-perm-actions">
            <button id="permAllow">Activer</button>
            <button id="permDismiss">Plus tard</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     STATS BAR
══════════════════════════════════════════ --}}
<div class="ms-stats" role="region" aria-label="Statistiques messages">
    <div class="ms-stat">
        <div class="ms-stat-icon" style="--ic:#2563a8;--ib:rgba(37,99,168,.12)">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <div class="ms-stat-info">
            <span class="ms-stat-n">{{ $totalConv }}</span>
            <span class="ms-stat-l">Total</span>
        </div>
    </div>

    <div class="ms-stat {{ $nonRepondus > 0 ? 'is-alert' : '' }}">
        <div class="ms-stat-icon" style="--ic:#e8722a;--ib:rgba(232,114,42,.12)">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        </div>
        <div class="ms-stat-info">
            <span class="ms-stat-n" style="{{ $nonRepondus > 0 ? 'color:#e8722a' : '' }}">{{ $nonRepondus }}</span>
            <span class="ms-stat-l">En attente</span>
        </div>
        @if($nonRepondus > 0)<div class="ms-alert-ring" aria-hidden="true"></div>@endif
    </div>

    <div class="ms-stat">
        <div class="ms-stat-icon" style="--ic:#2d9c4f;--ib:rgba(45,156,79,.12)">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="ms-stat-info">
            <span class="ms-stat-n" style="color:#2d9c4f">{{ $repondues }}</span>
            <span class="ms-stat-l">Répondues</span>
        </div>
    </div>

    <div class="ms-stat">
        <div class="ms-stat-icon" style="--ic:#94a3b8;--ib:var(--bg-surface-2,#f8fafc)">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="21 8 21 21 3 21 3 8"/><rect stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x="1" y="3" width="22" height="5"/></svg>
        </div>
        <div class="ms-stat-info">
            <span class="ms-stat-n">{{ $archivees }}</span>
            <span class="ms-stat-l">Archivées</span>
        </div>
    </div>

    <div class="ms-rt-badge" id="rtBadge" aria-live="polite">
        <div class="ms-rt-dot" aria-hidden="true"></div>
        <span id="rtLabel">Connexion…</span>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MESSENGER SHELL
══════════════════════════════════════════ --}}
<div class="ms-shell" id="msShell">

    {{-- ── SIDEBAR CONVERSATIONS ── --}}
    <div class="ms-panel" id="msPanel" role="complementary" aria-label="Liste des conversations">

        <div class="ms-panel-head">
            <span class="ms-panel-title">
                Conversations
                @if($nonRepondus > 0)
                    <span class="ms-panel-badge" aria-label="{{ $nonRepondus }} en attente">{{ $nonRepondus }}</span>
                @endif
            </span>
        </div>

        <div class="ms-tabs" role="tablist">
            <button class="ms-tab active" data-filter="all" role="tab" aria-selected="true">
                Actives
                @if($nonRepondus > 0)<em>{{ $nonRepondus }}</em>@endif
            </button>
            <button class="ms-tab" data-filter="archived" role="tab" aria-selected="false">
                Archivées
                @if($archivees > 0)<em class="muted">{{ $archivees }}</em>@endif
            </button>
        </div>

        <div class="ms-conv-list" id="msConvList" role="list">
            @if($convsEnriched->count() > 0)
                @foreach($convsEnriched as $conv)
                    @php
                        $lastMsg     = $conv->_last_message;
                        $isArchived  = $conv->closed ?? false;
                        $hasResponse = $conv->_has_response;
                        $isUnread    = !$hasResponse && !$isArchived;
                        $initials    = collect(explode(' ', $conv->nom_complet))
                                        ->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->join('');
                        $palette = [
                            ['#2563a8','#60a5fa'], ['#d63384','#f472b6'],
                            ['#2d9c4f','#4ade80'], ['#e8722a','#fb923c'],
                            ['#7c3aed','#a78bfa'], ['#0891b2','#22d3ee'],
                        ];
                        $cp = $palette[abs(crc32($conv->email_client)) % count($palette)];
                        $phone = '';
                        if (!empty($lastMsg->telephone)) $phone = $lastMsg->telephone;
                        elseif (!empty($lastMsg->phone)) $phone = $lastMsg->phone;
                        elseif (!empty($conv->telephone)) $phone = $conv->telephone;
                        $previewText = $lastMsg ? Str::limit(strip_tags($lastMsg->message ?? ''), 50) : 'Aucun message';
                        $timeStr = $lastMsg ? $conv->_last_at->format('H:i') : '';
                    @endphp
                    <div class="ms-conv {{ $isUnread ? 'unread' : '' }}"
                         role="listitem"
                         tabindex="0"
                         aria-label="Conversation avec {{ e($conv->nom_complet) }}"
                         data-email="{{ e($conv->email_client) }}"
                         data-name="{{ e($conv->nom_complet) }}"
                         data-initials="{{ $initials }}"
                         data-c1="{{ $cp[0] }}"
                         data-c2="{{ $cp[1] }}"
                         data-phone="{{ e($phone) }}"
                         data-archived="{{ $isArchived ? '1' : '0' }}"
                         data-responded="{{ $hasResponse ? '1' : '0' }}">

                        <div class="ms-conv-av" style="background:linear-gradient(145deg,{{ $cp[0] }},{{ $cp[1] }})" aria-hidden="true">
                            {{ $initials }}
                            @if($isUnread)<div class="ms-conv-badge" aria-label="Non lu"></div>@endif
                        </div>

                        <div class="ms-conv-info">
                            <div class="ms-conv-top">
                                <span class="ms-conv-name">{{ e($conv->nom_complet) }}</span>
                                <span class="ms-conv-time" title="{{ $conv->_last_at->format('d/m/Y H:i') }}">{{ $timeStr }}</span>
                            </div>
                            <div class="ms-conv-preview">{{ $previewText }}</div>
                            <span class="ms-tag {{ $isArchived ? 'arc' : ($hasResponse ? 'ok' : 'wait') }}">
                                {{ $isArchived ? 'Archivé' : ($hasResponse ? 'Répondu' : 'En attente') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="ms-empty-list" role="status">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <p>Aucune conversation</p>
                    <span>Les messages clients apparaîtront ici</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── ZONE CHAT ── --}}
    <div class="ms-chat" id="msChat" role="main">

        {{-- Idle placeholder --}}
        <div class="ms-idle" id="msIdle" aria-label="Aucune conversation sélectionnée">
            <div class="ms-idle-orbs" aria-hidden="true">
                <div class="ms-orb o1"></div>
                <div class="ms-orb o2"></div>
                <div class="ms-orb o3"></div>
            </div>
            <div class="ms-idle-icon" aria-hidden="true">
                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <h3>Choisissez une conversation</h3>
            @if($nonRepondus > 0)
                <p class="ms-idle-alert" role="status">⚡ {{ $nonRepondus }} message{{ $nonRepondus > 1 ? 's' : '' }} en attente</p>
            @else
                <p>Toutes les conversations sont à jour ✓</p>
            @endif
        </div>

        {{-- Chat actif --}}
        <div class="ms-thread" id="msThread" style="display:none" role="region" aria-label="Conversation">

            {{-- Header conversation --}}
            <div class="ms-thread-head">
                {{-- BOUTON RETOUR MOBILE — toujours présent dans le DOM, visible via CSS --}}
                <button class="ms-back" id="msBack" aria-label="Retour à la liste">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/>
                    </svg>
                </button>

                <div class="ms-thread-contact">
                    <div class="ms-thread-av" id="threadAv" aria-hidden="true"></div>
                    <div>
                        <div class="ms-thread-name" id="threadName">—</div>
                        <div class="ms-thread-sub">
                            <span class="ms-online-dot" aria-hidden="true"></span>
                            <span id="threadEmail" class="ms-thread-email">—</span>
                        </div>
                    </div>
                </div>

                <div class="ms-thread-actions">
                    <button class="ms-hbtn" id="btnSound" title="Son activé" aria-pressed="true">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" id="soundIconOn"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" id="soundIconOff" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/></svg>
                    </button>
                    <button class="ms-hbtn" id="btnArchive" title="Archiver" aria-pressed="false">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                    </button>
                    <button class="ms-hbtn" id="btnInfo" title="Fiche client">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Zone messages --}}
            <div class="ms-messages" id="msMessages" aria-live="polite" aria-label="Messages"></div>

            {{-- Zone de rédaction --}}
            <div class="ms-compose" id="msCompose">
                <div class="ms-compose-inner">
                    <textarea
                        id="msInput"
                        rows="1"
                        placeholder="Répondre au client…"
                        maxlength="2000"
                        aria-label="Rédiger une réponse"
                        aria-multiline="true"
                    ></textarea>
                    <div class="ms-compose-meta">
                        <span class="ms-ccount"><span id="msCount">0</span>/2000</span>
                        <span class="ms-hint">↵ Envoyer · ⇧↵ Nouvelle ligne</span>
                    </div>
                </div>
                <button class="ms-send" id="msSend" aria-label="Envoyer le message">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 2 11 13M22 2 15 22l-4-9-9-4 20-7z"/></svg>
                </button>
            </div>

            {{-- Alerte anti-spam --}}
            <div class="ms-warn" id="msWarn" style="display:none" role="alert" aria-live="assertive">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <span id="msWarnTxt"></span>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     FICHE CLIENT
══════════════════════════════════════════ --}}
<div class="ms-info-panel" id="msInfoPanel" role="dialog" aria-modal="true" aria-label="Fiche client">
    <div class="ms-info-header">
        <span>Fiche client</span>
        <button id="msInfoClose" aria-label="Fermer la fiche">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="ms-info-content">
        <div class="ms-info-av" id="infoAv" aria-hidden="true"></div>
        <div class="ms-info-name" id="infoName">—</div>
        <div class="ms-info-role">Client</div>
        <div class="ms-info-rows">
            <div class="ms-info-row">
                <div class="ms-info-dot" style="background:#2563a8" aria-hidden="true"></div>
                <div>
                    <div class="ms-info-lbl">Email</div>
                    <div class="ms-info-val" id="infoEmail">—</div>
                </div>
            </div>
            <div class="ms-info-row">
                <div class="ms-info-dot" style="background:#2d9c4f" aria-hidden="true"></div>
                <div>
                    <div class="ms-info-lbl">Téléphone</div>
                    <div class="ms-info-val" id="infoPhone">—</div>
                </div>
            </div>
            <div class="ms-info-row last">
                <div class="ms-info-dot" style="background:#e8722a" aria-hidden="true"></div>
                <div>
                    <div class="ms-info-lbl">Statut</div>
                    <div class="ms-tag" id="infoStatus" style="margin-top:5px">—</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ms-veil" id="msVeil" aria-hidden="true"></div>

{{-- ══════════════════════════════════════════
     TOAST NOTIFICATION PREMIUM
══════════════════════════════════════════ --}}
<div class="ms-toast" id="msToast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="ms-toast-glow" aria-hidden="true"></div>
    <div class="ms-toast-body">
        <div class="ms-toast-av" id="toastAv" aria-hidden="true"></div>
        <div class="ms-toast-text">
            <div class="ms-toast-header">
                <span class="ms-toast-label">Nouveau message</span>
                <span class="ms-toast-time" id="toastTime"></span>
            </div>
            <div class="ms-toast-name" id="toastName"></div>
            <div class="ms-toast-msg" id="toastMsg"></div>
        </div>
        <button class="ms-toast-close" id="toastClose" aria-label="Fermer la notification">
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="ms-toast-progress" id="toastProgress" aria-hidden="true"></div>
</div>

{{-- ══════════════════════════════════════════
     STYLES
══════════════════════════════════════════ --}}
<style>
/* ────── Design tokens ────── */
:root {
    --ms-blue:    #2563a8;
    --ms-blue2:   #3b82c4;
    --ms-pink:    #d63384;
    --ms-orange:  #e8722a;
    --ms-green:   #2d9c4f;
    --ms-teal:    #1a8fa0;
    --ms-panel-w: 300px;
    --ms-radius:  20px;
    --ms-shell-h: calc(100vh - 252px);
    --ms-trans:   .2s cubic-bezier(.4,0,.2,1);
}

/* ────── Keyframes ────── */
@keyframes ms-up    { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
@keyframes ms-slide { from{opacity:0;transform:translateX(80px) scale(.95)} to{opacity:1;transform:none} }
@keyframes ms-bub   { from{opacity:0;transform:scale(.88) translateY(10px)} to{opacity:1;transform:none} }
@keyframes ms-prog  { from{width:100%} to{width:0} }
@keyframes ms-pulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.65);opacity:.45} }
@keyframes ms-orb   { 0%,100%{transform:scale(1) translate(0,0)} 50%{transform:scale(1.18) translate(10px,-8px)} }
@keyframes ms-spin  { to{transform:rotate(360deg)} }
@keyframes ms-dot   { 0%,80%,100%{transform:scale(0);opacity:.35} 40%{transform:scale(1);opacity:1} }
@keyframes ms-flash { 0%,100%{background:transparent} 50%{background:rgba(37,99,168,.07)} }
@keyframes ms-shake { 0%,100%{transform:translateX(0)} 20%,60%{transform:translateX(-3px)} 40%,80%{transform:translateX(3px)} }
@keyframes ms-toast-in { from{opacity:0;transform:translateX(100%) scale(.94)} to{opacity:1;transform:none} }

/* ────── Permission banner ────── */
.ms-perm {
    background: rgba(37,99,168,.07);
    border: 1px solid rgba(37,99,168,.2);
    border-radius: 14px; padding: 0;
    margin-bottom: 14px;
    overflow: hidden;
    animation: ms-up .3s ease;
}

.ms-perm-inner {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 16px; flex-wrap: wrap;
    font-size: 12.5px; color: var(--ms-blue); font-weight: 500;
}

.ms-perm-inner svg { flex-shrink: 0; }

.ms-perm-actions { display: flex; gap: 6px; margin-left: auto; }

.ms-perm-actions button {
    padding: 5px 14px; border-radius: 8px;
    border: none; font-size: 12px; font-weight: 600;
    cursor: pointer; font-family: 'DM Sans',sans-serif;
    transition: all .14s;
}

#permAllow   { background: var(--ms-blue); color: white; }
#permAllow:hover { background: var(--ms-blue2); }
#permDismiss { background: transparent; color: var(--text-muted,#94a3b8); }

/* ────── Stats bar ────── */
.ms-stats {
    display: flex; align-items: center;
    gap: 10px; margin-bottom: 14px; flex-wrap: wrap;
}

.ms-stat {
    display: flex; align-items: center; gap: 11px;
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 14px; padding: 11px 16px;
    flex: 1; min-width: 110px;
    position: relative; overflow: hidden;
    transition: transform var(--ms-trans), box-shadow var(--ms-trans);
    cursor: default;
}

.ms-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.07); }
.ms-stat.is-alert { border-color: rgba(232,114,42,.4); background: rgba(232,114,42,.04); }

.ms-stat-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: var(--ib); color: var(--ic);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.ms-stat-n { font-family: 'Outfit',sans-serif; font-size: 22px; font-weight: 800; color: var(--text-primary,#0f1923); line-height: 1; }
.ms-stat-l { font-size: 10.5px; color: var(--text-muted,#94a3b8); font-weight: 500; margin-top: 2px; }

.ms-alert-ring {
    position: absolute; top: 50%; right: 12px; transform: translateY(-50%);
    width: 10px; height: 10px; border-radius: 50%;
    background: var(--ms-orange);
    animation: ms-pulse 1.3s ease-in-out infinite;
}

/* Realtime badge */
.ms-rt-badge {
    display: flex; align-items: center; gap: 7px;
    padding: 9px 16px; border-radius: 20px;
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    font-size: 12px; font-weight: 600; color: var(--text-muted,#94a3b8);
    white-space: nowrap; margin-left: auto; flex-shrink: 0;
    transition: all .3s ease;
}

.ms-rt-dot { width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; transition: background .3s; flex-shrink: 0; }

.ms-rt-badge.on  { color: var(--ms-green); border-color: rgba(45,156,79,.3); background: rgba(45,156,79,.04); }
.ms-rt-badge.on .ms-rt-dot { background: var(--ms-green); box-shadow: 0 0 7px rgba(45,156,79,.7); animation: ms-pulse 2s ease-in-out infinite; }
.ms-rt-badge.off { color: #ef4444; border-color: rgba(239,68,68,.3); }
.ms-rt-badge.off .ms-rt-dot { background: #ef4444; }

/* ────── Shell ────── */
.ms-shell {
    display: flex;
    height: var(--ms-shell-h);
    min-height: 460px;
    border-radius: var(--ms-radius);
    overflow: hidden;
    border: 1px solid var(--border-color,#e2e8f0);
    box-shadow: 0 8px 32px rgba(0,0,0,.08);
    background: var(--bg-surface,#fff);
    animation: ms-up .3s ease;
    position: relative;
}

/* ────── Panel (sidebar gauche) ────── */
.ms-panel {
    width: var(--ms-panel-w);
    flex-shrink: 0;
    display: flex; flex-direction: column;
    background: var(--bg-surface,#fff);
    border-right: 1px solid var(--border-color,#e2e8f0);
    z-index: 2;
    /* TRANSITION pour mobile */
    transition: transform var(--ms-trans);
}

.ms-panel-head {
    padding: 16px 18px 12px;
    border-bottom: 1px solid var(--border-subtle,#eef2f7);
    background: var(--bg-surface-2,#f8fafc);
    flex-shrink: 0;
}

.ms-panel-title {
    font-family: 'Outfit',sans-serif;
    font-size: 15px; font-weight: 800;
    color: var(--text-primary,#0f1923);
    display: flex; align-items: center; gap: 8px;
}

.ms-panel-badge {
    background: linear-gradient(135deg, var(--ms-orange), var(--ms-pink));
    color: white; font-size: 10px; font-weight: 800;
    padding: 2px 9px; border-radius: 20px;
    animation: ms-pulse 2s infinite;
    box-shadow: 0 0 10px rgba(232,114,42,.35);
}

/* Tabs */
.ms-tabs { display: flex; gap: 3px; padding: 8px 10px 4px; flex-shrink: 0; }

.ms-tab {
    flex: 1; padding: 7px 8px;
    border: none; border-radius: 10px;
    font-size: 12px; font-weight: 500; cursor: pointer;
    color: var(--text-muted,#94a3b8);
    background: transparent;
    font-family: 'DM Sans',sans-serif;
    transition: all .15s ease;
    display: flex; align-items: center; justify-content: center; gap: 5px;
}

.ms-tab em {
    background: var(--ms-orange); color: white;
    font-style: normal; font-size: 9.5px; font-weight: 700;
    min-width: 16px; height: 16px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center; padding: 0 4px;
}

.ms-tab em.muted { background: var(--text-muted,#94a3b8); }
.ms-tab:hover  { background: var(--bg-surface-2,#f8fafc); color: var(--text-secondary,#4a5568); }
.ms-tab.active { background: rgba(37,99,168,.1); color: var(--ms-blue); font-weight: 600; }

/* Conv list */
.ms-conv-list { flex: 1; overflow-y: auto; padding: 4px 6px; }
.ms-conv-list::-webkit-scrollbar { width: 3px; }
.ms-conv-list::-webkit-scrollbar-thumb { background: var(--border-color,#e2e8f0); border-radius: 3px; }
.ms-conv-list::-webkit-scrollbar-thumb:hover { background: var(--ms-blue); }

/* Conv item */
.ms-conv {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px; border-radius: 12px;
    cursor: pointer; margin-bottom: 2px;
    border: 1px solid transparent;
    transition: all .15s ease;
    position: relative;
    outline: none;
}

.ms-conv:hover        { background: var(--bg-surface-2,#f8fafc); }
.ms-conv:focus-visible { outline: 2px solid var(--ms-blue); outline-offset: -2px; }
.ms-conv.sel          { background: rgba(37,99,168,.07); border-color: rgba(37,99,168,.2); }
.ms-conv.unread       { background: rgba(232,114,42,.05); border-color: rgba(232,114,42,.18); }
.ms-conv.new-flash    { animation: ms-flash .55s ease; }

.ms-conv-av {
    width: 44px; height: 44px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 14px; font-weight: 700;
    font-family: 'Outfit',sans-serif; flex-shrink: 0;
    position: relative;
    box-shadow: 0 3px 10px rgba(0,0,0,.14);
    transition: transform .15s ease;
}

.ms-conv:hover .ms-conv-av { transform: scale(1.05); }

.ms-conv-badge {
    position: absolute; top: -3px; right: -3px;
    width: 12px; height: 12px; border-radius: 50%;
    background: var(--ms-orange);
    border: 2.5px solid var(--bg-surface,#fff);
    animation: ms-pulse 1.4s ease-in-out infinite;
}

.ms-conv-info { flex: 1; min-width: 0; }
.ms-conv-top  { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px; }
.ms-conv-name { font-size: 13px; font-weight: 600; color: var(--text-primary,#0f1923); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 148px; }
.ms-conv-time { font-size: 10px; color: var(--text-muted,#94a3b8); flex-shrink: 0; }
.ms-conv-preview { font-size: 11.5px; color: var(--text-muted,#94a3b8); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 5px; line-height: 1.4; }

/* Tags */
.ms-tag { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 8px; display: inline-flex; align-items: center; gap: 3px; }
.ms-tag.wait { background: rgba(232,114,42,.12); color: var(--ms-orange); }
.ms-tag.ok   { background: rgba(45,156,79,.12);  color: var(--ms-green); }
.ms-tag.arc  { background: var(--bg-surface-2,#f8fafc); color: var(--text-muted,#94a3b8); }

/* Empty */
.ms-empty-list { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 60px 20px; gap: 8px; color: var(--text-muted,#94a3b8); }
.ms-empty-list svg { opacity: .3; }
.ms-empty-list p { font-size: 14px; font-weight: 600; color: var(--text-secondary,#4a5568); }
.ms-empty-list span { font-size: 12px; }

/* ────── Chat zone ────── */
.ms-chat {
    flex: 1; display: flex; flex-direction: column;
    background: var(--bg-base,#f0f4f8);
    background-image:
        radial-gradient(circle at 18px 18px, rgba(37,99,168,.02) 1px, transparent 1px),
        radial-gradient(circle at 54px 54px, rgba(45,156,79,.015) 1px, transparent 1px);
    background-size: 36px 36px;
    min-width: 0; position: relative;
}

/* Idle */
.ms-idle {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 10px; position: relative; overflow: hidden;
}

.ms-idle-orbs { position: absolute; inset: 0; pointer-events: none; }
.ms-orb { position: absolute; border-radius: 50%; filter: blur(50px); animation: ms-orb 7s ease-in-out infinite; }
.ms-orb.o1 { width: 220px; height: 220px; background: rgba(37,99,168,.07);  top: 8%; left: 5%; animation-delay: 0s; }
.ms-orb.o2 { width: 170px; height: 170px; background: rgba(45,156,79,.06);  bottom: 12%; right: 8%; animation-delay: 2.5s; }
.ms-orb.o3 { width: 130px; height: 130px; background: rgba(232,114,42,.05); top: 50%; left: 42%; animation-delay: 5s; }

.ms-idle-icon {
    width: 72px; height: 72px; border-radius: 50%;
    background: var(--bg-surface,#fff);
    box-shadow: 0 10px 28px rgba(0,0,0,.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted,#94a3b8); z-index: 1;
    margin-bottom: 4px;
}

.ms-idle h3 { font-family: 'Outfit',sans-serif; font-size: 18px; font-weight: 700; color: var(--text-secondary,#4a5568); z-index: 1; }
.ms-idle p  { font-size: 13px; color: var(--text-muted,#94a3b8); z-index: 1; }

.ms-idle-alert {
    display: inline-flex; align-items: center; gap: 5px;
    color: var(--ms-orange) !important;
    background: rgba(232,114,42,.1);
    padding: 6px 18px; border-radius: 20px;
    font-weight: 600 !important; font-size: 13px !important;
    border: 1px solid rgba(232,114,42,.2);
}

/* Thread */
.ms-thread { display: flex; flex-direction: column; height: 100%; }

/* Thread head */
.ms-thread-head {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 18px;
    background: var(--bg-surface,#fff);
    border-bottom: 1px solid var(--border-color,#e2e8f0);
    flex-shrink: 0;
    box-shadow: 0 1px 6px rgba(0,0,0,.04);
}

/* ── BOUTON RETOUR MOBILE ──
   Caché sur desktop, visible sur mobile via CSS.
   Toujours présent dans le DOM pour éviter tout bug JS.
*/
.ms-back {
    width: 34px; height: 34px;
    background: var(--bg-surface-2,#f8fafc);
    border: 1.5px solid var(--border-color,#e2e8f0);
    border-radius: 10px; cursor: pointer;
    color: var(--text-secondary,#4a5568);
    display: none;           /* caché par défaut (desktop) */
    align-items: center; justify-content: center;
    flex-shrink: 0; transition: all .15s;
}

.ms-back:hover { background: var(--border-color,#e2e8f0); color: var(--text-primary,#0f1923); }

.ms-thread-contact { display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0; }

.ms-thread-av {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 14px; font-weight: 700;
    flex-shrink: 0; font-family: 'Outfit',sans-serif;
    box-shadow: 0 3px 10px rgba(0,0,0,.15);
}

.ms-thread-name { font-family: 'Outfit',sans-serif; font-size: 14px; font-weight: 700; color: var(--text-primary,#0f1923); }
.ms-thread-sub  { display: flex; align-items: center; gap: 5px; font-size: 11.5px; color: var(--text-muted,#94a3b8); }
.ms-thread-email { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }

.ms-online-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--ms-green);
    box-shadow: 0 0 6px rgba(45,156,79,.6);
    animation: ms-pulse 2.5s ease-in-out infinite;
    flex-shrink: 0;
}

.ms-thread-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

.ms-hbtn {
    width: 32px; height: 32px; border-radius: 9px;
    background: var(--bg-surface-2,#f8fafc);
    border: 1.5px solid var(--border-color,#e2e8f0);
    cursor: pointer; color: var(--text-secondary,#4a5568);
    display: flex; align-items: center; justify-content: center;
    transition: all .15s ease;
}

.ms-hbtn:hover   { background: rgba(37,99,168,.1); color: var(--ms-blue); border-color: rgba(37,99,168,.25); }
.ms-hbtn.s-off   { background: rgba(239,68,68,.08); color: #ef4444; border-color: rgba(239,68,68,.2); }
.ms-hbtn.arc-on  { background: rgba(239,68,68,.08); color: #ef4444; border-color: rgba(239,68,68,.2); }

/* Messages */
.ms-messages {
    flex: 1; overflow-y: auto; padding: 18px 20px;
    display: flex; flex-direction: column; gap: 8px;
    scroll-behavior: smooth;
}

.ms-messages::-webkit-scrollbar { width: 4px; }
.ms-messages::-webkit-scrollbar-thumb { background: var(--border-color,#e2e8f0); border-radius: 4px; }
.ms-messages::-webkit-scrollbar-thumb:hover { background: var(--ms-blue2); }

/* Séparateur date */
.ms-date-sep { display: flex; align-items: center; gap: 10px; margin: 4px 0; opacity: .55; }
.ms-date-sep::before, .ms-date-sep::after { content:''; flex:1; height:1px; background:var(--border-subtle,#eef2f7); }
.ms-date-sep span { font-size: 10.5px; font-weight: 600; color: var(--text-muted,#94a3b8); white-space: nowrap; }

/* Bulles */
.ms-row-l { display: flex; justify-content: flex-start; animation: ms-bub .22s ease; }
.ms-row-r { display: flex; justify-content: flex-end;   animation: ms-bub .22s ease; }
.ms-row-r.optimistic .ms-bub-r { opacity: .6; }

.ms-bub-l {
    background: var(--bg-surface,#fff);
    border-radius: 4px 18px 18px 18px;
    padding: 10px 14px; max-width: 62%;
    box-shadow: 0 1px 5px rgba(0,0,0,.05);
    border: 1px solid var(--border-subtle,#eef2f7);
}

.ms-bub-r {
    background: linear-gradient(140deg, var(--ms-blue), var(--ms-blue2));
    border-radius: 18px 4px 18px 18px;
    padding: 10px 14px; max-width: 62%;
    box-shadow: 0 4px 14px rgba(37,99,168,.28);
}

.ms-bub-r.err { background: linear-gradient(135deg,#dc2626,#ef4444); }

.ms-sender   { font-size: 10.5px; font-weight: 700; color: var(--ms-blue); margin-bottom: 4px; }
.ms-sender-r { font-size: 10.5px; font-weight: 700; color: rgba(255,255,255,.6); margin-bottom: 4px; }
.ms-txt      { font-size: 13.5px; color: var(--text-primary,#0f1923); line-height: 1.5; word-break: break-word; }
.ms-txt-r    { font-size: 13.5px; color: white; line-height: 1.5; word-break: break-word; }
.ms-time     { font-size: 9.5px; color: var(--text-muted,#94a3b8); margin-top: 5px; text-align: right; }
.ms-time-r   { font-size: 9.5px; color: rgba(255,255,255,.5); margin-top: 5px; display: flex; align-items: center; justify-content: flex-end; gap: 3px; }

/* Loader */
.ms-loader { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 60px 20px; color: var(--text-muted,#94a3b8); font-size: 13px; }
.ms-loader svg { animation: ms-spin .8s linear infinite; }

/* Zone de rédaction */
.ms-compose {
    display: flex; align-items: flex-end; gap: 10px;
    padding: 12px 16px;
    background: var(--bg-surface,#fff);
    border-top: 2px solid var(--border-color,#e2e8f0);
    flex-shrink: 0;
}

.ms-compose-inner { flex: 1; position: relative; }

.ms-compose-inner textarea {
    width: 100%;
    background: var(--bg-surface-2,#f8fafc);
    border: 1.5px solid var(--border-color,#e2e8f0);
    border-radius: 14px; padding: 11px 14px 28px;
    font-size: 13.5px; line-height: 1.5; resize: none; outline: none;
    color: var(--text-primary,#0f1923);
    max-height: 140px; min-height: 46px;
    font-family: 'DM Sans',sans-serif;
    transition: border-color .18s, box-shadow .18s, background .18s;
    box-shadow: none !important;
}

.ms-compose-inner textarea:focus {
    border-color: var(--ms-blue);
    background: var(--bg-surface,#fff);
    box-shadow: 0 0 0 3px rgba(37,99,168,.1) !important;
}

.ms-compose-inner textarea::placeholder { color: var(--text-muted,#94a3b8); }
.ms-compose-inner textarea:disabled     { opacity: .4; cursor: not-allowed; }

.ms-compose-meta {
    position: absolute; bottom: 8px; left: 12px; right: 12px;
    display: flex; align-items: center; justify-content: space-between;
    pointer-events: none;
}

.ms-ccount { font-size: 10px; color: var(--text-muted,#94a3b8); }
.ms-hint   { font-size: 10px; color: var(--text-muted,#94a3b8); opacity: .6; }

.ms-send {
    width: 46px; height: 46px; border-radius: 14px;
    background: linear-gradient(140deg, var(--ms-blue), var(--ms-blue2));
    border: none; cursor: pointer; color: white; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: all .18s ease;
    box-shadow: 0 4px 14px rgba(37,99,168,.35);
}

.ms-send:hover   { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(37,99,168,.45); }
.ms-send:active  { transform: scale(.95); }
.ms-send:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }
.ms-send.loading  { animation: ms-pulse .75s ease-in-out infinite; }

/* Alerte anti-spam */
.ms-warn {
    display: flex; align-items: center; gap: 7px;
    margin: 0 16px 8px; padding: 8px 14px;
    border-radius: 9px; font-size: 12px; font-weight: 500;
    background: rgba(232,114,42,.1); border: 1px solid rgba(232,114,42,.25);
    color: var(--ms-orange); flex-shrink: 0;
    animation: ms-shake .4s ease;
}

/* ────── Info panel ────── */
.ms-info-panel {
    position: fixed; top: 0; right: 0; height: 100%;
    width: 300px;
    background: var(--bg-surface,#fff);
    border-left: 1px solid var(--border-color,#e2e8f0);
    transform: translateX(100%);
    transition: transform .27s cubic-bezier(.4,0,.2,1);
    z-index: 600; display: flex; flex-direction: column;
    box-shadow: -8px 0 32px rgba(0,0,0,.09);
}

.ms-info-panel.open { transform: translateX(0); }

.ms-veil {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.22); z-index: 599;
    backdrop-filter: blur(2px);
}

.ms-veil.open { display: block; }

.ms-info-header {
    background: linear-gradient(135deg, var(--ms-blue), var(--ms-blue2));
    padding: 18px 20px; color: white;
    display: flex; justify-content: space-between; align-items: center;
    font-family: 'Outfit',sans-serif; font-size: 15px; font-weight: 700;
    flex-shrink: 0;
}

.ms-info-header button {
    width: 28px; height: 28px; border-radius: 8px;
    background: rgba(255,255,255,.15); border: none;
    color: white; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .14s;
}

.ms-info-header button:hover { background: rgba(255,255,255,.28); }

.ms-info-content {
    padding: 24px 20px;
    display: flex; flex-direction: column; align-items: center;
    overflow-y: auto; flex: 1;
}

.ms-info-av { width: 70px; height: 70px; border-radius: 18px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 700; font-family: 'Outfit',sans-serif; margin-bottom: 12px; box-shadow: 0 6px 20px rgba(0,0,0,.13); }
.ms-info-name { font-family: 'Outfit',sans-serif; font-size: 17px; font-weight: 800; color: var(--text-primary,#0f1923); }
.ms-info-role { font-size: 11.5px; color: var(--text-muted,#94a3b8); margin-top: 3px; margin-bottom: 18px; }

.ms-info-rows { width: 100%; }
.ms-info-row  { display: flex; align-items: flex-start; gap: 12px; padding: 11px 0; border-bottom: 1px solid var(--border-subtle,#eef2f7); }
.ms-info-row.last { border-bottom: none; }

.ms-info-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
.ms-info-lbl { font-size: 10px; color: var(--text-muted,#94a3b8); text-transform: uppercase; letter-spacing: .5px; font-weight: 700; margin-bottom: 3px; }
.ms-info-val { font-size: 13px; color: var(--text-primary,#0f1923); font-weight: 500; word-break: break-all; }

/* ────── Toast premium ────── */
.ms-toast {
    position: fixed; bottom: 20px; right: 20px;
    width: 340px; max-width: calc(100vw - 28px);
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 18px;
    box-shadow: 0 20px 50px rgba(0,0,0,.15), 0 2px 8px rgba(0,0,0,.06);
    overflow: hidden; z-index: 2000; pointer-events: none;
    opacity: 0;
    transform: translateX(calc(100% + 28px));
    transition: transform .4s cubic-bezier(.34,1.56,.64,1), opacity .3s ease;
}

.ms-toast.show {
    opacity: 1; pointer-events: all;
    transform: translateX(0);
}

.ms-toast-glow {
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--ms-blue), var(--ms-pink), var(--ms-orange));
}

.ms-toast-body {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 14px 10px;
}

.ms-toast-av {
    width: 42px; height: 42px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 15px; font-weight: 700;
    flex-shrink: 0; font-family: 'Outfit',sans-serif;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}

.ms-toast-text { flex: 1; min-width: 0; }

.ms-toast-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 2px;
}

.ms-toast-label {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; color: var(--ms-blue);
    display: flex; align-items: center; gap: 4px;
}

.ms-toast-label::before {
    content: '';
    display: inline-block; width: 6px; height: 6px;
    border-radius: 50%; background: var(--ms-blue);
    animation: ms-pulse 1.5s infinite;
}

.ms-toast-time { font-size: 10px; color: var(--text-muted,#94a3b8); }
.ms-toast-name { font-size: 13.5px; font-weight: 700; color: var(--text-primary,#0f1923); font-family: 'Outfit',sans-serif; }
.ms-toast-msg  { font-size: 12px; color: var(--text-secondary,#4a5568); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }

.ms-toast-close {
    width: 26px; height: 26px; border-radius: 8px; flex-shrink: 0;
    background: var(--bg-surface-2,#f8fafc);
    border: 1px solid var(--border-color,#e2e8f0);
    cursor: pointer; color: var(--text-muted,#94a3b8);
    display: flex; align-items: center; justify-content: center;
    margin-top: 2px;
    transition: all .13s;
}

.ms-toast-close:hover { background: var(--border-color,#e2e8f0); color: var(--text-primary,#0f1923); }

.ms-toast-progress { height: 3px; background: linear-gradient(90deg, var(--ms-blue), var(--ms-blue2)); }
.ms-toast-progress.go { animation: ms-prog 5s linear forwards; }

/* ────── RESPONSIVE MOBILE ────── */
@media (max-width: 768px) {
    :root { --ms-panel-w: 100%; --ms-shell-h: calc(100vh - 200px); }

    .ms-shell { border-radius: 14px; }

    /* Sur mobile le panel RECOUVRE toute la zone */
    .ms-panel {
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 100%; height: 100%;
        z-index: 10;
        /* état VISIBLE par défaut (liste conversations) */
    }

    /* Quand on sélectionne une conv, on cache le panel */
    .ms-panel.slide-out {
        transform: translateX(-100%);
    }

    /* ── BOUTON RETOUR : visible uniquement sur mobile ── */
    .ms-back {
        display: flex !important;
    }

    .ms-info-panel { width: 100%; }
    .ms-hint { display: none; }

    .ms-toast {
        bottom: 10px; right: 10px; left: 10px;
        width: auto; max-width: none;
    }

    .ms-thread-email { max-width: 130px; }
}

@media (max-width: 420px) {
    .ms-stat-n { font-size: 19px; }
    .ms-stats  { gap: 7px; }
    .ms-stat   { padding: 9px 12px; min-width: 95px; }
    .ms-bub-l, .ms-bub-r { max-width: 85%; }
}
</style>

{{-- ══════════════════════════════════════════
     JAVASCRIPT — Optimisé, robuste, pro
══════════════════════════════════════════ --}}
<script>
(function () {
'use strict';

/* ══════════════════════════
   ÉTAT GLOBAL
══════════════════════════ */
let cEmail = '', cArc = false, cInit = '', cC1 = '', cC2 = '', cPhone = '';
const arcSet = new Set();
const newSet = new Set();

let soundOn    = true;
let browserNot = false;
let audioCtx   = null;

/* Anti-spam */
const COOLDOWN = 1400, BURST = 5;
let lastSend = 0, sendCnt = 0, cntReset = null, inFlight = false;

/* Refresh debounce */
let refreshing  = false;
let refreshPend = false;

/* Toast timer */
let toastTimer = null;

/* ══════════════════════════
   UTILS / SÉCURITÉ
══════════════════════════ */
function esc(str) {
    if (str === null || str === undefined) return '';
    const d = document.createElement('div');
    d.textContent = String(str);
    return d.innerHTML;
}

function clean(str, max = 2000) {
    return String(str || '')
        .replace(/<[^>]*>/g, '')
        .replace(/[\x00-\x08\x0B\x0C\x0E-\x1F]/g, '')
        .substring(0, max)
        .trim();
}

function validMsg(s) {
    const t = clean(s);
    return t.length >= 1 && t.length <= 2000;
}

function cssEsc(email) {
    /* Échappe manuellement pour les sélecteurs CSS */
    try { return CSS.escape(email); }
    catch { return email.replace(/[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~]/g, '\\$&'); }
}

function fmtTime(d) {
    if (!d) return '';
    try { return new Date(d).toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' }); }
    catch { return ''; }
}

function fmtDate(d) {
    if (!d) return '';
    try {
        const dt  = new Date(d);
        const now = new Date();
        const diff = (now - dt) / 86400000;
        if (diff < 1) return "Aujourd'hui";
        if (diff < 2) return "Hier";
        return dt.toLocaleDateString('fr-FR', { day:'2-digit', month:'long' });
    } catch { return ''; }
}

function nowHHMM() {
    return new Date().toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' });
}

/* ══════════════════════════
   AUDIO
══════════════════════════ */
function initAudio() {
    if (audioCtx) return;
    try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch {}
}

document.addEventListener('click', initAudio, { once: true });

function playSound(type) {
    if (!soundOn) return;
    try {
        initAudio();
        if (!audioCtx) return;
        if (audioCtx.state === 'suspended') audioCtx.resume();
        const t = audioCtx.currentTime;

        if (type === 'receive') {
            [[880, t,      0.16, t + 0.24],
             [1100, t + 0.14, 0.11, t + 0.36]].forEach(([f,s,g,e]) => {
                const o = audioCtx.createOscillator();
                const gn = audioCtx.createGain();
                o.connect(gn); gn.connect(audioCtx.destination);
                o.type = 'sine'; o.frequency.value = f;
                gn.gain.setValueAtTime(0, s);
                gn.gain.linearRampToValueAtTime(g, s + 0.03);
                gn.gain.exponentialRampToValueAtTime(0.0001, e);
                o.start(s); o.stop(e + 0.05);
            });
        } else {
            /* Son envoi */
            const o = audioCtx.createOscillator();
            const gn = audioCtx.createGain();
            o.connect(gn); gn.connect(audioCtx.destination);
            o.type = 'sine'; o.frequency.value = 620;
            gn.gain.setValueAtTime(0, t);
            gn.gain.linearRampToValueAtTime(0.07, t + 0.02);
            gn.gain.exponentialRampToValueAtTime(0.0001, t + 0.13);
            o.start(t); o.stop(t + 0.14);
        }
    } catch {}
}

/* ══════════════════════════
   TOAST NOTIFICATION
══════════════════════════ */
function showToast(name, msg, c1, c2) {
    const el = document.getElementById('msToast');
    if (!el) return;

    document.getElementById('toastName').textContent = String(name || '').substring(0, 40);
    document.getElementById('toastMsg').textContent  = String(msg  || '').substring(0, 80);
    document.getElementById('toastTime').textContent = nowHHMM();

    const av = document.getElementById('toastAv');
    if (av) {
        av.textContent      = String(name || '?').substring(0, 2).toUpperCase();
        av.style.background = linear-gradient(140deg, ${c1}, ${c2});
    }

    const prog = document.getElementById('toastProgress');
    if (prog) { prog.className = 'ms-toast-progress'; void prog.offsetWidth; prog.className = 'ms-toast-progress go'; }

    el.classList.add('show');
    playSound('receive');

    clearTimeout(toastTimer);
    toastTimer = setTimeout(hideToast, 5500);
}

function hideToast() {
    document.getElementById('msToast')?.classList.remove('show');
}

window.hideToast = hideToast;

document.getElementById('toastClose')?.addEventListener('click', hideToast);

/* Clic sur le toast → ouvrir la conversation */
document.getElementById('msToast')?.addEventListener('click', function(e) {
    if (e.target.closest('.ms-toast-close')) return;
    /* Trouver la conv correspondante et la sélectionner */
    const name = document.getElementById('toastName')?.textContent;
    if (!name) return;
    const conv = document.querySelector(.ms-conv[data-name="${CSS.escape ? CSS.escape(name) : name}"]);
    conv?.click();
    hideToast();
});

/* ══════════════════════════
   NOTIFICATIONS NAVIGATEUR
══════════════════════════ */
function initBrowserNotif() {
    if (!('Notification' in window)) return;
    if (Notification.permission === 'granted') { browserNot = true; return; }
    if (Notification.permission !== 'denied') {
        const banner = document.getElementById('msPerm');
        if (banner) banner.style.display = 'block';
    }
}

document.getElementById('permAllow')?.addEventListener('click', () => {
    Notification.requestPermission().then(p => {
        if (p === 'granted') browserNot = true;
        document.getElementById('msPerm')?.remove();
    });
});

document.getElementById('permDismiss')?.addEventListener('click', () => {
    document.getElementById('msPerm')?.remove();
});

function notifBrowser(name, msg) {
    if (!browserNot || document.hasFocus()) return;
    try { new Notification(💬 ${name}, { body: String(msg || '').substring(0, 100), icon: '/images/logo.jpg' }); }
    catch {}
}

/* ══════════════════════════
   REALTIME BADGE
══════════════════════════ */
function setRT(state) {
    const b = document.getElementById('rtBadge');
    const l = document.getElementById('rtLabel');
    if (!b || !l) return;
    b.className = ms-rt-badge ${state === 'connected' ? 'on' : state === 'disconnected' ? 'off' : ''};
    l.textContent = { connected:'Temps réel', disconnected:'Déconnecté', connecting:'Connexion…' }[state] || state;
}

/* ══════════════════════════
   ANTI-SPAM
══════════════════════════ */
function warn(txt) {
    const el  = document.getElementById('msWarn');
    const txt2 = document.getElementById('msWarnTxt');
    if (!el || !txt2) return;
    txt2.textContent = txt;
    el.style.display = 'flex';
    /* Re-trigger animation */
    el.style.animation = 'none'; void el.offsetWidth; el.style.animation = '';
    clearTimeout(el._t);
    el._t = setTimeout(() => { el.style.display = 'none'; }, 4000);
}

function checkRL() {
    const now = Date.now();
    if (!cntReset) cntReset = setTimeout(() => { sendCnt = 0; cntReset = null; }, 30000);
    if (now - lastSend < COOLDOWN) {
        warn(Attendez ${((COOLDOWN - (now - lastSend)) / 1000).toFixed(1)}s avant de renvoyer.);
        return false;
    }
    if (sendCnt >= BURST) {
        warn('Trop de messages en rafale. Patientez 30 secondes.');
        return false;
    }
    return true;
}

/* ══════════════════════════
   UNREAD MARKERS
══════════════════════════ */
function markNew(email) {
    newSet.add(email);
    const it = document.querySelector(.ms-conv[data-email="${cssEsc(email)}"]);
    if (!it) return;
    it.classList.add('new-flash');
    if (!it.querySelector('.ms-conv-badge')) {
        const b = document.createElement('div');
        b.className = 'ms-conv-badge';
        it.querySelector('.ms-conv-av')?.appendChild(b);
    }
}

function clearNew(email) {
    newSet.delete(email);
    const it = document.querySelector(.ms-conv[data-email="${cssEsc(email)}"]);
    if (!it) return;
    it.querySelector('.ms-conv-badge')?.remove();
    it.classList.remove('unread', 'new-flash');
}

/* ══════════════════════════
   CHARGER MESSAGES
══════════════════════════ */
async function loadConv(email) {
    if (!email) return;
    const box = document.getElementById('msMessages');
    if (!box) return;

    box.innerHTML = `
        <div class="ms-loader">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Chargement…
        </div>`;

    try {
        const r = await fetch(/admin/messages/conversation/${encodeURIComponent(email)}, {
            cache: 'no-store',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });

        if (!r.ok) throw new Error(HTTP ${r.status});
        const msgs = await r.json();

        if (!Array.isArray(msgs) || msgs.length === 0) {
            box.innerHTML = <div style="text-align:center;padding:60px;font-size:13px;color:var(--text-muted)">Aucun message dans cette conversation</div>;
            return;
        }

        /* Téléphone */
        const tel = msgs[0]?.telephone || msgs[0]?.phone || '';
        if (tel) {
            cPhone = tel;
            const ph = document.getElementById('infoPhone');
            if (ph) ph.textContent = tel;
        }

        renderMsgs(msgs, box);

    } catch(e) {
        box.innerHTML = <div style="text-align:center;padding:60px;font-size:13px;color:#ef4444">Erreur de chargement. Réessayez.</div>;
        console.warn('loadConv error:', e.message);
    }
}

function renderMsgs(msgs, box) {
    let html = '';
    let lastDate = '';

    for (const m of msgs) {
        const dateStr = fmtDate(m.created_at);
        if (dateStr !== lastDate) {
            html += <div class="ms-date-sep"><span>${esc(dateStr)}</span></div>;
            lastDate = dateStr;
        }

        const hasA = m.reponse_admin?.trim();
        const hasC = m.message?.trim();

        if (!hasC && hasA) {
            html += bubR(m.reponse_admin, m.created_at);
        } else if (hasC) {
            html += bubL(m.nom_complet || 'Client', m.message, m.created_at);
            if (hasA) html += bubR(m.reponse_admin, m.updated_at);
        }
    }

    box.innerHTML = html;
    box.scrollTop = box.scrollHeight;
}

function bubL(sender, text, time) {
    return `<div class="ms-row-l"><div class="ms-bub-l">
        <div class="ms-sender">${esc(sender)}</div>
        <div class="ms-txt">${esc(text)}</div>
        <div class="ms-time">${fmtTime(time)}</div>
    </div></div>`;
}

function bubR(text, time, extra = '') {
    return `<div class="ms-row-r${extra}"><div class="ms-bub-r">
        <div class="ms-sender-r">Vous</div>
        <div class="ms-txt-r">${esc(text)}</div>
        <div class="ms-time-r">
            <span>${fmtTime(time)}</span>
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
    </div></div>`;
}

/* ══════════════════════════
   ENVOI — Optimistic UI
══════════════════════════ */
async function sendMsg() {
    if (!cEmail) return;
    if (arcSet.has(cEmail)) { warn('Conversation archivée — impossible de répondre.'); return; }
    if (inFlight) return;

    const ta  = document.getElementById('msInput');
    const raw = ta?.value || '';
    const msg = clean(raw);

    if (!validMsg(msg)) { if (msg.length > 0) warn('Message invalide (trop long ou caractères interdits).'); return; }
    if (!checkRL()) return;

    /* Lock */
    inFlight  = true;
    lastSend  = Date.now();
    sendCnt++;

    const btn = document.getElementById('msSend');
    if (btn) { btn.disabled = true; btn.classList.add('loading'); }

    /* Bulle optimiste */
    const box   = document.getElementById('msMessages');
    const optId = opt-${Date.now()};

    box?.insertAdjacentHTML('beforeend', `
        <div class="ms-row-r optimistic" id="${optId}">
            <div class="ms-bub-r">
                <div class="ms-sender-r">Vous</div>
                <div class="ms-txt-r">${esc(msg)}</div>
                <div class="ms-time-r"><span>Envoi…</span></div>
            </div>
        </div>`);

    if (box) box.scrollTop = box.scrollHeight;

    ta.value = ''; ta.style.height = 'auto'; updateCount(0);
    playSound('send');

    try {
        const res = await fetch('/admin/messages/reply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email_client: cEmail, reponse_admin: msg })
        });

        if (!res.ok) throw new Error(HTTP ${res.status});
        const data = await res.json();

        if (data.success !== false) {
            document.getElementById(optId)?.remove();
            await loadConv(cEmail);
            scheduleRefresh();
        } else {
            const el = document.getElementById(optId);
            el?.querySelector('.ms-bub-r')?.classList.add('err');
            ta.value = raw; updateCount(raw.length);
            warn(data.message || 'Erreur lors de l\'envoi.');
        }

    } catch(e) {
        const el = document.getElementById(optId);
        el?.querySelector('.ms-bub-r')?.classList.add('err');
        ta.value = raw; updateCount(raw.length);
        warn('Erreur réseau — vérifiez votre connexion.');
        console.error('sendMsg error:', e.message);

    } finally {
        inFlight = false;
        if (btn) { btn.disabled = false; btn.classList.remove('loading'); }
    }
}

/* ══════════════════════════
   CHAR COUNT & AUTORESIZE
══════════════════════════ */
function updateCount(n) {
    const el = document.getElementById('msCount');
    if (!el) return;
    el.textContent = n;
    el.style.color = n > 1900 ? '#ef4444' : n > 1500 ? 'var(--ms-orange)' : '';
}

const ta = document.getElementById('msInput');
if (ta) {
    ta.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 140) + 'px';
        const n = this.value.length;
        updateCount(n);
        if (n > 2000) this.value = this.value.substring(0, 2000);
    });
    ta.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); }
    });
}

document.getElementById('msSend')?.addEventListener('click', sendMsg);

/* ══════════════════════════
   BOUTON RETOUR MOBILE
   Gestion séparée et robuste
══════════════════════════ */
document.getElementById('msBack')?.addEventListener('click', function () {
    /* Affiche le panel de liste, cache le chat actif */
    const panel = document.getElementById('msPanel');
    if (panel) panel.classList.remove('slide-out');
    /* Sur les écrans de taille normale, rien ne change */
});

/* ══════════════════════════
   BOUTON SON
══════════════════════════ */
document.getElementById('btnSound')?.addEventListener('click', function () {
    soundOn = !soundOn;
    this.setAttribute('aria-pressed', soundOn ? 'true' : 'false');
    this.classList.toggle('s-off', !soundOn);
    this.title = soundOn ? 'Son activé' : 'Son désactivé';
    document.getElementById('soundIconOn').style.display  = soundOn ? '' : 'none';
    document.getElementById('soundIconOff').style.display = soundOn ? 'none' : '';
});

/* ══════════════════════════
   ARCHIVE
══════════════════════════ */
document.getElementById('btnArchive')?.addEventListener('click', () => {
    if (!cEmail) return;
    doArchive(cEmail, !cArc);
});

function updateArchBtn() {
    const b = document.getElementById('btnArchive');
    if (!b) return;
    b.title = cArc ? 'Désarchiver' : 'Archiver';
    b.classList.toggle('arc-on', cArc);
    b.setAttribute('aria-pressed', cArc ? 'true' : 'false');
}

function doArchive(email, arc) {
    fetch('/admin/messages/toggle-close', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email_client: email, closed: arc })
    })
    .then(r => r.json())
    .then(() => {
        if (arc) arcSet.add(email); else arcSet.delete(email);
        if (cEmail === email) {
            cArc = arc;
            const compose = document.getElementById('msCompose');
            if (compose) compose.style.display = arc ? 'none' : 'flex';
            updateArchBtn();
        }
        scheduleRefresh();
    })
    .catch(e => console.warn('archive error:', e.message));
}

/* ══════════════════════════
   INFO PANEL
══════════════════════════ */
document.getElementById('btnInfo')?.addEventListener('click', () => {
    document.getElementById('msInfoPanel')?.classList.add('open');
    document.getElementById('msVeil')?.classList.add('open');
});

document.getElementById('msInfoClose')?.addEventListener('click', closeInfo);
document.getElementById('msVeil')?.addEventListener('click', closeInfo);

function closeInfo() {
    document.getElementById('msInfoPanel')?.classList.remove('open');
    document.getElementById('msVeil')?.classList.remove('open');
}

/* Fermer sur Escape */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeInfo();
        hideToast();
    }
});

/* ══════════════════════════
   TABS
══════════════════════════ */
document.querySelectorAll('.ms-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        document.querySelectorAll('.ms-tab').forEach(t => {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
        });
        this.classList.add('active');
        this.setAttribute('aria-selected', 'true');

        const f = this.dataset.filter;
        document.querySelectorAll('.ms-conv').forEach(it => {
            const arc = it.dataset.archived === '1';
            it.style.display = (f === 'all' && !arc) || (f === 'archived' && arc) ? '' : 'none';
        });
    });
});

/* ══════════════════════════
   SÉLECTION D'UNE CONVERSATION
══════════════════════════ */
function selectConv(el) {
    /* Mise à jour sélection visuelle */
    document.querySelectorAll('.ms-conv').forEach(i => i.classList.remove('sel'));
    el.classList.add('sel');

    /* Extraction données */
    cEmail = el.dataset.email;
    cInit  = el.dataset.initials || '?';
    cC1    = el.dataset.c1 || '#2563a8';
    cC2    = el.dataset.c2 || '#3b82c4';
    cPhone = el.dataset.phone || '';
    cArc   = arcSet.has(cEmail);

    clearNew(cEmail);

    /* Header du chat */
    const tav = document.getElementById('threadAv');
    if (tav) { tav.textContent = cInit; tav.style.background = linear-gradient(145deg,${cC1},${cC2}); }
    setEl('threadName', el.dataset.name);
    setEl('threadEmail', cEmail);

    /* Info panel */
    const iav = document.getElementById('infoAv');
    if (iav) { iav.textContent = cInit; iav.style.background = linear-gradient(145deg,${cC1},${cC2}); }
    setEl('infoName',  el.dataset.name);
    setEl('infoEmail', cEmail);
    setEl('infoPhone', cPhone || 'Non renseigné');

    /* Statut dans info panel */
    const isArc  = arcSet.has(cEmail);
    const hasRes = el.dataset.responded === '1';
    const infoSt = document.getElementById('infoStatus');
    if (infoSt) {
        infoSt.className   = ms-tag ${isArc ? 'arc' : (hasRes ? 'ok' : 'wait')};
        infoSt.textContent = isArc ? 'Archivé' : (hasRes ? 'Répondu' : 'En attente');
    }

    /* Afficher zone chat */
    const idle   = document.getElementById('msIdle');
    const thread = document.getElementById('msThread');
    const compose = document.getElementById('msCompose');
    const warn_el = document.getElementById('msWarn');

    if (idle)    idle.style.display   = 'none';
    if (thread)  thread.style.display = 'flex';
    if (compose) compose.style.display = cArc ? 'none' : 'flex';
    if (warn_el) warn_el.style.display = 'none';

    updateArchBtn();
    loadConv(cEmail);

    /* ── MOBILE : glisser le panel hors de l'écran ── */
    if (window.innerWidth <= 768) {
        const panel = document.getElementById('msPanel');
        if (panel) panel.classList.add('slide-out');
    }
}

function setEl(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
}

/* ══════════════════════════
   ATTACHER EVENTS CONV
══════════════════════════ */
function attachConvEvents() {
    document.querySelectorAll('.ms-conv').forEach(el => {
        /* Clone pour supprimer anciens listeners */
        const ne = el.cloneNode(true);
        el.parentNode.replaceChild(ne, el);

        /* Clic */
        ne.addEventListener('click', function () { selectConv(this); });

        /* Accessibilité clavier */
        ne.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                selectConv(this);
            }
        });
    });
}

/* ══════════════════════════
   REFRESH LISTE
══════════════════════════ */
function scheduleRefresh() {
    if (refreshing) { refreshPend = true; return; }
    doRefresh();
}

function doRefresh() {
    if (refreshing) return;
    refreshing  = true;
    refreshPend = false;

    fetch(window.location.href, { cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => {
            const doc  = new DOMParser().parseFromString(html, 'text/html');
            const newL = doc.getElementById('msConvList');
            const oldL = document.getElementById('msConvList');
            if (!newL || !oldL) return;

            const scrollY = oldL.scrollTop;
            oldL.innerHTML = newL.innerHTML;
            attachConvEvents();

            /* Restaurer conv sélectionnée */
            if (cEmail) {
                const sel = oldL.querySelector(.ms-conv[data-email="${cssEsc(cEmail)}"]);
                sel?.classList.add('sel');
            }

            /* Restaurer états */
            oldL.querySelectorAll('.ms-conv').forEach(el => {
                const em = el.dataset.email;
                if (el.dataset.archived === '1') arcSet.add(em); else arcSet.delete(em);
                if (newSet.has(em)) markNew(em);
            });

            /* Garder la position scroll */
            oldL.scrollTop = scrollY;
        })
        .catch(e => console.warn('refreshList error:', e.message))
        .finally(() => {
            refreshing = false;
            if (refreshPend) doRefresh();
        });
}

/* ══════════════════════════
   PUSHER — TEMPS RÉEL
══════════════════════════ */
function initPusher() {
    if (typeof Pusher === 'undefined') { setTimeout(initPusher, 600); return; }

    const key     = '{{ env("PUSHER_APP_KEY") }}';
    const cluster = '{{ env("PUSHER_APP_CLUSTER") }}';

    if (!key || key === '') {
        console.error('[Pusher] Clé APP_KEY manquante dans .env');
        setRT('disconnected');
        return;
    }

    const pusher = new Pusher(key, {
        cluster,
        encrypted: true,
        enabledTransports: ['ws', 'wss']
    });

    const channel = pusher.subscribe('new-messages');

    pusher.connection.bind('connected',    () => setRT('connected'));
    pusher.connection.bind('disconnected', () => setRT('disconnected'));
    pusher.connection.bind('connecting',   () => setRT('connecting'));
    pusher.connection.bind('unavailable',  () => setRT('disconnected'));

    /* Reconnexion automatique sur perte de signal */
    pusher.connection.bind('error', err => {
        console.warn('[Pusher] Erreur connexion:', err);
        setRT('disconnected');
    });

    channel.bind('App\\Events\\NewMessageReceived', function (data) {
        if (!data?.email_client) return;

        const email = data.email_client;
        const name  = data.nom_complet || email;
        const msg   = data.message || 'Nouveau message';

        /* Toujours rafraîchir la liste */
        scheduleRefresh();

        if (email !== cEmail) {
            /* Autre conversation */
            markNew(email);
            const it = document.querySelector(.ms-conv[data-email="${cssEsc(email)}"]);
            const c1 = it?.dataset.c1 || '#2563a8';
            const c2 = it?.dataset.c2 || '#3b82c4';
            showToast(name, msg, c1, c2);
            notifBrowser(name, msg);
        } else {
            /* Conversation ouverte → recharger les messages */
            loadConv(cEmail);
        }
    });
}

/* ══════════════════════════
   INIT
══════════════════════════ */
attachConvEvents();
initPusher();
initBrowserNotif();

/* Fallback polling toutes les 30s */
setInterval(scheduleRefresh, 30000);

})();
</script>

@endsection