@extends('layouts.admin')

@section('page-title', 'Tableau de bord')
@section('page-subtitle', now()->translatedFormat('l d F Y'))

@section('content')

@php
    $totalCatalogues     = \App\Models\Catalogue::count();
    $totalFormations     = \App\Models\Formation::count();
    $totalAvis           = \App\Models\Avis::count();
    $noteMoyenne         = round(\App\Models\Avis::avg('note') ?? 0, 1);
    $totalPartenaires    = \App\Models\Partenaire::count();
    $totalArticles       = \App\Models\Article::count();
    $messagesNonLus      = \App\Models\Message::where('lu', false)->count();
    $messagesNonRepondus = \App\Models\Message::where(function($q){
        $q->whereNull('reponse_admin')->orWhere('reponse_admin','');
    })->count();

    $acts = collect();
    \App\Models\Catalogue::latest()->take(3)->get()->each(fn($i) => $acts->push(['type'=>'catalogue','color1'=>'#2563a8','color2'=>'#3b82c4','label'=>'Catalogue','icon'=>'book','title'=>$i->titre,'action'=>'ajouté','date'=>$i->created_at,'url'=>route('admin.catalogues.edit',$i)]));
    \App\Models\Formation::latest()->take(3)->get()->each(fn($i) => $acts->push(['type'=>'formation','color1'=>'#1a8fa0','color2'=>'#2d9c4f','label'=>'Formation','icon'=>'calendar','title'=>$i->titre,'action'=>'ajoutée','date'=>$i->created_at,'url'=>route('admin.formations.edit',$i)]));
    \App\Models\Article::latest()->take(2)->get()->each(fn($i) => $acts->push(['type'=>'article','color1'=>'#7c3aed','color2'=>'#d63384','label'=>'Article','icon'=>'pen','title'=>$i->titre,'action'=>$i->publie?'publié':'brouillon','date'=>$i->created_at,'url'=>route('admin.articles.edit',$i)]));
    \App\Models\Avis::latest()->take(2)->get()->each(fn($i) => $acts->push(['type'=>'avis','color1'=>'#e8722a','color2'=>'#d63384','label'=>'Avis','icon'=>'star','title'=>$i->entreprise_nom,'action'=>'note '.$i->note.'/5','date'=>$i->created_at,'url'=>route('admin.avis.edit',$i)]));
    \App\Models\Partenaire::latest()->take(2)->get()->each(fn($i) => $acts->push(['type'=>'partenaire','color1'=>'#2d9c4f','color2'=>'#1a8fa0','label'=>'Partenaire','icon'=>'handshake','title'=>$i->nom_entreprise,'action'=>'ajouté','date'=>$i->created_at,'url'=>route('admin.partenaires.edit',$i)]));
    \App\Models\Message::whereNotNull('reponse_admin')->where('reponse_admin','!=','')->latest('updated_at')->take(2)->get()->each(fn($i) => $acts->push(['type'=>'message','color1'=>'#2563a8','color2'=>'#7c3aed','label'=>'Message','icon'=>'reply','title'=>$i->nom_complet??$i->email_client,'action'=>'réponse envoyée','date'=>$i->updated_at,'url'=>route('admin.messages')]));
    $allActivities = $acts->sortByDesc('date')->take(12)->values();

    $messagesUrgents = \App\Models\Message::where(function($q){ $q->whereNull('reponse_admin')->orWhere('reponse_admin',''); })->latest()->take(8)->get();

    $healthScore = 0;
    if($totalCatalogues >= 5)   $healthScore += 20;
    if($totalFormations >= 3)   $healthScore += 20;
    if($totalAvis >= 5)         $healthScore += 20;
    if($totalPartenaires >= 3)  $healthScore += 15;
    if($totalArticles >= 2)     $healthScore += 15;
    if($messagesNonRepondus==0) $healthScore += 10;
    $healthColor = $healthScore>=80 ? '#2d9c4f' : ($healthScore>=50 ? '#e8722a' : '#ef4444');
    $healthLabel = $healthScore>=80 ? 'Excellent' : ($healthScore>=50 ? 'Correct' : 'À améliorer');
@endphp

