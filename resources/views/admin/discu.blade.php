@extends('layouts.admin')

@section('page-title', 'Discussions')
@section('page-subtitle', 'Messagerie clients en temps réel')

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

{{-- ═══ STATS ═══ --}}
<div class="ms-stats">
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
        @if($nonRepondus > 0)<div class="ms-alert-ring"></div>@endif
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

    <div class="ms-rt-badge" id="rtBadge">
        <div class="ms-rt-dot"></div>
        <span id="rtLabel">Connexion…</span>
    </div>
</div>

{{-- ═══ MESSENGER ═══ --}}
<div class="ms-shell" id="msShell">

    {{-- ── PANEL GAUCHE : liste conversations ── --}}
    <div class="ms-panel" id="msPanel">

        <div class="ms-panel-head">
            <span class="ms-panel-title">
                Conversations
                @if($nonRepondus > 0)
                    <span class="ms-panel-badge">{{ $nonRepondus }}</span>
                @endif
            </span>
        </div>

        <div class="ms-tabs">
            <button class="ms-tab active" data-filter="all">Actives @if($nonRepondus > 0)<em>{{ $nonRepondus }}</em>@endif</button>
            <button class="ms-tab" data-filter="archived">Archivées @if($archivees > 0)<em class="muted">{{ $archivees }}</em>@endif</button>
        </div>

        <div class="ms-conv-list" id="msConvList">
            @if($conversations->count() > 0)
                @foreach($conversations as $conv)
                    @php
                        $lastMsg     = \App\Models\Message::where('email_client', $conv->email_client)->latest()->first();
                        $isArchived  = $conv->closed ?? false;
                        $hasResponse = $lastMsg && $lastMsg->reponse_admin && $lastMsg->reponse_admin !== '';
                        $initials    = collect(explode(' ', $conv->nom_complet))->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->join('');
                        $palette = [
                            ['#2563a8','#60a5fa'],['#d63384','#f472b6'],
                            ['#2d9c4f','#4ade80'],['#e8722a','#fb923c'],
                            ['#7c3aed','#a78bfa'],['#1a8fa0','#22d3ee'],
                        ];
                        $cp = $palette[crc32($conv->email_client) % count($palette)];
                        $phone = '';
                        if (!empty($lastMsg->telephone)) $phone = $lastMsg->telephone;
                        elseif (!empty($lastMsg->phone)) $phone = $lastMsg->phone;
                        elseif (!empty($conv->telephone)) $phone = $conv->telephone;
                        $isUnread = !$hasResponse && !$isArchived;
                        $previewText = $lastMsg ? Str::limit(strip_tags($lastMsg->message), 48) : 'Aucun message';
                        $timeAgo = $lastMsg ? $lastMsg->created_at->format('H:i') : '';
                    @endphp
                    <div class="ms-conv {{ $isUnread ? 'unread' : '' }}"
                         data-email="{{ e($conv->email_client) }}"
                         data-name="{{ e($conv->nom_complet) }}"
                         data-initials="{{ $initials }}"
                         data-c1="{{ $cp[0] }}"
                         data-c2="{{ $cp[1] }}"
                         data-phone="{{ e($phone) }}"
                         data-archived="{{ $isArchived ? '1' : '0' }}"
                         data-responded="{{ $hasResponse ? '1' : '0' }}">

                        <div class="ms-conv-av" style="background:linear-gradient(145deg,{{ $cp[0] }},{{ $cp[1] }})">
                            {{ $initials }}
                            @if($isUnread)<div class="ms-conv-badge"></div>@endif
                        </div>

                        <div class="ms-conv-info">
                            <div class="ms-conv-top">
                                <span class="ms-conv-name">{{ e($conv->nom_complet) }}</span>
                                <span class="ms-conv-time">{{ $timeAgo }}</span>
                            </div>
                            <div class="ms-conv-preview">{{ $previewText }}</div>
                            <div class="ms-conv-tag-row">
                                <span class="ms-tag {{ $isArchived ? 'arc' : ($hasResponse ? 'ok' : 'wait') }}">
                                    {{ $isArchived ? 'Archivé' : ($hasResponse ? 'Répondu' : 'En attente') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="ms-empty-list">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <p>Aucune conversation</p>
                    <span>Les messages clients apparaîtront ici</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── ZONE CHAT ── --}}
    <div class="ms-chat" id="msChat">

        {{-- Écran vide --}}
        <div class="ms-idle" id="msIdle">
            <div class="ms-idle-orbs">
                <div class="ms-orb o1"></div>
                <div class="ms-orb o2"></div>
                <div class="ms-orb o3"></div>
            </div>
            <div class="ms-idle-icon">
                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <h3>Choisissez une conversation</h3>
            @if($nonRepondus > 0)
                <p class="ms-idle-alert">⚡ {{ $nonRepondus }} message{{ $nonRepondus > 1 ? 's' : '' }} en attente</p>
            @else
                <p>Toutes les conversations sont à jour ✓</p>
            @endif
        </div>

        {{-- Chat actif --}}
        <div class="ms-thread" id="msThread" style="display:none">

            {{-- Header --}}
            <div class="ms-thread-head">
                <button class="ms-back" id="msBack" aria-label="Retour">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                </button>

                <div class="ms-thread-contact">
                    <div class="ms-thread-av" id="threadAv"></div>
                    <div class="ms-thread-meta">
                        <div class="ms-thread-name" id="threadName">—</div>
                        <div class="ms-thread-sub">
                            <span class="ms-online-dot"></span>
                            <span id="threadEmail">—</span>
                        </div>
                    </div>
                </div>

                <div class="ms-thread-actions">
                    {{-- Indicateur "typing" côté admin --}}
                    <div class="ms-typing-indicator" id="typingIndicator" style="display:none">
                        <span></span><span></span><span></span>
                    </div>

                    <button class="ms-hbtn" id="btnSound" title="Son activé">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </button>
                    <button class="ms-hbtn" id="btnArchive" title="Archiver">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                    </button>
                    <button class="ms-hbtn" id="btnInfo" title="Fiche client">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Messages --}}
            <div class="ms-messages" id="msMessages"></div>

            {{-- Barre de composition --}}
            <div class="ms-compose" id="msCompose">
                <div class="ms-compose-inner">
                    <textarea
                        id="msInput"
                        rows="1"
                        placeholder="Répondre…"
                        maxlength="2000"
                        aria-label="Message"
                    ></textarea>
                    <div class="ms-compose-footer">
                        <span class="ms-ccount"><span id="msCount">0</span>/2000</span>
                        <span class="ms-hint">↵ Envoyer · ⇧↵ Nouvelle ligne</span>
                    </div>
                </div>
                <button class="ms-send" id="msSend" aria-label="Envoyer">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 2 11 13M22 2 15 22l-4-9-9-4 20-7z"/></svg>
                </button>
            </div>

            {{-- Warning anti-spam --}}
            <div class="ms-warn" id="msWarn" style="display:none">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <span id="msWarnTxt"></span>
            </div>
        </div>
    </div>
