@extends('layouts.admin')

@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Aperçu général — ' . now()->translatedFormat('l d F Y'))

@section('content')

@php
    // ── Stats globales ──────────────────────────────────────────
    $totalCatalogues    = \App\Models\Catalogue::count();
    $totalFormations    = \App\Models\Formation::count();
    $totalAvis          = \App\Models\Avis::count();
    $noteMoyenne        = round(\App\Models\Avis::avg('note') ?? 0, 1);
    $totalPartenaires   = \App\Models\Partenaire::count();
    $totalArticles      = \App\Models\Article::count();
    $messagesNonLus     = \App\Models\Message::where('lu', false)->count();
    $messagesNonRepondus = \App\Models\Message::where(function($q){
        $q->whereNull('reponse_admin')->orWhere('reponse_admin','');
    })->count();

    // ── Activités récentes (limité à 12 pour filtrage) ──
    $acts = collect();

    \App\Models\Catalogue::latest()->take(3)->get()->each(fn($i) => $acts->push([
        'type' => 'catalogue', 'color1' => '#2563a8', 'color2' => '#3b82c4',
        'label' => 'Catalogue', 'icon' => 'book',
        'title' => $i->titre, 'action' => 'ajouté',
        'date' => $i->created_at, 'url' => route('admin.catalogues.edit', $i),
    ]));

    \App\Models\Formation::latest()->take(3)->get()->each(fn($i) => $acts->push([
        'type' => 'formation', 'color1' => '#1a8fa0', 'color2' => '#2d9c4f',
        'label' => 'Formation', 'icon' => 'calendar',
        'title' => $i->titre, 'action' => 'ajoutée',
        'date' => $i->created_at, 'url' => route('admin.formations.edit', $i),
    ]));

    \App\Models\Article::latest()->take(2)->get()->each(fn($i) => $acts->push([
        'type' => 'article', 'color1' => '#7c3aed', 'color2' => '#d63384',
        'label' => 'Article', 'icon' => 'pen',
        'title' => $i->titre, 'action' => $i->publie ? 'publié' : 'brouillon',
        'date' => $i->created_at, 'url' => route('admin.articles.edit', $i),
    ]));

    \App\Models\Avis::latest()->take(2)->get()->each(fn($i) => $acts->push([
        'type' => 'avis', 'color1' => '#e8722a', 'color2' => '#d63384',
        'label' => 'Avis', 'icon' => 'star',
        'title' => $i->entreprise_nom, 'action' => 'avis '.$i->note.'/5',
        'date' => $i->created_at, 'url' => route('admin.avis.edit', $i),
    ]));

    \App\Models\Partenaire::latest()->take(2)->get()->each(fn($i) => $acts->push([
        'type' => 'partenaire', 'color1' => '#2d9c4f', 'color2' => '#1a8fa0',
        'label' => 'Partenaire', 'icon' => 'handshake',
        'title' => $i->nom_entreprise, 'action' => 'ajouté',
        'date' => $i->created_at, 'url' => route('admin.partenaires.edit', $i),
    ]));

    \App\Models\Message::whereNotNull('reponse_admin')
        ->where('reponse_admin','!=','')
        ->latest('updated_at')->take(2)->get()->each(fn($i) => $acts->push([
            'type' => 'message', 'color1' => '#2563a8', 'color2' => '#7c3aed',
            'label' => 'Message', 'icon' => 'reply',
            'title' => $i->nom_complet ?? $i->email_client, 'action' => 'réponse envoyée',
            'date' => $i->updated_at, 'url' => route('admin.messages'),
        ]));

    $allActivities = $acts->sortByDesc('date')->take(12)->values();

    // ── Messages urgents (limité à 6 visibles avec scroll) ──
    $messagesUrgents = \App\Models\Message::where(function($q){
        $q->whereNull('reponse_admin')->orWhere('reponse_admin','');
    })->latest()->take(10)->get();

    // ── Score santé site ─────────────────────────────────────────
    $healthScore = 0;
    if($totalCatalogues >= 5) $healthScore += 20;
    if($totalFormations >= 3) $healthScore += 20;
    if($totalAvis >= 5) $healthScore += 20;
    if($totalPartenaires >= 3) $healthScore += 15;
    if($totalArticles >= 2) $healthScore += 15;
    if($messagesNonRepondus == 0) $healthScore += 10;
    $healthColor = $healthScore >= 80 ? '#2d9c4f' : ($healthScore >= 50 ? '#e8722a' : '#ef4444');
    $healthLabel = $healthScore >= 80 ? 'Excellent' : ($healthScore >= 50 ? 'Moyen' : 'À améliorer');