{{-- ═══════════════════════════════════════
     BARRE ACCÈS RAPIDE
═══════════════════════════════════════ --}}
<div class="dv-topbar">
    <div class="dv-topbar-left">
        <div class="dv-greet-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <span>Bienvenue,</span>
        <strong>{{ auth()->user()->name }}</strong>
    </div>
    <div class="dv-topbar-right">
        <a href="{{ route('admin.catalogues.create') }}" class="dv-action-btn" style="--ac:#2563a8;--ab:rgba(37,99,168,.12)">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Catalogue
        </a>
        <a href="{{ route('admin.formations.create') }}" class="dv-action-btn" style="--ac:#1a8fa0;--ab:rgba(26,143,160,.12)">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Formation
        </a>
        <a href="{{ route('admin.articles.create') }}" class="dv-action-btn" style="--ac:#7c3aed;--ab:rgba(124,58,237,.12)">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Article
        </a>
        @if($messagesNonRepondus > 0)
        <a href="{{ route('admin.messages') }}" class="dv-action-btn urgent">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            {{ $messagesNonRepondus }} en attente
        </a>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════
     STATS CARDS — 3 grandes + 3 petites
═══════════════════════════════════════ --}}
<div class="dv-stats-zone">

    {{-- Grandes stats (3 premières) --}}
    <div class="dv-stats-primary">
        <div class="dv-stat-big" style="--g1:#2563a8;--g2:#3b82c4">
            <div class="dv-stat-big-glow"></div>
            <div class="dv-stat-big-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div class="dv-stat-big-num">{{ $totalCatalogues }}</div>
            <div class="dv-stat-big-lbl">Catalogues</div>
            <div class="dv-stat-big-sub">{{ round($totalCatalogues/20*100) }}% de l'objectif</div>
            <div class="dv-stat-big-bar"><div style="width:{{ min(100,round($totalCatalogues/20*100)) }}%"></div></div>
        </div>

        <div class="dv-stat-big" style="--g1:#1a8fa0;--g2:#2d9c4f">
            <div class="dv-stat-big-glow"></div>
            <div class="dv-stat-big-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="dv-stat-big-num">{{ $totalFormations }}</div>
            <div class="dv-stat-big-lbl">Formations</div>
            <div class="dv-stat-big-sub">{{ round($totalFormations/15*100) }}% de l'objectif</div>
            <div class="dv-stat-big-bar"><div style="width:{{ min(100,round($totalFormations/15*100)) }}%"></div></div>
        </div>

        <div class="dv-stat-big {{ $messagesNonRepondus > 0 ? 'is-alert' : '' }}" style="--g1:#ef4444;--g2:#d63384">
            @if($messagesNonRepondus > 0)<div class="dv-alert-pulse"></div>@endif
            <div class="dv-stat-big-glow"></div>
            <div class="dv-stat-big-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <div class="dv-stat-big-num" style="{{ $messagesNonRepondus > 0 ? 'color:#fca5a5' : '' }}">{{ $messagesNonRepondus }}</div>
            <div class="dv-stat-big-lbl">Sans réponse</div>
            <div class="dv-stat-big-sub">{{ $messagesNonRepondus > 0 ? 'À traiter en priorité' : 'Tout est traité ✓' }}</div>
            <div class="dv-stat-big-bar"><div style="width:{{ $messagesNonRepondus > 0 ? '100' : '0' }}%;background:rgba(255,255,255,.4)"></div></div>
        </div>
    </div>

    {{-- Petites stats (3 restantes) --}}
    <div class="dv-stats-secondary">
        <div class="dv-stat-mini" style="--mc:#e8722a;--mb:rgba(232,114,42,.1)">
            <div class="dv-stat-mini-top">
                <div class="dv-stat-mini-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <span class="dv-stat-mini-n">{{ $totalAvis }}</span>
            </div>
            <div class="dv-stat-mini-lbl">Avis clients</div>
            <div class="dv-stat-mini-sub">⭐ {{ $noteMoyenne }}/5 moy.</div>
        </div>

        <div class="dv-stat-mini" style="--mc:#7c3aed;--mb:rgba(124,58,237,.1)">
            <div class="dv-stat-mini-top">
                <div class="dv-stat-mini-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <span class="dv-stat-mini-n">{{ $totalArticles }}</span>
            </div>
            <div class="dv-stat-mini-lbl">Articles</div>
            <div class="dv-stat-mini-sub">{{ round($totalArticles/10*100) }}% objectif</div>
        </div>

        <div class="dv-stat-mini" style="--mc:#2d9c4f;--mb:rgba(45,156,79,.1)">
            <div class="dv-stat-mini-top">
                <div class="dv-stat-mini-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="dv-stat-mini-n">{{ $totalPartenaires }}</span>
            </div>
            <div class="dv-stat-mini-lbl">Partenaires</div>
            <div class="dv-stat-mini-sub">Entreprises clientes</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════
     GRILLE PRINCIPALE