</div>

{{-- ═══ FICHE CLIENT PANEL ═══ --}}
<div class="ms-info-panel" id="msInfoPanel">
    <div class="ms-info-header">
        <span>Fiche client</span>
        <button id="msInfoClose"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="ms-info-content">
        <div class="ms-info-av" id="infoAv"></div>
        <div class="ms-info-name" id="infoName">—</div>
        <div class="ms-info-role">Client</div>
        <div class="ms-info-rows">
            <div class="ms-info-row">
                <div class="ms-info-dot" style="background:#2563a8"></div>
                <div>
                    <div class="ms-info-lbl">Email</div>
                    <div class="ms-info-val" id="infoEmail">—</div>
                </div>
            </div>
            <div class="ms-info-row">
                <div class="ms-info-dot" style="background:#2d9c4f"></div>
                <div>
                    <div class="ms-info-lbl">Téléphone</div>
                    <div class="ms-info-val" id="infoPhone">—</div>
                </div>
            </div>
            <div class="ms-info-row" style="border:none">
                <div class="ms-info-dot" style="background:#e8722a"></div>
                <div>
                    <div class="ms-info-lbl">Statut</div>
                    <div class="ms-tag" id="infoStatus" style="margin-top:5px">—</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ms-veil" id="msVeil"></div>

{{-- ═══ TOAST NOTIFICATION ═══ --}}
<div class="ms-toast" id="msToast" role="alert" aria-live="assertive">
    <div class="ms-toast-strip"></div>
    <div class="ms-toast-body">
        <div class="ms-toast-av" id="toastAv"></div>
        <div class="ms-toast-txt">
            <div class="ms-toast-name" id="toastName"></div>
            <div class="ms-toast-msg"  id="toastMsg"></div>
        </div>
        <button class="ms-toast-x" id="toastClose" aria-label="Fermer">
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="ms-toast-prog" id="toastProg"></div>
</div>

{{-- ═══ BANNER PERMISSION NOTIFICATIONS ═══ --}}
<div class="ms-perm" id="msPerm" style="display:none">
    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
    <span>Activez les notifications navigateur pour ne rien manquer</span>
    <button id="permAllow">Activer</button>
    <button id="permDismiss">Plus tard</button>
</div>

{{-- ═══════════ STYLES ═══════════ --}}
<style>
/* ────── Tokens ────── */
:root {
    --ms-blue:   #2563a8;
    --ms-blue2:  #3b82c4;
    --ms-pink:   #d63384;
    --ms-orange: #e8722a;
    --ms-green:  #2d9c4f;
    --ms-teal:   #1a8fa0;
}

/* ────── Keyframes ────── */
@keyframes ms-up    { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
@keyframes ms-in    { from{opacity:0;transform:translateX(72px) scale(.96)} to{opacity:1;transform:none} }
@keyframes ms-bub   { from{opacity:0;transform:scale(.9) translateY(8px)} to{opacity:1;transform:none} }
@keyframes ms-prog  { from{width:100%} to{width:0} }
@keyframes ms-pulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.6);opacity:.5} }
@keyframes ms-ring  { 0%{transform:scale(1);opacity:.25} 100%{transform:scale(2.6);opacity:0} }
@keyframes ms-spin  { to{transform:rotate(360deg)} }
@keyframes ms-dot   { 0%,80%,100%{transform:scale(0);opacity:.4} 40%{transform:scale(1);opacity:1} }
@keyframes ms-flash { 0%,100%{background:transparent} 50%{background:rgba(37,99,168,.07)} }
@keyframes ms-orb   { 0%,100%{transform:scale(1) translate(0,0);opacity:.6} 50%{transform:scale(1.15) translate(8px,-6px);opacity:.9} }

/* ────── Stats ────── */
.ms-stats {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 16px; flex-wrap: wrap;
}