@endphp

{{-- QUICK ACTIONS BAR --}}
<div class="db-quick-bar">
    <span class="db-quick-label">Accès rapide :</span>
    <a href="{{ route('admin.catalogues.create') }}" class="db-quick-btn blue">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Catalogue
    </a>
    <a href="{{ route('admin.formations.create') }}" class="db-quick-btn teal">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Formation
    </a>
    <a href="{{ route('admin.articles.create') }}" class="db-quick-btn violet">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Article
    </a>
    <a href="{{ route('admin.messages') }}" class="db-quick-btn {{ $messagesNonRepondus > 0 ? 'urgent' : 'muted' }}">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        Messages{{ $messagesNonRepondus > 0 ? ' ('.$messagesNonRepondus.')' : '' }}
    </a>
</div>

{{-- STATS CARDS --}}
<div class="db-stats-grid">
    <div class="db-stat-card" style="--c1:#2563a8;--c2:#3b82c4;">
        <div class="db-stat-icon" style="background:rgba(37,99,168,0.12);color:#2563a8;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">Catalogues</div>
            <div class="db-stat-num">{{ $totalCatalogues }}</div>
        </div>
    </div>
    <div class="db-stat-card" style="--c1:#1a8fa0;--c2:#2d9c4f;">
        <div class="db-stat-icon" style="background:rgba(26,143,160,0.12);color:#1a8fa0;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">Formations</div>
            <div class="db-stat-num">{{ $totalFormations }}</div>
        </div>
    </div>
    <div class="db-stat-card" style="--c1:#e8722a;--c2:#d63384;">
        <div class="db-stat-icon" style="background:rgba(232,114,42,0.12);color:#e8722a;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">Avis</div>
            <div class="db-stat-num">{{ $totalAvis }}<span style="font-size:10px;"> ⭐{{ $noteMoyenne }}</span></div>
        </div>
    </div>
    <div class="db-stat-card {{ $messagesNonRepondus > 0 ? 'db-stat-urgent' : '' }}" style="--c1:#ef4444;--c2:#d63384;">
        <div class="db-stat-icon" style="background:rgba(239,68,68,0.12);color:#ef4444;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">En attente</div>
            <div class="db-stat-num" style="{{ $messagesNonRepondus > 0 ? 'color:#ef4444' : '' }}">{{ $messagesNonRepondus }}</div>
        </div>
        @if($messagesNonRepondus > 0) <div class="db-stat-pulse"></div> @endif
    </div>
    <div class="db-stat-card" style="--c1:#7c3aed;--c2:#2563a8;">
        <div class="db-stat-icon" style="background:rgba(124,58,237,0.12);color:#7c3aed;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">Articles</div>
            <div class="db-stat-num">{{ $totalArticles }}</div>
        </div>
    </div>
    <div class="db-stat-card" style="--c1:#2d9c4f;--c2:#1a8fa0;">
        <div class="db-stat-icon" style="background:rgba(45,156,79,0.12);color:#2d9c4f;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">Partenaires</div>
            <div class="db-stat-num">{{ $totalPartenaires }}</div>
        </div>
    </div>
</div>