═══════════════════════════════════════ --}}
<div class="dv-grid">

    {{-- ═════ COLONNE GAUCHE ═════ --}}
    <div class="dv-col-main">

        {{-- Activités récentes --}}
        <div class="dv-card">
            <div class="dv-card-head">
                <div class="dv-card-head-left">
                    <div class="dv-card-dot" style="background:linear-gradient(135deg,#2563a8,#d63384)"></div>
                    <div>
                        <div class="dv-card-title">Activités récentes</div>
                        <div class="dv-card-sub">Dernières actions sur le contenu</div>
                    </div>
                </div>
                <div class="dv-card-head-right">
                    <select id="activityDateFilter" class="dv-select">
                        <option value="1">Aujourd'hui</option>
                        <option value="7">7 jours</option>
                        <option value="30" selected>30 jours</option>
                        <option value="all">Tout voir</option>
                    </select>
                    <span class="dv-badge blue" id="activityCount">{{ $allActivities->count() }}</span>
                </div>
            </div>
            <div class="dv-act-list" id="activityList">
                @forelse($allActivities as $i => $act)
                <a href="{{ $act['url'] }}" class="dv-act-item" data-date="{{ $act['date']->toISOString() }}" style="animation-delay:{{ $i * 0.04 }}s">
                    <div class="dv-act-av" style="background:linear-gradient(135deg,{{ $act['color1'] }},{{ $act['color2'] }})">
                        @if($act['icon']==='book')
                            <svg width="12" height="12" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @elseif($act['icon']==='calendar')
                            <svg width="12" height="12" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @elseif($act['icon']==='pen')
                            <svg width="12" height="12" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        @elseif($act['icon']==='star')
                            <svg width="12" height="12" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        @elseif($act['icon']==='handshake')
                            <svg width="12" height="12" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        @else
                            <svg width="12" height="12" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        @endif
                    </div>
                    <div class="dv-act-body">
                        <div class="dv-act-name">{{ Str::limit($act['title'], 42) }}</div>
                        <div class="dv-act-meta">
                            <span class="dv-act-chip" style="background:{{ $act['color1'] }}18;color:{{ $act['color1'] }}">{{ $act['label'] }}</span>
                            <span>{{ $act['action'] }}</span>
                            <span class="dv-act-sep">·</span>
                            <span>{{ $act['date']->diffForHumans() }}</span>
                        </div>
                    </div>
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="dv-act-arrow"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @empty
                <div class="dv-empty">Aucune activité récente</div>
                @endforelse
            </div>
        </div>

        {{-- Objectifs / Progression --}}
        <div class="dv-card">
            <div class="dv-card-head">
                <div class="dv-card-head-left">
                    <div class="dv-card-dot" style="background:linear-gradient(135deg,#2d9c4f,#1a8fa0)"></div>
                    <div>
                        <div class="dv-card-title">Objectifs de contenu</div>
                        <div class="dv-card-sub">Progression par catégorie</div>
                    </div>
                </div>
            </div>
            <div class="dv-prog-grid">
                @php
                    $progs = [
                        ['label'=>'Catalogues',  'val'=>$totalCatalogues, 'max'=>20, 'c1'=>'#2563a8','c2'=>'#3b82c4'],
                        ['label'=>'Formations',  'val'=>$totalFormations, 'max'=>15, 'c1'=>'#1a8fa0','c2'=>'#2d9c4f'],
                        ['label'=>'Avis clients','val'=>$totalAvis,       'max'=>30, 'c1'=>'#e8722a','c2'=>'#d63384'],
                        ['label'=>'Articles',    'val'=>$totalArticles,   'max'=>10, 'c1'=>'#7c3aed','c2'=>'#2563a8'],
                        ['label'=>'Partenaires', 'val'=>$totalPartenaires,'max'=>20, 'c1'=>'#2d9c4f','c2'=>'#1a8fa0'],
                    ];
                @endphp
                @foreach($progs as $p)
                @php $pct = min(100, round($p['val']/$p['max']*100)); @endphp
                <div class="dv-prog-item">
                    <div class="dv-prog-head">
                        <span class="dv-prog-lbl">{{ $p['label'] }}</span>
                        <span class="dv-prog-val">{{ $p['val'] }}<span>/{{ $p['max'] }}</span></span>
                    </div>
                    <div class="dv-prog-track">
                        <div class="dv-prog-fill" style="--w:{{ $pct }}%;background:linear-gradient(90deg,{{ $p['c1'] }},{{ $p['c2'] }})"></div>
                    </div>
                    <div class="dv-prog-pct">{{ $pct }}%</div>
                </div>
                @endforeach
            </div>
        </div>

    </div>{{-- fin col main --}}

    {{-- ═════ COLONNE DROITE ═════ --}}
    <div class="dv-col-side">

        {{-- Carte Admin --}}
        <div class="dv-admin-card">
            <div class="dv-admin-bg-orb"></div>
            <div class="dv-admin-top">
                <div class="dv-admin-av">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                <div class="dv-admin-online">
                    <div class="dv-admin-dot"></div>En ligne
                </div>
            </div>
            <div class="dv-admin-name">{{ auth()->user()->name }}</div>
            <div class="dv-admin-email">{{ auth()->user()->email }}</div>
            <span class="dv-admin-role">Administrateur</span>

            <div class="dv-admin-kpis">
                <div class="dv-admin-kpi">
                    <span>{{ $totalCatalogues }}</span>
                    <label>Catalogues</label>
                </div>
                <div class="dv-admin-kpi-sep"></div>
                <div class="dv-admin-kpi">
                    <span>{{ $totalFormations }}</span>
                    <label>Formations</label>
                </div>
                <div class="dv-admin-kpi-sep"></div>
                <div class="dv-admin-kpi">
                    <span>{{ $totalArticles }}</span>
                    <label>Articles</label>
                </div>
            </div>

            {{-- Score santé --}}
            <div class="dv-health">
                <div class="dv-health-top">
                    <span>Santé du site</span>
                    <span style="color:{{ $healthColor }};font-weight:700">{{ $healthScore }}% — {{ $healthLabel }}</span>
                </div>
                <div class="dv-health-track">
                    <div class="dv-health-fill" style="width:{{ $healthScore }}%;background:{{ $healthColor }}"></div>
                </div>
                <div class="dv-health-labels">
                    <span>0</span><span>Objectif: 100%</span>
                </div>
            </div>
        </div>

        {{-- Messages urgents --}}
        <div class="dv-card dv-urgent-card" id="urgentMessagesCard">
            <div class="dv-card-head">
                <div class="dv-card-head-left">
                    <div class="dv-card-dot" style="background:linear-gradient(135deg,#ef4444,#d63384)"></div>
                    <div>
                        <div class="dv-card-title">Messages urgents</div>
                        <div class="dv-card-sub">En attente de réponse</div>
                    </div>
                </div>
                <a href="{{ route('admin.messages') }}" class="dv-card-link">
                    Voir tous
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="dv-msg-list">
                @forelse($messagesUrgents as $msg)
                @php
                    $colors = [['#2563a8','#3b82c4'],['#d63384','#e8722a'],['#2d9c4f','#1a8fa0'],['#7c3aed','#d63384'],['#e8722a','#2d9c4f']];
                    $cp = $colors[$loop->index % count($colors)];
                    $ini = collect(explode(' ', $msg->nom_complet??''))->take(2)->map(fn($w)=>strtoupper(substr($w,0,1)))->join('');
                @endphp
                <div class="dv-msg-row"
                     data-email="{{ e($msg->email_client) }}"
                     onclick="location.href='{{ route('admin.messages') }}?email={{ urlencode($msg->email_client) }}'">
                    <div class="dv-msg-av" style="background:linear-gradient(135deg,{{ $cp[0] }},{{ $cp[1] }})">
                        {{ $ini ?: strtoupper(substr($msg->email_client,0,1)) }}
                    </div>
                    <div class="dv-msg-info">
                        <div class="dv-msg-name">{{ $msg->nom_complet ?? $msg->email_client }}</div>
                        <div class="dv-msg-prev">{{ Str::limit(strip_tags($msg->message), 38) }}</div>
                    </div>
                    <div class="dv-msg-right">
                        <div class="dv-msg-time">{{ $msg->created_at->diffForHumans() }}</div>
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="dv-msg-arrow"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
                @empty
                <div class="dv-msg-empty">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Tous les messages sont traités</span>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Infos rapides --}}
        <div class="dv-card">
            <div class="dv-card-head">
                <div class="dv-card-head-left">
                    <div class="dv-card-dot" style="background:linear-gradient(135deg,#2563a8,#7c3aed)"></div>
                    <div>
                        <div class="dv-card-title">État du site</div>
                    </div>
                </div>
            </div>
            <div class="dv-kv-list">
                <div class="dv-kv-row">
                    <div class="dv-kv-icon" style="background:rgba(124,58,237,.1);color:#7c3aed">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <span class="dv-kv-lbl">Articles publiés</span>
                    <span class="dv-kv-val">{{ $totalArticles }}</span>
                </div>
                <div class="dv-kv-row">
                    <div class="dv-kv-icon" style="background:rgba(45,156,79,.1);color:#2d9c4f">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="dv-kv-lbl">Partenaires</span>
                    <span class="dv-kv-val">{{ $totalPartenaires }}</span>
                </div>
                <div class="dv-kv-row">
                    <div class="dv-kv-icon" style="background:{{ $messagesNonLus>0?'rgba(239,68,68,.1)':'rgba(45,156,79,.1)' }};color:{{ $messagesNonLus>0?'#ef4444':'#2d9c4f' }}">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <span class="dv-kv-lbl">Messages non lus</span>
                    <span class="dv-kv-val" style="{{ $messagesNonLus>0?'color:#ef4444;font-weight:700':'' }}">{{ $messagesNonLus }}</span>
                </div>
                <div class="dv-kv-row" style="border:none">
                    <div class="dv-kv-icon" style="background:rgba(232,114,42,.1);color:#e8722a">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <span class="dv-kv-lbl">Note moyenne avis</span>
                    <span class="dv-kv-val" style="color:#e8722a">⭐ {{ $noteMoyenne }}/5</span>
                </div>
            </div>
        </div>

    </div>{{-- fin col side --}}