.ms-stat {
    display: flex; align-items: center; gap: 11px;
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 14px; padding: 11px 16px;
    flex: 1; min-width: 120px;
    position: relative; overflow: hidden;
    transition: transform .18s ease, box-shadow .18s ease;
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

.ms-stat-info { display: flex; flex-direction: column; gap: 1px; }
.ms-stat-n { font-family: 'Outfit',sans-serif; font-size: 21px; font-weight: 800; color: var(--text-primary,#0f1923); line-height: 1; }
.ms-stat-l { font-size: 10.5px; color: var(--text-muted,#94a3b8); font-weight: 500; }

.ms-alert-ring {
    position: absolute; top: 50%; right: 12px; transform: translateY(-50%);
    width: 10px; height: 10px; border-radius: 50%;
    background: var(--ms-orange);
    animation: ms-pulse 1.3s ease-in-out infinite;
}

/* Realtime badge */
.ms-rt-badge {
    display: flex; align-items: center; gap: 7px;
    padding: 8px 14px; border-radius: 20px;
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    font-size: 12px; font-weight: 600;
    color: var(--text-muted,#94a3b8);
    white-space: nowrap; margin-left: auto; flex-shrink: 0;
    transition: all .25s ease;
}
.ms-rt-dot { width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; transition: background .3s; }

.ms-rt-badge.on  { color: var(--ms-green); border-color: rgba(45,156,79,.3); }
.ms-rt-badge.on .ms-rt-dot { background: var(--ms-green); box-shadow: 0 0 7px rgba(45,156,79,.6); animation: ms-pulse 2s ease-in-out infinite; }
.ms-rt-badge.off { color: #ef4444; border-color: rgba(239,68,68,.3); }
.ms-rt-badge.off .ms-rt-dot { background: #ef4444; }

/* ────── Shell ────── */
.ms-shell {
    display: flex;
    height: calc(100vh - 256px);
    min-height: 480px;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--border-color,#e2e8f0);
    box-shadow: 0 6px 28px rgba(0,0,0,.07);
    animation: ms-up .3s ease;
}

/* ────── Panel gauche ────── */
.ms-panel {
    width: 290px; flex-shrink: 0;
    display: flex; flex-direction: column;
    background: var(--bg-surface,#fff);
    border-right: 1px solid var(--border-color,#e2e8f0);
    overflow: hidden;
    transition: transform .22s cubic-bezier(.4,0,.2,1);
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
    background: linear-gradient(135deg,var(--ms-orange),var(--ms-pink));
    color: white; font-size: 10px; font-weight: 800;
    padding: 2px 8px; border-radius: 20px;
    animation: ms-pulse 2s infinite;
    box-shadow: 0 0 8px rgba(232,114,42,.4);
}

/* Tabs */
.ms-tabs {
    display: flex; gap: 3px;
    padding: 8px 10px 4px; flex-shrink: 0;
}

.ms-tab {
    flex: 1; padding: 7px; border: none; border-radius: 9px;
    font-size: 12px; font-weight: 500; cursor: pointer;
    color: var(--text-muted,#94a3b8); background: transparent;
    font-family: 'DM Sans',sans-serif;
    transition: all .16s ease;
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
.ms-conv-list {
    flex: 1; overflow-y: auto; padding: 4px 6px;
}
.ms-conv-list::-webkit-scrollbar { width: 3px; }
.ms-conv-list::-webkit-scrollbar-thumb { background: var(--border-color,#e2e8f0); border-radius: 3px; }

/* Conv item */
.ms-conv {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px; border-radius: 12px;
    cursor: pointer; margin-bottom: 2px;
    border: 1px solid transparent;
    transition: all .15s ease;
    position: relative;
}
.ms-conv:hover   { background: var(--bg-surface-2,#f8fafc); }
.ms-conv.sel     { background: rgba(37,99,168,.07); border-color: rgba(37,99,168,.2); }
.ms-conv.unread  { background: rgba(232,114,42,.05); border-color: rgba(232,114,42,.18); }
.ms-conv.new-msg { animation: ms-flash .5s ease; }

.ms-conv-av {
    width: 44px; height: 44px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 14px; font-weight: 700;
    font-family: 'Outfit',sans-serif; flex-shrink: 0;
    position: relative;
    box-shadow: 0 3px 10px rgba(0,0,0,.13);
}

.ms-conv-badge {
    position: absolute; top: -3px; right: -3px;
    width: 11px; height: 11px; border-radius: 50%;
    background: var(--ms-orange);
    border: 2px solid var(--bg-surface,#fff);
    animation: ms-pulse 1.4s ease-in-out infinite;
}

.ms-conv-info { flex: 1; min-width: 0; }
.ms-conv-top  { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px; }
.ms-conv-name { font-size: 13px; font-weight: 600; color: var(--text-primary,#0f1923); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 145px; }
.ms-conv-time { font-size: 10px; color: var(--text-muted,#94a3b8); flex-shrink: 0; }
.ms-conv-preview { font-size: 11.5px; color: var(--text-muted,#94a3b8); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 5px; }
.ms-conv-tag-row { display: flex; align-items: center; }

/* Tags */
.ms-tag { font-size: 9.5px; font-weight: 700; padding: 2px 7px; border-radius: 8px; display: inline-flex; align-items: center; gap: 3px; }
.ms-tag.wait { background: rgba(232,114,42,.12); color: var(--ms-orange); }
.ms-tag.ok   { background: rgba(45,156,79,.12);  color: var(--ms-green); }
.ms-tag.arc  { background: var(--bg-surface-2,#f8fafc); color: var(--text-muted,#94a3b8); }

/* Empty */
.ms-empty-list { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 60px 20px; gap: 8px; color: var(--text-muted,#94a3b8); }
.ms-empty-list svg { opacity: .3; }
.ms-empty-list p { font-size: 13.5px; font-weight: 600; color: var(--text-secondary,#4a5568); }
.ms-empty-list span { font-size: 11.5px; }

/* ────── Chat zone ────── */
.ms-chat {
    flex: 1; display: flex; flex-direction: column;
    background: var(--bg-base,#f0f4f8);
    background-image:
        radial-gradient(circle at 16px 16px, rgba(37,99,168,.022) 1px, transparent 1px),
        radial-gradient(circle at 48px 48px, rgba(45,156,79,.018) 1px, transparent 1px);
    background-size: 32px 32px;
    min-width: 0; position: relative;
}

/* Idle screen */
.ms-idle {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 10px; position: relative; overflow: hidden;
}

.ms-idle-orbs { position: absolute; inset: 0; pointer-events: none; }
.ms-orb {
    position: absolute; border-radius: 50%; filter: blur(40px);
    animation: ms-orb 6s ease-in-out infinite;
}
.ms-orb.o1 { width: 200px; height: 200px; background: rgba(37,99,168,.07);  top: 10%; left: 10%; animation-delay: 0s; }
.ms-orb.o2 { width: 160px; height: 160px; background: rgba(45,156,79,.06);  bottom: 15%; right: 15%; animation-delay: 2s; }
.ms-orb.o3 { width: 120px; height: 120px; background: rgba(232,114,42,.05); top: 55%; left: 45%; animation-delay: 4s; }

.ms-idle-icon {
    width: 68px; height: 68px; border-radius: 50%;
    background: var(--bg-surface,#fff);
    box-shadow: 0 8px 24px rgba(0,0,0,.09);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted,#94a3b8); z-index: 1;
    margin-bottom: 4px;
}

.ms-idle h3  { font-family: 'Outfit',sans-serif; font-size: 18px; font-weight: 700; color: var(--text-secondary,#4a5568); z-index: 1; }
.ms-idle p   { font-size: 12.5px; color: var(--text-muted,#94a3b8); z-index: 1; }
.ms-idle-alert {
    color: var(--ms-orange) !important;
    background: rgba(232,114,42,.1);
    padding: 5px 16px; border-radius: 20px;
    display: inline-flex; align-items: center; gap: 4px;
    font-weight: 600 !important; font-size: 12.5px !important;
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

.ms-back {
    display: none; width: 32px; height: 32px;
    background: var(--bg-surface-2,#f8fafc);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 9px; cursor: pointer;
    color: var(--text-secondary,#4a5568);
    align-items: center; justify-content: center;
    flex-shrink: 0; transition: all .14s;
}
.ms-back:hover { background: var(--border-color,#e2e8f0); }

.ms-thread-contact { display: flex; align-items: center; gap: 11px; flex: 1; min-width: 0; }

.ms-thread-av {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 14px; font-weight: 700;
    flex-shrink: 0; font-family: 'Outfit',sans-serif;
    box-shadow: 0 3px 10px rgba(0,0,0,.14);
}

.ms-thread-name { font-family: 'Outfit',sans-serif; font-size: 14px; font-weight: 700; color: var(--text-primary,#0f1923); }
.ms-thread-sub  { display: flex; align-items: center; gap: 5px; font-size: 11.5px; color: var(--text-muted,#94a3b8); }
.ms-online-dot  { width: 7px; height: 7px; border-radius: 50%; background: var(--ms-green); box-shadow: 0 0 6px rgba(45,156,79,.5); animation: ms-pulse 2.5s ease-in-out infinite; flex-shrink: 0; }

.ms-thread-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

.ms-hbtn {
    width: 32px; height: 32px; border-radius: 9px;
    background: var(--bg-surface-2,#f8fafc);
    border: 1px solid var(--border-color,#e2e8f0);
    cursor: pointer; color: var(--text-secondary,#4a5568);
    display: flex; align-items: center; justify-content: center;
    transition: all .15s ease;
}
.ms-hbtn:hover   { background: rgba(37,99,168,.1); color: var(--ms-blue); border-color: rgba(37,99,168,.25); }
.ms-hbtn.s-on    { background: rgba(45,156,79,.1);  color: var(--ms-green); border-color: rgba(45,156,79,.25); }
.ms-hbtn.arc-on  { background: rgba(239,68,68,.1);  color: #ef4444; border-color: rgba(239,68,68,.2); }

/* Typing indicator */
.ms-typing-indicator {
    display: inline-flex; align-items: center; gap: 3px;
    padding: 6px 10px; background: var(--bg-surface-2,#f8fafc);
    border: 1px solid var(--border-color,#e2e8f0); border-radius: 12px;
}
.ms-typing-indicator span {
    width: 5px; height: 5px; border-radius: 50%; background: var(--text-muted,#94a3b8);
    animation: ms-dot 1.2s ease-in-out infinite both;
}
.ms-typing-indicator span:nth-child(2) { animation-delay: .16s; }
.ms-typing-indicator span:nth-child(3) { animation-delay: .32s; }

/* Messages */
.ms-messages {
    flex: 1; overflow-y: auto; padding: 18px 20px;
    display: flex; flex-direction: column; gap: 8px;
    scroll-behavior: smooth;
}
.ms-messages::-webkit-scrollbar { width: 4px; }
.ms-messages::-webkit-scrollbar-thumb { background: var(--border-color,#e2e8f0); border-radius: 4px; }

/* Separateur de date */
.ms-date-sep {
    display: flex; align-items: center; gap: 10px;
    margin: 6px 0; opacity: .6;
}
.ms-date-sep::before,.ms-date-sep::after { content:''; flex:1; height:1px; background:var(--border-subtle,#eef2f7); }
.ms-date-sep span { font-size: 10.5px; font-weight: 600; color: var(--text-muted,#94a3b8); white-space: nowrap; }

/* Bulles */
.ms-row-l { display: flex; justify-content: flex-start; animation: ms-bub .2s ease; }
.ms-row-r { display: flex; justify-content: flex-end;   animation: ms-bub .2s ease; }

/* Bulle optimiste (envoi en cours) */
.ms-row-r.optimistic .ms-bub-r { opacity: .65; }

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
    position: relative;
}

.ms-bub-r.error-bub { background: linear-gradient(135deg,#dc2626,#ef4444); }

.ms-sender   { font-size: 10.5px; font-weight: 700; color: var(--ms-blue); margin-bottom: 4px; }
.ms-sender-r { font-size: 10.5px; font-weight: 700; color: rgba(255,255,255,.65); margin-bottom: 4px; }
.ms-txt      { font-size: 13.5px; color: var(--text-primary,#0f1923); line-height: 1.5; word-break: break-word; }
.ms-txt-r    { font-size: 13.5px; color: white; line-height: 1.5; word-break: break-word; }
.ms-time     { font-size: 9.5px; color: var(--text-muted,#94a3b8); margin-top: 5px; text-align: right; }
.ms-time-r   { font-size: 9.5px; color: rgba(255,255,255,.5); margin-top: 5px; display: flex; align-items: center; justify-content: flex-end; gap: 3px; }

/* Loader messages */
.ms-loader { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 50px; color: var(--text-muted,#94a3b8); font-size: 13px; }
.ms-loader svg { animation: ms-spin .8s linear infinite; }

/* ────── Compose ────── */
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
.ms-compose-inner textarea:disabled { opacity: .4; cursor: not-allowed; }

.ms-compose-footer {
    position: absolute; bottom: 8px; left: 12px; right: 12px;
    display: flex; align-items: center; justify-content: space-between;
    pointer-events: none;
}
.ms-ccount { font-size: 10px; color: var(--text-muted,#94a3b8); }
.ms-hint   { font-size: 10px; color: var(--text-muted,#94a3b8); opacity: .65; }

.ms-send {
    width: 46px; height: 46px; border-radius: 14px;
    background: linear-gradient(140deg, var(--ms-blue), var(--ms-blue2));
    border: none; cursor: pointer; color: white; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: all .18s ease;
    box-shadow: 0 4px 14px rgba(37,99,168,.35);
}
.ms-send:hover  { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,168,.45); }
.ms-send:active { transform: scale(.95); }
.ms-send:disabled { opacity: .4; cursor: not-allowed; transform: none; }
.ms-send.loading { animation: ms-pulse .7s ease-in-out infinite; }

/* Warn */
.ms-warn {
    display: flex; align-items: center; gap: 7px;
    margin: 0 16px 8px; padding: 7px 13px;
    border-radius: 9px; font-size: 12px; font-weight: 500;
    background: rgba(232,114,42,.1); border: 1px solid rgba(232,114,42,.25);
    color: var(--ms-orange); flex-shrink: 0;
}

/* ────── Info Panel ────── */
.ms-info-panel {
    position: fixed; top: 0; right: 0; height: 100%; width: 295px;
    background: var(--bg-surface,#fff);
    border-left: 1px solid var(--border-color,#e2e8f0);
    transform: translateX(100%);
    transition: transform .26s cubic-bezier(.4,0,.2,1);
    z-index: 500; display: flex; flex-direction: column;
    box-shadow: -8px 0 32px rgba(0,0,0,.08);
}
.ms-info-panel.open { transform: translateX(0); }

.ms-veil { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.22); z-index: 499; backdrop-filter: blur(2px); }
.ms-veil.open { display: block; }

.ms-info-header {
    background: linear-gradient(135deg, var(--ms-blue), var(--ms-blue2));
    padding: 18px 20px; color: white;
    display: flex; justify-content: space-between; align-items: center;
    font-family: 'Outfit',sans-serif; font-size: 15px; font-weight: 700;
    flex-shrink: 0;
}
.ms-info-header button {
    width: 27px; height: 27px; border-radius: 8px;
    background: rgba(255,255,255,.15); border: none;
    color: white; cursor: pointer; display: flex;
    align-items: center; justify-content: center;
    transition: background .14s;
}
.ms-info-header button:hover { background: rgba(255,255,255,.25); }

.ms-info-content { padding: 24px 22px; display: flex; flex-direction: column; align-items: center; overflow-y: auto; flex: 1; }

.ms-info-av { width: 70px; height: 70px; border-radius: 18px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 700; font-family: 'Outfit',sans-serif; margin-bottom: 12px; box-shadow: 0 6px 18px rgba(0,0,0,.12); }
.ms-info-name { font-family: 'Outfit',sans-serif; font-size: 17px; font-weight: 800; color: var(--text-primary,#0f1923); }
.ms-info-role { font-size: 11.5px; color: var(--text-muted,#94a3b8); margin-top: 3px; margin-bottom: 18px; }

.ms-info-rows { width: 100%; }
.ms-info-row { display: flex; align-items: flex-start; gap: 12px; padding: 11px 0; border-bottom: 1px solid var(--border-subtle,#eef2f7); }
.ms-info-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
.ms-info-lbl { font-size: 10px; color: var(--text-muted,#94a3b8); text-transform: uppercase; letter-spacing: .5px; font-weight: 700; margin-bottom: 3px; }
.ms-info-val { font-size: 13px; color: var(--text-primary,#0f1923); font-weight: 500; word-break: break-all; }

/* ────── Toast ────── */
.ms-toast {
    position: fixed; bottom: 20px; right: 20px;
    width: 330px; max-width: calc(100vw - 28px);
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 16px;
    box-shadow: 0 16px 44px rgba(0,0,0,.14), 0 2px 8px rgba(0,0,0,.06);
    overflow: hidden; z-index: 2000; pointer-events: none;
    transform: translateX(calc(100% + 28px));
    opacity: 0;
    transition: transform .38s cubic-bezier(.34,1.56,.64,1), opacity .28s ease;
}
.ms-toast.show { transform: translateX(0); opacity: 1; pointer-events: all; }

.ms-toast-strip { height: 3px; background: linear-gradient(90deg,var(--ms-blue),var(--ms-pink),var(--ms-orange)); }

.ms-toast-body { display: flex; align-items: center; gap: 11px; padding: 11px 13px; }

.ms-toast-av {
    width: 38px; height: 38px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 14px; font-weight: 700;
    flex-shrink: 0; font-family: 'Outfit',sans-serif;
    box-shadow: 0 3px 9px rgba(0,0,0,.14);
}

.ms-toast-txt { flex: 1; min-width: 0; }
.ms-toast-name { font-size: 13px; font-weight: 700; color: var(--text-primary,#0f1923); font-family: 'Outfit',sans-serif; }
.ms-toast-msg  { font-size: 11.5px; color: var(--text-secondary,#4a5568); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }

.ms-toast-x {
    width: 24px; height: 24px; border-radius: 7px; flex-shrink: 0;
    background: var(--bg-surface-2,#f8fafc); border: 1px solid var(--border-color,#e2e8f0);
    cursor: pointer; color: var(--text-muted,#94a3b8);
    display: flex; align-items: center; justify-content: center;
    transition: all .13s;
}
.ms-toast-x:hover { background: var(--border-color,#e2e8f0); color: var(--text-primary,#0f1923); }

.ms-toast-prog { height: 3px; background: linear-gradient(90deg,var(--ms-blue),var(--ms-blue2)); }
.ms-toast-prog.go { animation: ms-prog 5s linear forwards; }

/* ────── Permission banner ────── */
.ms-perm {
    display: flex; align-items: center; gap: 10px;
    background: rgba(37,99,168,.08); border: 1px solid rgba(37,99,168,.2);
    border-radius: 12px; padding: 9px 14px; margin-bottom: 14px;
    font-size: 12.5px; color: var(--ms-blue); font-weight: 500;
    flex-wrap: wrap;
    animation: ms-up .3s ease;
}
.ms-perm svg { flex-shrink: 0; }
.ms-perm button { padding: 5px 13px; border-radius: 8px; border: none; font-size: 11.5px; font-weight: 600; cursor: pointer; font-family: 'DM Sans',sans-serif; transition: all .14s; }
#permAllow   { background: var(--ms-blue); color: white; margin-left: auto; }
#permAllow:hover { background: var(--ms-blue2); }
#permDismiss { background: transparent; color: var(--text-muted,#94a3b8); }

/* ────── Responsive ────── */
@media (max-width:768px) {
    .ms-panel { position: absolute; width: 100%; height: 100%; z-index: 10; }
    .ms-panel.hide { transform: translateX(-100%); }
    .ms-back  { display: flex !important; }
    .ms-info-panel { width: 100%; }
    .ms-toast { bottom: 10px; right: 10px; left: 10px; width: auto; }
    .ms-shell { height: calc(100vh - 190px); border-radius: 14px; }
    .ms-stats { gap: 7px; }
    .ms-stat  { padding: 9px 12px; min-width: 100px; }
}

@media (max-width:420px) {
    .ms-stat-n { font-size: 18px; }
    .ms-hint   { display: none; }
}
</style>

{{-- ═══════════ JS ═══════════ --}}
<script>
(function () {
'use strict';

/* ══════════════════════════════════
   ÉTAT
══════════════════════════════════ */
let cEmail = '', cArc = false, cInit = '', cC1 = '', cC2 = '', cPhone = '';
const arcSet  = new Set();
const newSet  = new Set();

let soundOn    = true;
let browserNot = false;
let audioCtx   = null;

// Anti-spam
const COOLDOWN = 1400, BURST = 5;
let lastSend = 0, sendCnt = 0, cntReset = null, inFlight = false;

// Refresh
let refreshing = false;
let typingTimer = null;

/* ══════════════════════════════════
   SÉCURITÉ / UTILS
══════════════════════════════════ */
const esc = str => {
    if (!str && str !== 0) return '';
    const d = document.createElement('div');
    d.textContent = String(str);
    return d.innerHTML;
};

const clean = (str, max = 2000) =>
    String(str || '')
        .replace(/<[^>]*>/g, '')
        .replace(/[\x00-\x08\x0B\x0C\x0E-\x1F]/g, '')
        .substring(0, max)
        .trim();

const validMsg = s => {
    const t = clean(s);
    return t.length >= 1 && t.length <= 2000 && !/<[^>]>/.test(t);
};

const fmtTime = d => {
    if (!d) return '';
    try { return new Date(d).toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'}); }
    catch { return ''; }
};

const fmtDate = d => {
    if (!d) return '';
    try {
        const dt = new Date(d);
        const now = new Date();
        const diff = (now - dt) / 86400000;
        if (diff < 1) return "Aujourd'hui";
        if (diff < 2) return "Hier";
        return dt.toLocaleDateString('fr-FR',{day:'2-digit',month:'long'});
    } catch { return ''; }
};

/* ══════════════════════════════════
   AUDIO — Sons pro
══════════════════════════════════ */
function initAudio() {
    if (audioCtx) return;
    try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch {}
}
document.addEventListener('click', initAudio, { once: true });

function playSound(type = 'receive') {
    if (!soundOn) return;
    try {
        initAudio();
        if (!audioCtx) return;
        if (audioCtx.state === 'suspended') audioCtx.resume();
        const t = audioCtx.currentTime;

        if (type === 'receive') {
            // Son de notification entrant — deux notes harmonieuses
            const notes = [[880,t,0.16,t+0.25],[1100,t+0.14,0.11,t+0.36]];
            notes.forEach(([f,s,g,e]) => {
                const o = audioCtx.createOscillator();
                const gn = audioCtx.createGain();
                o.connect(gn); gn.connect(audioCtx.destination);
                o.type = 'sine'; o.frequency.value = f;
                gn.gain.setValueAtTime(0, s);
                gn.gain.linearRampToValueAtTime(g, s+0.03);
                gn.gain.exponentialRampToValueAtTime(0.0001, e);
                o.start(s); o.stop(e+0.05);
            });
        } else {
            // Son d'envoi — clic doux court
            const o = audioCtx.createOscillator();
            const gn = audioCtx.createGain();
            o.connect(gn); gn.connect(audioCtx.destination);
            o.type = 'sine'; o.frequency.value = 600;
            gn.gain.setValueAtTime(0, t);
            gn.gain.linearRampToValueAtTime(0.08, t+0.02);
            gn.gain.exponentialRampToValueAtTime(0.0001, t+0.14);
            o.start(t); o.stop(t+0.15);
        }
    } catch {}
}

/* ══════════════════════════════════
   TOAST NOTIFICATION PREMIUM
══════════════════════════════════ */
let toastT = null;
function showToast(name, msg, c1, c2) {
    const el = document.getElementById('msToast');
    if (!el) return;

    document.getElementById('toastName').textContent = String(name).substring(0, 40);
    document.getElementById('toastMsg').textContent  = String(msg  || '').substring(0, 70);

    const av = document.getElementById('toastAv');
    av.textContent      = String(name).substring(0, 2).toUpperCase();
    av.style.background = `linear-gradient(140deg,${c1},${c2})`;

    // Progress bar reset
    const prog = document.getElementById('toastProg');
    prog.className = 'ms-toast-prog';
    void prog.offsetWidth;
    prog.className = 'ms-toast-prog go';

    el.classList.add('show');
    playSound('receive');
    clearTimeout(toastT);
    toastT = setTimeout(hideToast, 5500);
}

function hideToast() {
    document.getElementById('msToast')?.classList.remove('show');
}
window.hideToast = hideToast;
document.getElementById('toastClose')?.addEventListener('click', hideToast);

/* ══════════════════════════════════
   NOTIFICATION NAVIGATEUR
══════════════════════════════════ */
function initBrowserNotif() {
    if (!('Notification' in window)) return;
    if (Notification.permission === 'granted') { browserNot = true; return; }
    if (Notification.permission !== 'denied') {
        document.getElementById('msPerm').style.display = 'flex';
    }
}

document.getElementById('permAllow')?.addEventListener('click', () => {
    Notification.requestPermission().then(p => {
        if (p === 'granted') { browserNot = true; }
        document.getElementById('msPerm')?.remove();
    });
});
document.getElementById('permDismiss')?.addEventListener('click', () => {
    document.getElementById('msPerm')?.remove();
});

function notif(name, msg) {
    if (!browserNot || document.hasFocus()) return;
    try { new Notification(`💬 ${name}`, { body: String(msg||'').substring(0,100), icon: '/images/logo.jpg' }); }
    catch {}
}

/* ══════════════════════════════════
   REALTIME BADGE
══════════════════════════════════ */
function setRT(state) {
    const b = document.getElementById('rtBadge');
    const l = document.getElementById('rtLabel');
    if (!b || !l) return;
    b.className = `ms-rt-badge ${state === 'connected' ? 'on' : state === 'disconnected' ? 'off' : ''}`;
    l.textContent = state === 'connected' ? 'Temps réel' : state === 'disconnected' ? 'Déconnecté' : 'Connexion…';
}

/* ══════════════════════════════════
   WARN ANTI-SPAM
══════════════════════════════════ */
function warn(txt) {
    const el = document.getElementById('msWarn');
    const t  = document.getElementById('msWarnTxt');
    if (!el || !t) return;
    t.textContent = txt;
    el.style.display = 'flex';
    clearTimeout(el._t);
    el._t = setTimeout(() => { el.style.display = 'none'; }, 4000);
}

function checkRL() {
    const now = Date.now();
    if (!cntReset) cntReset = setTimeout(() => { sendCnt = 0; cntReset = null; }, 30000);
    if (now - lastSend < COOLDOWN) {
        warn(`Attendez ${((COOLDOWN-(now-lastSend))/1000).toFixed(1)}s`);
        return false;
    }
    if (sendCnt >= BURST) {
        warn('Trop de messages. Attendez 30 secondes.');
        return false;
    }
    return true;
}

/* ══════════════════════════════════
   UNREAD MARKERS
══════════════════════════════════ */
function markNew(email) {
    newSet.add(email);
    const it = document.querySelector(`.ms-conv[data-email="${CSS.escape(email)}"]`);
    if (!it) return;
    it.classList.add('new-msg');
    // Badge sur avatar si pas déjà là
    if (!it.querySelector('.ms-conv-badge')) {
        const b = document.createElement('div');
        b.className = 'ms-conv-badge';
        it.querySelector('.ms-conv-av')?.appendChild(b);
    }
}

function clearNew(email) {
    newSet.delete(email);
    const it = document.querySelector(`.ms-conv[data-email="${CSS.escape(email)}"]`);
    if (!it) return;
    it.querySelector('.ms-conv-badge')?.remove();
}

/* ══════════════════════════════════
   CHARGER MESSAGES
══════════════════════════════════ */
async function loadConv(email) {
    if (!email) return;
    const box = document.getElementById('msMessages');
    box.innerHTML = `<div class="ms-loader">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>Chargement…</div>`;

    try {
        const r = await fetch(`/admin/messages/conversation/${encodeURIComponent(email)}`, {
            cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        const msgs = await r.json();

        if (!msgs?.length) {
            box.innerHTML = `<div style="text-align:center;padding:60px;font-size:13px;color:var(--text-muted)">Aucun message</div>`;
            return;
        }

        // Récupération téléphone
        if (msgs[0]?.telephone) {
            cPhone = msgs[0].telephone;
            const el = document.getElementById('infoPhone');
            if (el) el.textContent = cPhone;
        }

        renderMessages(msgs, box);

    } catch(e) {
        box.innerHTML = `<div style="text-align:center;padding:60px;font-size:13px;color:#ef4444">Erreur de chargement</div>`;
        console.warn('loadConv:', e);
    }
}

function renderMessages(msgs, box) {
    let html = '';
    let lastDate = '';

    for (const m of msgs) {
        // Séparateur de date
        const msgDate = fmtDate(m.created_at);
        if (msgDate !== lastDate) {
            html += `<div class="ms-date-sep"><span>${esc(msgDate)}</span></div>`;
            lastDate = msgDate;
        }

        const hasA = m.reponse_admin?.trim();
        const hasC = m.message?.trim();

        if (!hasC && hasA) {
            html += bubbleRight(m.reponse_admin, m.created_at);
        } else if (hasC) {
            html += bubbleLeft(m.nom_complet || 'Client', m.message, m.created_at);
            if (hasA) html += bubbleRight(m.reponse_admin, m.updated_at);
        }
    }

    box.innerHTML = html;
    box.scrollTop = box.scrollHeight;
}

function bubbleLeft(sender, text, time) {
    return `<div class="ms-row-l">
        <div class="ms-bub-l">
            <div class="ms-sender">${esc(sender)}</div>
            <div class="ms-txt">${esc(text)}</div>
            <div class="ms-time">${fmtTime(time)}</div>
        </div>
    </div>`;
}

function bubbleRight(text, time, extra = '') {
    return `<div class="ms-row-r${extra}">
        <div class="ms-bub-r">
            <div class="ms-sender-r">Vous</div>
            <div class="ms-txt-r">${esc(text)}</div>
            <div class="ms-time-r">
                <span>${fmtTime(time)}</span>
                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            </div>
        </div>
    </div>`;
}

/* ══════════════════════════════════
   ENVOI — Optimistic UI
══════════════════════════════════ */
async function sendMsg() {
    if (!cEmail || arcSet.has(cEmail) || inFlight) return;
    const ta  = document.getElementById('msInput');
    const raw = ta?.value || '';
    const msg = clean(raw);

    if (!validMsg(msg)) { if (msg) warn('Message invalide.'); return; }
    if (!checkRL()) return;

    inFlight  = true;
    lastSend  = Date.now();
    sendCnt++;

    const btn = document.getElementById('msSend');
    btn.disabled = true; btn.classList.add('loading');

    // ── OPTIMISTIC : afficher la bulle immédiatement ──
    const box = document.getElementById('msMessages');
    const optId = `opt-${Date.now()}`;
    const optHtml = `<div class="ms-row-r optimistic" id="${optId}">
        <div class="ms-bub-r">
            <div class="ms-sender-r">Vous</div>
            <div class="ms-txt-r">${esc(msg)}</div>
            <div class="ms-time-r"><span>Envoi…</span></div>
        </div>
    </div>`;
    box.insertAdjacentHTML('beforeend', optHtml);
    box.scrollTop = box.scrollHeight;

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

        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();

        if (data.success !== false) {
            // Remplacer la bulle optimiste par la vraie
            document.getElementById(optId)?.remove();
            await loadConv(cEmail);
            refreshList();
        } else {
            // Erreur serveur — marquer bulle en rouge
            const optEl = document.getElementById(optId);
            if (optEl) optEl.querySelector('.ms-bub-r')?.classList.add('error-bub');
            ta.value = raw; updateCount(raw.length);
            warn(data.message || 'Erreur envoi.');
        }

    } catch(e) {
        const optEl = document.getElementById(optId);
        if (optEl) optEl.querySelector('.ms-bub-r')?.classList.add('error-bub');
        ta.value = raw; updateCount(raw.length);
        warn('Erreur réseau.');
        console.error('sendMsg:', e);
    } finally {
        inFlight = false;
        btn.disabled = false; btn.classList.remove('loading');
    }
}

/* ══════════════════════════════════
   CHAR COUNT & TEXTAREA AUTO-RESIZE
══════════════════════════════════ */
function updateCount(n) {
    const el = document.getElementById('msCount');
    if (!el) return;
    el.textContent = n;
    el.style.color = n > 1900 ? '#ef4444' : n > 1500 ? 'var(--ms-orange)' : '';
}

const ta = document.getElementById('msInput');
ta?.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 140) + 'px';
    updateCount(this.value.length);
    if (this.value.length > 2000) this.value = this.value.substring(0, 2000);
});

ta?.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); }
});

document.getElementById('msSend')?.addEventListener('click', sendMsg);
document.getElementById('msBack')?.addEventListener('click', () => {
    document.getElementById('msPanel')?.classList.add('hide');
});

/* ══════════════════════════════════
   BOUTON SON
══════════════════════════════════ */
document.getElementById('btnSound')?.addEventListener('click', function () {
    soundOn = !soundOn;
    this.classList.toggle('s-on', soundOn);
    this.title = soundOn ? 'Son activé' : 'Son désactivé';
});

/* ══════════════════════════════════
   ARCHIVE
══════════════════════════════════ */
document.getElementById('btnArchive')?.addEventListener('click', () => {
    if (!cEmail) return;
    doArchive(cEmail, !cArc);
});

function updateArchBtn() {
    const b = document.getElementById('btnArchive');
    if (!b) return;
    b.title = cArc ? 'Désarchiver' : 'Archiver';
    b.classList.toggle('arc-on', cArc);
}

function doArchive(email, arc) {
    fetch('/admin/messages/toggle-close', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ email_client: email, closed: arc })
    })
    .then(r => r.json())
    .then(() => {
        if (arc) arcSet.add(email); else arcSet.delete(email);
        if (cEmail === email) {
            cArc = arc;
            document.getElementById('msCompose').style.display = arc ? 'none' : 'flex';
            updateArchBtn();
        }
        refreshList();
    })
    .catch(e => console.warn('archive:', e));
}

/* ══════════════════════════════════
   INFO PANEL
══════════════════════════════════ */
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

/* ══════════════════════════════════
   TABS
══════════════════════════════════ */
document.querySelectorAll('.ms-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        document.querySelectorAll('.ms-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        const f = this.dataset.filter;
        document.querySelectorAll('.ms-conv').forEach(it => {
            const arc = it.dataset.archived === '1';
            it.style.display = (f === 'all' && !arc) || (f === 'archived' && arc) ? '' : 'none';
        });
    });
});

/* ══════════════════════════════════
   ATTACHER EVENTS CONV ITEMS
══════════════════════════════════ */
function attachConvEvents() {
    document.querySelectorAll('.ms-conv').forEach(el => {
        const ne = el.cloneNode(true);
        el.parentNode.replaceChild(ne, el);

        ne.addEventListener('click', function () {
            document.querySelectorAll('.ms-conv').forEach(i => i.classList.remove('sel'));
            this.classList.add('sel');

            cEmail = this.dataset.email;
            cInit  = this.dataset.initials;
            cC1    = this.dataset.c1;
            cC2    = this.dataset.c2;
            cPhone = this.dataset.phone || '';
            cArc   = arcSet.has(cEmail);
            clearNew(cEmail);

            // Thread header
            const tav = document.getElementById('threadAv');
            if (tav) { tav.textContent = cInit; tav.style.background = `linear-gradient(145deg,${cC1},${cC2})`; }
            const tn = document.getElementById('threadName');
            const te = document.getElementById('threadEmail');
            if (tn) tn.textContent = this.dataset.name;
            if (te) te.textContent = cEmail;

            // Info panel
            const iav = document.getElementById('infoAv');
            if (iav) { iav.textContent = cInit; iav.style.background = `linear-gradient(145deg,${cC1},${cC2})`; }
            const in_name  = document.getElementById('infoName');
            const in_email = document.getElementById('infoEmail');
            const in_phone = document.getElementById('infoPhone');
            const in_st    = document.getElementById('infoStatus');
            if (in_name)  in_name.textContent  = this.dataset.name;
            if (in_email) in_email.textContent = cEmail;
            if (in_phone) in_phone.textContent = cPhone || 'Non renseigné';

            const isArc  = arcSet.has(cEmail);
            const hasRes = this.dataset.responded === '1';
            if (in_st) {
                in_st.className  = `ms-tag ${isArc ? 'arc' : (hasRes ? 'ok' : 'wait')}`;
                in_st.textContent = isArc ? 'Archivé' : (hasRes ? 'Répondu' : 'En attente');
            }

            // Afficher chat
            document.getElementById('msIdle').style.display   = 'none';
            document.getElementById('msThread').style.display = 'flex';
            document.getElementById('msCompose').style.display = cArc ? 'none' : 'flex';
            document.getElementById('msWarn').style.display   = 'none';
            updateArchBtn();
            loadConv(cEmail);

            // Mobile
            if (window.innerWidth <= 768) {
                document.getElementById('msPanel')?.classList.add('hide');
            }
        });
    });
}

/* ══════════════════════════════════
   REFRESH LISTE
══════════════════════════════════ */
function refreshList() {
    if (refreshing) return;
    refreshing = true;
    fetch(window.location.href, { cache: 'no-store' })
        .then(r => r.text())
        .then(html => {
            const doc   = new DOMParser().parseFromString(html, 'text/html');
            const newL  = doc.getElementById('msConvList');
            const oldL  = document.getElementById('msConvList');
            if (!newL || !oldL) return;

            const scroll = oldL.scrollTop;
            oldL.innerHTML = newL.innerHTML;
            attachConvEvents();

            // Restore sel
            if (cEmail) {
                const sel = oldL.querySelector(`.ms-conv[data-email="${CSS.escape(cEmail)}"]`);
                sel?.classList.add('sel');
            }
            // Restore archived states
            oldL.querySelectorAll('.ms-conv').forEach(el => {
                const em = el.dataset.email;
                if (el.dataset.archived === '1') arcSet.add(em); else arcSet.delete(em);
                if (newSet.has(em)) markNew(em);
            });
            oldL.scrollTop = scroll;
        })
        .catch(e => console.warn('refreshList:', e))
        .finally(() => { refreshing = false; });
}

/* ══════════════════════════════════
   PUSHER — TEMPS RÉEL
══════════════════════════════════ */
function initPusher() {
    if (typeof Pusher === 'undefined') { setTimeout(initPusher, 500); return; }

    const key     = '{{ env("PUSHER_APP_KEY") }}';
    const cluster = '{{ env("PUSHER_APP_CLUSTER") }}';
    if (!key) { console.error('Pusher key manquante'); return; }

    const pusher  = new Pusher(key, { cluster, encrypted: true });
    const channel = pusher.subscribe('new-messages');

    pusher.connection.bind('connected',    () => setRT('connected'));
    pusher.connection.bind('disconnected', () => setRT('disconnected'));
    pusher.connection.bind('connecting',   () => setRT('connecting'));

    channel.bind('App\\Events\\NewMessageReceived', function (data) {
        const email = data.email_client;
        const name  = data.nom_complet || email;
        const msg   = data.message || 'Nouveau message';

        // Refresh liste dans tous les cas
        refreshList();

        if (email !== cEmail) {
            // Autre conversation — notifier
            markNew(email);
            const it = document.querySelector(`.ms-conv[data-email="${CSS.escape(email)}"]`);
            const c1 = it?.dataset.c1 || '#2563a8';
            const c2 = it?.dataset.c2 || '#3b82c4';
            showToast(name, msg, c1, c2);
            notif(name, msg);
        } else {
            // Conversation active — rafraîchir immédiatement
            loadConv(cEmail);
        }
    });
}

/* ══════════════════════════════════
   INIT
══════════════════════════════════ */
attachConvEvents();
initPusher();
initBrowserNotif();

// Fallback refresh toutes les 30s
setInterval(refreshList, 30000);

})();
</script>

@endsection