{{-- MAIN GRID OPTIMISÉ --}}
<div class="db-main-grid">
    {{-- COLONNE GAUCHE --}}
    <div class="db-left-col">
        {{-- Activités récentes (RÉDUIT À 4 VISIBLES) --}}
        <div class="db-card">
            <div class="db-card-head">
                <div>
                    <div class="db-card-title">
                        <div class="db-card-title-dot" style="background:linear-gradient(135deg,#2563a8,#d63384)"></div>
                        Activités récentes
                    </div>
                    <div class="db-card-sub">Dernières actions</div>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <select id="activityDateFilter" class="db-date-filter">
                        <option value="1">Aujourd'hui</option>
                        <option value="7">7j</option>
                        <option value="15">15j</option>
                        <option value="30" selected>30j</option>
                        <option value="all">Tout</option>
                    </select>
                    <div class="db-card-badge" id="activityCount">{{ $allActivities->count() }}</div>
                </div>
            </div>
            <div id="activityList" class="db-activity-list">
                @forelse($allActivities as $act)
                <a href="{{ $act['url'] }}" class="db-activity-item" data-date="{{ $act['date']->toISOString() }}">
                    <div class="db-act-avatar" style="background:linear-gradient(135deg,{{ $act['color1'] }},{{ $act['color2'] }})">
                        @if($act['icon'] === 'book')
                            <svg width="11" height="11" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @elseif($act['icon'] === 'calendar')
                            <svg width="11" height="11" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @elseif($act['icon'] === 'pen')
                            <svg width="11" height="11" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        @elseif($act['icon'] === 'star')
                            <svg width="11" height="11" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        @elseif($act['icon'] === 'handshake')
                            <svg width="11" height="11" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        @else
                            <svg width="11" height="11" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        @endif
                    </div>
                    <div class="db-act-body">
                        <div class="db-act-title">{{ Str::limit($act['title'], 35) }}</div>
                        <div class="db-act-meta">
                            <span class="db-act-type-chip">{{ $act['label'] }}</span>
                            <span>{{ $act['action'] }}</span>
                            <span class="act-relative-time" data-date="{{ $act['date']->toISOString() }}">{{ $act['date']->diffForHumans() }}</span>
                        </div>
                    </div>
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="db-act-arrow"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @empty
                <div class="db-empty">Aucune activité</div>
                @endforelse
            </div>
        </div>

        {{-- Progression globale (MAINTENANT PLUS VISIBLE) --}}
        <div class="db-card db-progress-compact">
            <div class="db-card-head">
                <div>
                    <div class="db-card-title">
                        <div class="db-card-title-dot" style="background:linear-gradient(135deg,#2d9c4f,#1a8fa0)"></div>
                        Objectifs
                    </div>
                </div>
            </div>
            <div class="db-progress-list">
                <div class="db-progress-item">
                    <div class="db-progress-head"><span>📚 Catalogues</span><span>{{ $totalCatalogues }}/20</span></div>
                    <div class="db-prog-bar-wrap"><div class="db-prog-bar" style="--w:{{ min(100,round($totalCatalogues/20*100)) }}%;--c1:#2563a8;--c2:#3b82c4;"></div></div>
                </div>
                <div class="db-progress-item">
                    <div class="db-progress-head"><span>📅 Formations</span><span>{{ $totalFormations }}/15</span></div>
                    <div class="db-prog-bar-wrap"><div class="db-prog-bar" style="--w:{{ min(100,round($totalFormations/15*100)) }}%;--c1:#1a8fa0;--c2:#2d9c4f;"></div></div>
                </div>
                <div class="db-progress-item">
                    <div class="db-progress-head"><span>⭐ Avis</span><span>{{ $totalAvis }}/30</span></div>
                    <div class="db-prog-bar-wrap"><div class="db-prog-bar" style="--w:{{ min(100,round($totalAvis/30*100)) }}%;--c1:#e8722a;--c2:#d63384;"></div></div>
                </div>
                <div class="db-progress-item">
                    <div class="db-progress-head"><span>📝 Articles</span><span>{{ $totalArticles }}/10</span></div>
                    <div class="db-prog-bar-wrap"><div class="db-prog-bar" style="--w:{{ min(100,round($totalArticles/10*100)) }}%;--c1:#7c3aed;--c2:#2563a8;"></div></div>
                </div>
                <div class="db-progress-item">
                    <div class="db-progress-head"><span>🤝 Partenaires</span><span>{{ $totalPartenaires }}/20</span></div>
                    <div class="db-prog-bar-wrap"><div class="db-prog-bar" style="--w:{{ min(100,round($totalPartenaires/20*100)) }}%;--c1:#2d9c4f;--c2:#1a8fa0;"></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- COLONNE DROITE --}}
    <div class="db-right-col">
        {{-- Admin card compacte --}}
        <div class="db-admin-card">
            <div class="db-admin-glow"></div>
            <div class="db-admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="db-admin-name">{{ auth()->user()->name }}</div>
            <div class="db-admin-email">{{ auth()->user()->email }}</div>
            <div class="db-admin-role">Admin</div>
            <div class="db-admin-stats">
                <div><span>{{ $totalCatalogues }}</span><label>Catalogues</label></div>
                <div><span>{{ $totalFormations }}</span><label>Formations</label></div>
                <div><span>{{ $totalArticles }}</span><label>Articles</label></div>
            </div>
            <div class="db-health-row">
                <span>Santé</span>
                <span style="color:{{ $healthColor }}">{{ $healthScore }}% — {{ $healthLabel }}</span>
            </div>
            <div class="db-health-bar-wrap"><div class="db-health-bar" style="width:{{ $healthScore }}%;background:{{ $healthColor }}"></div></div>
        </div>

        {{-- Messages urgents (RÉDUIT À 2 VISIBLES + SCROLL) --}}
        <div class="db-card" id="urgentMessagesCard">
            <div class="db-card-head">
                <div>
                    <div class="db-card-title">
                        <div class="db-card-title-dot" style="background:linear-gradient(135deg,#ef4444,#d63384)"></div>
                        En attente
                    </div>
                    <div class="db-card-sub">Priorité</div>
                </div>
                <a href="{{ route('admin.messages') }}" class="db-card-action">Voir</a>
            </div>
            <div class="db-urgent-list">
                @forelse($messagesUrgents as $msg)
                @php
                    $colors = [['#2563a8','#3b82c4'],['#d63384','#e8722a'],['#2d9c4f','#1a8fa0'],['#7c3aed','#d63384']];
                    $cp = $colors[$loop->index % count($colors)];
                    $initials = collect(explode(' ', $msg->nom_complet ?? ''))->take(2)->map(fn($w) => strtoupper(substr($w,0,1)))->join('');
                @endphp
                <div class="db-msg-item" onclick="window.location.href='{{ route('admin.messages') }}?email={{ urlencode($msg->email_client) }}'" data-email="{{ e($msg->email_client) }}">
                    <div class="db-msg-avatar" style="background:linear-gradient(135deg,{{ $cp[0] }},{{ $cp[1] }})">{{ $initials ?: substr($msg->email_client,0,1) }}</div>
                    <div class="db-msg-body">
                        <div class="db-msg-name">{{ $msg->nom_complet ?? $msg->email_client }}</div>
                        <div class="db-msg-preview">{{ Str::limit(strip_tags($msg->message), 30) }}</div>
                        <div class="db-msg-time">{{ $msg->created_at->diffForHumans() }}</div>
                    </div>
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="db-msg-arrow"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
                @empty
                <div class="db-empty db-empty-green">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Traités ✓</span>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Infos site (remonté) --}}
        <div class="db-card">
            <div class="db-card-head">
                <div>
                    <div class="db-card-title">
                        <div class="db-card-title-dot" style="background:linear-gradient(135deg,#2563a8,#1a8fa0)"></div>
                        Infos site
                    </div>
                </div>
            </div>
            <div class="db-info-rows">
                <div class="db-info-row"><div class="db-info-icon violet">📝</div><span>Articles</span><span>{{ $totalArticles }}</span></div>
                <div class="db-info-row"><div class="db-info-icon green">🤝</div><span>Partenaires</span><span>{{ $totalPartenaires }}</span></div>
                <div class="db-info-row"><div class="db-info-icon red">💬</div><span>Non lus</span><span style="{{ $messagesNonLus > 0 ? 'color:#ef4444;font-weight:700' : '' }}">{{ $messagesNonLus }}</span></div>
                <div class="db-info-row"><div class="db-info-icon orange">⭐</div><span>Note avis</span><span>{{ $noteMoyenne }}/5</span></div>
            </div>
        </div>
    </div>