</div>{{-- fin dv-grid --}}

{{-- ═══════════ STYLES ═══════════ --}}
<style>
/* ── Keyframes ── */
@keyframes dv-up    { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
@keyframes dv-pulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.6);opacity:.5} }
@keyframes dv-grow  { from{width:0} to{width:var(--w)} }
@keyframes dv-purg  { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)} 70%{box-shadow:0 0 0 8px rgba(239,68,68,0)} }

/* ── Top bar ── */
.dv-topbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px; flex-wrap: wrap; gap: 10px;
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 14px; padding: 11px 18px;
    box-shadow: var(--shadow-sm, 0 1px 4px rgba(0,0,0,.06));
}

.dv-topbar-left {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--text-secondary,#4a5568);
}

.dv-greet-icon {
    width: 28px; height: 28px; border-radius: 8px;
    background: linear-gradient(135deg,#2563a8,#d63384);
    display: flex; align-items: center; justify-content: center;
    color: white; flex-shrink: 0;
}

.dv-topbar-left strong { font-weight: 700; color: var(--text-primary,#0f1923); }

.dv-topbar-right { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

.dv-action-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 13px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    text-decoration: none;
    background: var(--ab,rgba(37,99,168,.1));
    color: var(--ac,#2563a8);
    border: 1px solid color-mix(in srgb, var(--ac,#2563a8) 20%, transparent);
    transition: all .17s ease;
}

.dv-action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }

.dv-action-btn.urgent {
    background: rgba(239,68,68,.1); color: #ef4444;
    border-color: rgba(239,68,68,.25);
    animation: dv-purg 1.8s infinite;
}

/* ── Stats zone ── */
.dv-stats-zone { display: grid; grid-template-columns: 1fr auto; gap: 14px; margin-bottom: 18px; align-items: start; }

/* Grandes stats */
.dv-stats-primary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }

.dv-stat-big {
    background: linear-gradient(145deg, var(--g1), var(--g2));
    border-radius: 18px; padding: 20px 18px 16px;
    color: white; position: relative; overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
    cursor: default;
    box-shadow: 0 6px 20px rgba(0,0,0,.12);
    animation: dv-up .35s ease both;
}

.dv-stat-big:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,.18); }
.dv-stat-big.is-alert { box-shadow: 0 6px 24px rgba(239,68,68,.3); }

.dv-alert-pulse {
    position: absolute; top: 12px; right: 12px;
    width: 10px; height: 10px; border-radius: 50%;
    background: white;
    animation: dv-pulse 1.3s ease-in-out infinite;
}

.dv-stat-big-glow {
    position: absolute; top: -20px; right: -20px;
    width: 100px; height: 100px; border-radius: 50%;
    background: rgba(255,255,255,.08);
    pointer-events: none;
}

.dv-stat-big-icon {
    width: 44px; height: 44px; border-radius: 14px;
    background: rgba(255,255,255,.18);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 14px;
}

.dv-stat-big-num {
    font-family: 'Outfit',sans-serif; font-size: 36px; font-weight: 900;
    color: white; line-height: 1; letter-spacing: -1px;
}

.dv-stat-big-lbl {
    font-size: 13px; font-weight: 600; color: rgba(255,255,255,.85);
    margin-top: 5px;
}

.dv-stat-big-sub {
    font-size: 11px; color: rgba(255,255,255,.6);
    margin-top: 2px; margin-bottom: 12px;
}

.dv-stat-big-bar {
    height: 4px; background: rgba(255,255,255,.2); border-radius: 4px; overflow: hidden;
}

.dv-stat-big-bar div {
    height: 100%; background: rgba(255,255,255,.65); border-radius: 4px;
    animation: dv-grow .7s ease forwards;
}

/* Petites stats */
.dv-stats-secondary { display: grid; grid-template-columns: 1fr; gap: 10px; width: 200px; }

.dv-stat-mini {
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 14px; padding: 14px 16px;
    transition: all .18s ease;
    position: relative; overflow: hidden;
    animation: dv-up .35s ease both;
}

.dv-stat-mini::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
    background: var(--mc,#2563a8); border-radius: 14px 0 0 14px;
}

.dv-stat-mini:hover { transform: translateX(2px); box-shadow: 0 4px 14px rgba(0,0,0,.07); }

.dv-stat-mini-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }

.dv-stat-mini-icon {
    width: 30px; height: 30px; border-radius: 9px;
    background: var(--mb);  color: var(--mc);
    display: flex; align-items: center; justify-content: center;
}

.dv-stat-mini-n {
    font-family: 'Outfit',sans-serif; font-size: 26px; font-weight: 800;
    color: var(--text-primary,#0f1923); line-height: 1;
}

.dv-stat-mini-lbl { font-size: 12px; font-weight: 600; color: var(--text-secondary,#4a5568); }
.dv-stat-mini-sub { font-size: 11px; color: var(--text-muted,#94a3b8); margin-top: 2px; }

/* ── Main grid ── */
.dv-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 16px; align-items: start;
}

.dv-col-main { display: flex; flex-direction: column; gap: 14px; }
.dv-col-side  { display: flex; flex-direction: column; gap: 14px; position: sticky; top: 78px; }

/* ── Generic card ── */
.dv-card {
    background: var(--bg-surface,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 18px; overflow: hidden;
    box-shadow: var(--shadow-sm, 0 1px 4px rgba(0,0,0,.05));
    animation: dv-up .35s ease both;
}

.dv-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px 12px;
    border-bottom: 1px solid var(--border-subtle,#eef2f7);
    background: var(--bg-surface-2,#f8fafc);
    gap: 12px;
}

.dv-card-head-left { display: flex; align-items: center; gap: 10px; }

.dv-card-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

.dv-card-title { font-family: 'Outfit',sans-serif; font-size: 14px; font-weight: 700; color: var(--text-primary,#0f1923); }
.dv-card-sub   { font-size: 11.5px; color: var(--text-muted,#94a3b8); margin-top: 1px; }

.dv-card-head-right { display: flex; align-items: center; gap: 8px; }

.dv-badge {
    font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 20px;
}
.dv-badge.blue { background: rgba(37,99,168,.1); color: #2563a8; }

.dv-card-link {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11.5px; font-weight: 600;
    color: #2563a8; background: rgba(37,99,168,.08);
    padding: 4px 11px; border-radius: 20px;
    text-decoration: none; transition: all .15s;
    white-space: nowrap;
}
.dv-card-link:hover { background: rgba(37,99,168,.16); }

.dv-select {
    font-size: 11.5px; padding: 5px 10px;
    border-radius: 10px; cursor: pointer;
    background: var(--bg-surface,#fff);
    border: 1.5px solid var(--border-color,#e2e8f0);
    color: var(--text-secondary,#4a5568);
    font-family: 'DM Sans',sans-serif;
    transition: border-color .15s;
    box-shadow: none !important;
}
.dv-select:focus { outline: none; border-color: #2563a8; }

/* ── Activity list ── */
.dv-act-list {
    max-height: 300px; overflow-y: auto;
    padding: 8px 12px; display: flex; flex-direction: column; gap: 2px;
}
.dv-act-list::-webkit-scrollbar { width: 3px; }
.dv-act-list::-webkit-scrollbar-thumb { background: var(--border-color,#e2e8f0); border-radius: 3px; }

.dv-act-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 8px; border-radius: 12px;
    text-decoration: none; color: var(--text-primary,#0f1923);
    border: 1px solid transparent;
    transition: all .15s ease;
    animation: dv-up .3s ease both;
}

.dv-act-item:hover {
    background: var(--bg-surface-2,#f8fafc);
    border-color: var(--border-subtle,#eef2f7);
    transform: translateX(3px);
}

.dv-act-av {
    width: 34px; height: 34px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,.12);
}

.dv-act-body { flex: 1; min-width: 0; }
.dv-act-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dv-act-meta { display: flex; align-items: center; gap: 5px; margin-top: 3px; font-size: 11.5px; color: var(--text-muted,#94a3b8); }
.dv-act-chip { font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 10px; }
.dv-act-sep  { opacity: .4; }
.dv-act-arrow { color: var(--text-muted,#94a3b8); opacity: 0; flex-shrink: 0; transition: opacity .15s; }
.dv-act-item:hover .dv-act-arrow { opacity: 1; }

/* ── Progress grid ── */
.dv-prog-grid { padding: 16px 18px; display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

.dv-prog-item {}

.dv-prog-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.dv-prog-lbl  { font-size: 12.5px; font-weight: 500; color: var(--text-secondary,#4a5568); }
.dv-prog-val  { font-family: 'Outfit',sans-serif; font-size: 14px; font-weight: 700; color: var(--text-primary,#0f1923); }
.dv-prog-val span { font-size: 11px; color: var(--text-muted,#94a3b8); font-weight: 400; }

.dv-prog-track { height: 6px; background: var(--border-color,#e2e8f0); border-radius: 6px; overflow: hidden; }
.dv-prog-fill  { height: 100%; border-radius: 6px; width: 0; animation: dv-grow .7s ease forwards; }

.dv-prog-pct { font-size: 11px; color: var(--text-muted,#94a3b8); margin-top: 4px; }

/* ── Admin card ── */
.dv-admin-card {
    background: linear-gradient(145deg, #0c1520, #162236, #1a1a3a);
    border-radius: 18px; padding: 20px;
    position: relative; overflow: hidden;
    text-align: center;
    box-shadow: 0 8px 28px rgba(0,0,0,.2);
}

.dv-admin-bg-orb {
    position: absolute; top: -30px; left: 50%; transform: translateX(-50%);
    width: 160px; height: 160px; border-radius: 50%;
    background: radial-gradient(circle, rgba(37,99,168,.25), transparent 70%);
    pointer-events: none;
}

.dv-admin-top { display: flex; align-items: center; justify-content: center; margin-bottom: 12px; position: relative; }

.dv-admin-av {
    width: 60px; height: 60px; border-radius: 50%;
    background: linear-gradient(135deg,#2563a8,#d63384,#e8722a);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Outfit',sans-serif; font-size: 20px; font-weight: 800;
    color: white;
    border: 3px solid rgba(255,255,255,.12);
    box-shadow: 0 6px 20px rgba(37,99,168,.4);
}

.dv-admin-online {
    position: absolute; right: 0;
    display: flex; align-items: center; gap: 5px;
    font-size: 10.5px; color: #4ade80; font-weight: 600;
}

.dv-admin-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #4ade80;
    box-shadow: 0 0 6px rgba(74,222,128,.7);
    animation: dv-pulse 2s infinite;
}

.dv-admin-name  { font-family: 'Outfit',sans-serif; font-size: 15px; font-weight: 700; color: white; }
.dv-admin-email { font-size: 11px; color: rgba(255,255,255,.45); margin-top: 3px; }

.dv-admin-role {
    display: inline-block; font-size: 10px; font-weight: 700;
    background: rgba(37,99,168,.3); color: #93c5fd;
    padding: 3px 12px; border-radius: 20px; margin-top: 8px;
    text-transform: uppercase; letter-spacing: .06em;
}

.dv-admin-kpis {
    display: flex; align-items: center; justify-content: space-around;
    margin-top: 16px; padding-top: 14px;
    border-top: 1px solid rgba(255,255,255,.08);
}

.dv-admin-kpi span  { display: block; font-family: 'Outfit',sans-serif; font-size: 22px; font-weight: 800; color: white; }
.dv-admin-kpi label { font-size: 10px; color: rgba(255,255,255,.45); margin-top: 2px; display: block; }
.dv-admin-kpi-sep   { width: 1px; height: 30px; background: rgba(255,255,255,.1); }

.dv-health {
    margin-top: 14px; padding-top: 12px;
    border-top: 1px solid rgba(255,255,255,.08);
}

.dv-health-top {
    display: flex; justify-content: space-between;
    font-size: 11px; color: rgba(255,255,255,.5); margin-bottom: 7px;
}

.dv-health-track { height: 6px; background: rgba(255,255,255,.1); border-radius: 6px; overflow: hidden; }
.dv-health-fill  { height: 100%; border-radius: 6px; transition: width .8s ease; }

.dv-health-labels {
    display: flex; justify-content: space-between;
    font-size: 9.5px; color: rgba(255,255,255,.3); margin-top: 4px;
}

/* ── Messages urgents ── */
.dv-msg-list { max-height: 260px; overflow-y: auto; }
.dv-msg-list::-webkit-scrollbar { width: 3px; }
.dv-msg-list::-webkit-scrollbar-thumb { background: var(--border-color,#e2e8f0); border-radius: 3px; }

.dv-msg-row {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 16px; cursor: pointer;
    border-bottom: 1px solid var(--border-subtle,#eef2f7);
    transition: all .14s ease;
}
.dv-msg-row:last-child { border-bottom: none; }
.dv-msg-row:hover { background: var(--bg-surface-2,#f8fafc); transform: translateX(2px); }

.dv-msg-av {
    width: 36px; height: 36px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 12px; font-weight: 700;
    flex-shrink: 0; font-family: 'Outfit',sans-serif;
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
}

.dv-msg-info { flex: 1; min-width: 0; }
.dv-msg-name { font-size: 12.5px; font-weight: 600; color: var(--text-primary,#0f1923); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dv-msg-prev { font-size: 11px; color: var(--text-muted,#94a3b8); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }

.dv-msg-right { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
.dv-msg-time  { font-size: 10px; color: #e8722a; font-weight: 600; white-space: nowrap; }
.dv-msg-arrow { color: var(--text-muted,#94a3b8); opacity: 0; transition: opacity .12s; }
.dv-msg-row:hover .dv-msg-arrow { opacity: 1; }

.dv-msg-empty {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    padding: 28px 20px; color: #2d9c4f; font-size: 12px; font-weight: 600;
}
.dv-msg-empty svg { opacity: .7; }

/* ── KV rows ── */
.dv-kv-list { padding: 8px 16px 12px; }

.dv-kv-row {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-subtle,#eef2f7);
}
.dv-kv-row:last-child { border-bottom: none; }

.dv-kv-icon {
    width: 30px; height: 30px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.dv-kv-lbl { font-size: 12.5px; color: var(--text-secondary,#4a5568); flex: 1; font-weight: 500; }
.dv-kv-val { font-size: 13px; font-weight: 700; color: var(--text-primary,#0f1923); }

/* ── Empty ── */
.dv-empty { text-align: center; padding: 30px; font-size: 12.5px; color: var(--text-muted,#94a3b8); }

/* ── Responsive ── */
@media (max-width: 1200px) {
    .dv-stats-zone { grid-template-columns: 1fr; }
    .dv-stats-secondary { width: 100%; grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 1000px) {
    .dv-grid { grid-template-columns: 1fr; }
    .dv-col-side { position: static; }
    .dv-stats-primary { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 680px) {
    .dv-stats-primary { grid-template-columns: 1fr; }
    .dv-stats-secondary { grid-template-columns: 1fr 1fr; }
    .dv-topbar { flex-direction: column; align-items: flex-start; }
    .dv-prog-grid { grid-template-columns: 1fr; }
}
</style>

{{-- ═══════════ JS ═══════════ --}}
<script>
(function () {
    'use strict';

    /* ── Filtre activités ── */
    const filter  = document.getElementById('activityDateFilter');
    const list    = document.getElementById('activityList');
    const counter = document.getElementById('activityCount');

    if (filter && list) {
        const items = Array.from(list.querySelectorAll('.dv-act-item'));

        function applyFilter(val) {
            const now = new Date(); let visible = 0;
            items.forEach(el => {
                const d = el.dataset.date ? new Date(el.dataset.date) : null;
                let show = false;
                if (!d || val === 'all') { show = true; }
                else if (val === '1') {
                    const t = new Date(); t.setHours(0,0,0,0);
                    const e = new Date(d); e.setHours(0,0,0,0);
                    show = e.getTime() === t.getTime();
                } else {
                    show = (now - d) / 86400000 <= parseInt(val);
                }
                el.style.display = show ? 'flex' : 'none';
                if (show) visible++;
            });
            if (counter) counter.textContent = visible;
        }

        filter.addEventListener('change', e => applyFilter(e.target.value));
        applyFilter(filter.value);
    }

    /* ── Refresh messages urgents ── */
    setInterval(function () {
        fetch(window.location.href, { cache: 'no-store' })
            .then(r => r.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const nc  = doc.getElementById('urgentMessagesCard');
                const oc  = document.getElementById('urgentMessagesCard');
                if (nc && oc && nc.innerHTML !== oc.innerHTML) {
                    oc.innerHTML = nc.innerHTML;
                    oc.querySelectorAll('.dv-msg-row[data-email]').forEach(el => {
                        const em = el.dataset.email;
                        if (em) el.onclick = () => location.href = '{{ route("admin.messages") }}?email=' + encodeURIComponent(em);
                    });
                }
            }).catch(() => {});
    }, 30000);

})();
</script>

@endsection