</div>

<style>
/* ── Quick bar ── */
.db-quick-bar { display: flex; align-items: center; gap: 6px; margin-bottom: 14px; flex-wrap: wrap; }
.db-quick-label { font-size: 10px; font-weight: 600; color: var(--text-muted,#94a3b8); }
.db-quick-btn { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 16px; font-size: 10.5px; font-weight: 600; text-decoration: none; transition: all 0.15s ease; border: 1px solid transparent; }
.db-quick-btn.blue { background: rgba(37,99,168,0.1); color: #2563a8; }
.db-quick-btn.teal { background: rgba(26,143,160,0.1); color: #1a8fa0; }
.db-quick-btn.violet { background: rgba(124,58,237,0.1); color: #7c3aed; }
.db-quick-btn.muted { background: var(--bg-surface-2,#f8fafc); color: var(--text-secondary,#4a5568); border-color: var(--border-color,#e2e8f0); }
.db-quick-btn.urgent { background: rgba(239,68,68,0.1); color: #ef4444; animation: pulse-urgent 1.8s infinite; }
@keyframes pulse-urgent { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,0.3)} 50%{box-shadow:0 0 0 3px rgba(239,68,68,0)} }
.db-quick-btn:hover { transform: translateY(-1px); }

/* ── Stats grid ── */
.db-stats-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px; margin-bottom: 14px; }
.db-stat-card { background: var(--bg-surface,#fff); border: 1px solid var(--border-color,#e2e8f0); border-radius: 12px; padding: 10px 10px 8px; position: relative; transition: all 0.18s ease; }
.db-stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, var(--c1), var(--c2)); border-radius: 12px 12px 0 0; }
.db-stat-card:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.db-stat-icon { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 4px; }
.db-stat-label { font-size: 9px; color: var(--text-muted,#94a3b8); font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px; }
.db-stat-num { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: var(--text-primary,#0f1923); line-height: 1.2; }
.db-stat-pulse { position: absolute; top: 6px; right: 6px; width: 6px; height: 6px; border-radius: 50%; background: #ef4444; animation: pulse-dot 1.5s infinite; }
@keyframes pulse-dot { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.4);opacity:0.5} }

/* ── Main grid ── */
.db-main-grid { display: grid; grid-template-columns: 1fr 280px; gap: 12px; align-items: start; }
.db-left-col, .db-right-col { display: flex; flex-direction: column; gap: 12px; }
.db-right-col { position: sticky; top: 70px; }

/* ── Cards ── */
.db-card { background: var(--bg-surface,#fff); border: 1px solid var(--border-color,#e2e8f0); border-radius: 14px; overflow: hidden; }
.db-card-head { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-bottom: 1px solid var(--border-subtle,#eef2f7); background: var(--bg-surface-2,#f8fafc); }
.db-card-title { font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 5px; }
.db-card-title-dot { width: 5px; height: 5px; border-radius: 50%; }
.db-card-sub { font-size: 9px; color: var(--text-muted,#94a3b8); margin-top: 1px; }
.db-card-badge { font-size: 9px; font-weight: 600; background: rgba(37,99,168,0.1); color: #2563a8; padding: 2px 6px; border-radius: 12px; }
.db-card-action { font-size: 9.5px; font-weight: 600; color: #2563a8; background: rgba(37,99,168,0.08); padding: 3px 8px; border-radius: 12px; text-decoration: none; }

/* ── Activity list (RÉDUIT À 4 VISIBLES + SCROLL) ── */
.db-activity-list { max-height: 180px; overflow-y: auto; padding: 6px 10px; display: flex; flex-direction: column; gap: 2px; }
.db-activity-item { display: flex; align-items: center; gap: 8px; padding: 6px 5px; border-radius: 8px; text-decoration: none; transition: all 0.12s ease; }
.db-activity-item:hover { background: var(--bg-surface-2,#f8fafc); transform: translateX(2px); }
.db-act-avatar { width: 24px; height: 24px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.db-act-body { flex: 1; min-width: 0; }
.db-act-title { font-size: 10.5px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.db-act-meta { display: flex; align-items: center; gap: 3px; margin-top: 1px; font-size: 8.5px; color: var(--text-muted,#94a3b8); flex-wrap: wrap; }
.db-act-type-chip { font-size: 8px; font-weight: 700; padding: 1px 4px; border-radius: 10px; background: rgba(37,99,168,0.1); color: #2563a8; }
.db-act-arrow { opacity: 0; transition: opacity 0.12s; flex-shrink: 0; }
.db-activity-item:hover .db-act-arrow { opacity: 1; }

/* ── Scrollbar ── */
.db-activity-list::-webkit-scrollbar, .db-urgent-list::-webkit-scrollbar { width: 3px; }
.db-activity-list::-webkit-scrollbar-track, .db-urgent-list::-webkit-scrollbar-track { background: var(--border-subtle, #eef2f7); border-radius: 3px; }
.db-activity-list::-webkit-scrollbar-thumb, .db-urgent-list::-webkit-scrollbar-thumb { background: var(--text-muted, #94a3b8); border-radius: 3px; }

/* ── Date filter AMÉLIORÉ ── */
.db-date-filter { font-size: 9px; padding: 2px 5px; border-radius: 12px; background: var(--bg-surface-2,#f8fafc); border: 1px solid var(--border-color,#e2e8f0); cursor: pointer; transition: all 0.15s ease; }
.db-date-filter:hover { border-color: #2563a8; }
.db-date-filter:focus { outline: none; border-color: #2563a8; box-shadow: 0 0 0 2px rgba(37,99,168,0.1); }

/* ── Progress compact ── */
.db-progress-list { padding: 8px 12px; display: flex; flex-direction: column; gap: 8px; }
.db-progress-item { font-size: 10px; }
.db-progress-head { display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 9.5px; }
.db-progress-head span:first-child { font-weight: 500; color: var(--text-secondary,#4a5568); }
.db-progress-head span:last-child { font-weight: 600; color: var(--text-primary,#0f1923); }
.db-prog-bar-wrap { height: 3px; background: var(--border-color,#e2e8f0); border-radius: 4px; overflow: hidden; }
.db-prog-bar { height: 100%; border-radius: 4px; background: linear-gradient(90deg, var(--c1), var(--c2)); width: 0; animation: growBar 0.6s ease forwards; }
@keyframes growBar { from{width:0} to{width:var(--w)} }

/* ── Admin card ultra compact ── */
.db-admin-card { background: linear-gradient(135deg, #0f1923 0%, #1a2d44 50%, #1e1b4b 100%); border-radius: 14px; padding: 12px 12px 10px; text-align: center; position: relative; overflow: hidden; }
.db-admin-glow { position: absolute; top: -20px; left: 50%; transform: translateX(-50%); width: 120px; height: 120px; background: radial-gradient(circle, rgba(37,99,168,0.3), transparent 70%); pointer-events: none; }
.db-admin-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #2563a8, #d63384, #e8722a); display: flex; align-items: center; justify-content: center; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 800; color: white; margin: 0 auto 6px; border: 2px solid rgba(255,255,255,0.15); }
.db-admin-name { font-size: 11px; font-weight: 700; color: white; }
.db-admin-email { font-size: 8px; color: rgba(255,255,255,0.5); }
.db-admin-role { font-size: 8px; font-weight: 700; background: rgba(37,99,168,0.35); color: #93c5fd; padding: 1px 6px; border-radius: 12px; display: inline-block; margin-top: 4px; }
.db-admin-stats { display: flex; justify-content: space-around; margin-top: 10px; padding-top: 8px; border-top: 1px solid rgba(255,255,255,0.08); }
.db-admin-stats > div { text-align: center; }
.db-admin-stats span { font-size: 13px; font-weight: 800; color: white; display: block; font-family: 'Outfit', sans-serif; }
.db-admin-stats label { font-size: 7px; color: rgba(255,255,255,0.5); display: block; margin-top: 2px; }
.db-health-row { display: flex; justify-content: space-between; margin-top: 8px; padding-top: 6px; border-top: 1px solid rgba(255,255,255,0.08); font-size: 9px; color: rgba(255,255,255,0.6); }
.db-health-bar-wrap { height: 2px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden; margin-top: 5px; }
.db-health-bar { height: 100%; border-radius: 4px; transition: width 0.6s ease; }

/* ── Messages urgents (RÉDUIT À 2 VISIBLES + SCROLL) ── */
.db-urgent-list { max-height: 140px; overflow-y: auto; }
.db-msg-item { display: flex; align-items: center; gap: 8px; padding: 8px 12px; cursor: pointer; border-bottom: 1px solid var(--border-subtle,#eef2f7); transition: all 0.12s ease; }
.db-msg-item:last-child { border-bottom: none; }
.db-msg-item:hover { background: var(--bg-surface-2,#f8fafc); transform: translateX(2px); }
.db-msg-avatar { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: 700; flex-shrink: 0; }
.db-msg-body { flex: 1; min-width: 0; }
.db-msg-name { font-size: 10.5px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.db-msg-preview { font-size: 9px; color: var(--text-muted,#94a3b8); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px; }
.db-msg-time { font-size: 8px; color: #e8722a; font-weight: 600; margin-top: 1px; }
.db-msg-arrow { opacity: 0; transition: opacity 0.12s; flex-shrink: 0; }
.db-msg-item:hover .db-msg-arrow { opacity: 1; }

/* ── Info rows compact ── */
.db-info-rows { padding: 6px 12px 8px; }
.db-info-row { display: flex; align-items: center; gap: 8px; padding: 6px 0; border-bottom: 1px solid var(--border-subtle,#eef2f7); font-size: 10px; }
.db-info-row:last-child { border-bottom: none; }
.db-info-icon { width: 22px; font-size: 11px; flex-shrink: 0; }
.db-info-row span:nth-child(2) { flex: 1; color: var(--text-secondary,#4a5568); font-weight: 500; }
.db-info-row span:nth-child(3) { font-weight: 700; color: var(--text-primary,#0f1923); }
.violet { color: #7c3aed; } .green { color: #2d9c4f; } .red { color: #ef4444; } .orange { color: #e8722a; }

/* ── Empty states ── */
.db-empty { text-align: center; padding: 16px; color: var(--text-muted,#94a3b8); font-size: 10px; }
.db-empty-green { display: flex; flex-direction: column; align-items: center; gap: 4px; color: #2d9c4f !important; padding: 12px !important; }
.db-empty-green svg { opacity: 0.6; }

/* ── Responsive ── */
@media (max-width: 1100px) { .db-stats-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 900px) { .db-main-grid { grid-template-columns: 1fr; } .db-right-col { position: static; } }
@media (max-width: 500px) { .db-stats-grid { grid-template-columns: repeat(2, 1fr); } }
</style>

<script>
(function () {
    'use strict';
    
    /* ── Refresh messages urgents toutes les 30s ── */
    setInterval(function () {
        fetch(window.location.href, { cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newCard = doc.getElementById('urgentMessagesCard');
                const oldCard = document.getElementById('urgentMessagesCard');
                if (newCard && oldCard && newCard.innerHTML !== oldCard.innerHTML) {
                    oldCard.innerHTML = newCard.innerHTML;
                    oldCard.querySelectorAll('.db-msg-item[data-email]').forEach(el => {
                        const email = el.dataset.email;
                        if (email) el.onclick = () => window.location.href = '{{ route("admin.messages") }}?email=' + encodeURIComponent(email);
                    });
                }
            }).catch(() => {});
    }, 30000);

    /* ── FILTRAGE AMÉLIORÉ DES ACTIVITÉS ── */
    const dateFilter = document.getElementById('activityDateFilter');
    const activityList = document.getElementById('activityList');
    const activityCount = document.getElementById('activityCount');
    
    if (dateFilter && activityList) {
        const items = Array.from(activityList.querySelectorAll('.db-activity-item'));
        const itemsWithDates = items.map(item => {
            const dateAttr = item.getAttribute('data-date');
            return { element: item, date: dateAttr ? new Date(dateAttr) : null };
        });

        function filterActivities(filterValue) {
            const now = new Date();
            let visible = 0;
            
            itemsWithDates.forEach(({ element, date }) => {
                let show = false;

                if (!date) {
                    show = true;
                } else if (filterValue === 'all') {
                    show = true;
                } else if (filterValue === '1') {
                    // Aujourd'hui
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const itemDate = new Date(date);
                    itemDate.setHours(0, 0, 0, 0);
                    show = itemDate.getTime() === today.getTime();
                } else {
                    // Jours
                    const daysDiff = (now - date) / (1000 * 60 * 60 * 24);
                    show = daysDiff <= parseInt(filterValue);
                }

                element.style.display = show ? 'flex' : 'none';
                if (show) visible++;
            });

            activityCount.textContent = visible;
            
            const emptyMsg = activityList.querySelector('.db-empty-list');
            if (visible === 0 && !emptyMsg) {
                const msg = document.createElement('div');
                msg.className = 'db-empty db-empty-list';
                msg.textContent = 'Aucune activité';
                activityList.appendChild(msg);
            } else if (visible > 0 && emptyMsg) {
                emptyMsg.remove();
            }
        }

        dateFilter.addEventListener('change', (e) => filterActivities(e.target.value));
        filterActivities(dateFilter.value);
    }
})();
</script>

@endsection