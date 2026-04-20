@extends('layouts.admin')

@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Aperçu des activités et statistiques')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div class="stat-info">
            <p>Catalogues</p>
            <h3>{{ $totalCatalogues ?? 0 }}</h3>
            <span class="stat-badge up">+12%</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
        <div class="stat-info">
            <p>Formations</p>
            <h3>{{ $totalFormations ?? 0 }}</h3>
            <span class="stat-badge up">+5%</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon coral">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
        </div>
        <div class="stat-info">
            <p>Expertises</p>
            <h3>{{ $totalExpertises ?? 0 }}</h3>
            <span class="stat-badge up">+8%</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <div class="stat-info">
            <p>Messages non lus</p>
            <h3>{{ \App\Models\Message::where('lu', false)->count() }}</h3>
            <span class="stat-badge pending">nouveaux</span>
        </div>
    </div>
</div>

<div class="content-grid">
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Activités récentes</h3>
                <p class="card-subtitle">Derniers messages et interactions</p>
            </div>
            <a href="{{ route('admin.messages') }}" class="card-action">Voir tous</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse(\App\Models\Message::latest()->take(5)->get() as $msg)
                <tr>
                    <td>
                        <div class="table-name-group">
                            <div class="avatar" style="width:32px;height:32px;font-size:12px">
                                {{ strtoupper(substr($msg->nom_complet, 0, 2)) }}
                            </div>
                            <span>{{ $msg->nom_complet }}</span>
                        </div>
                    </td>
                    <td>{{ Str::limit($msg->message, 40) }}</td>
                    <td>{{ $msg->created_at->diffForHumans() }}</td>
                    <td>
                        @if($msg->repondu)
                            <span class="status-badge active">Répondu</span>
                        @else
                            <span class="status-badge pending">En attente</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-gray-400 py-4">Aucun message récent</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="right-panel">
        <div class="profile-card">
            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <h3>{{ auth()->user()->name }}</h3>
            <p>{{ auth()->user()->email }}</p>
            <div class="profile-stats">
                <div class="stat">
                    <span>5</span>
                    <label>Projets</label>
                </div>
                <div class="stat">
                    <span>{{ \App\Models\Message::where('repondu', false)->count() }}</span>
                    <label>À traiter</label>
                </div>
                <div class="stat">
                    <span>{{ \App\Models\Message::count() }}</span>
                    <label>Messages</label>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Derniers messages</h3>
                    <p class="card-subtitle">À répondre rapidement</p>
                </div>
                <a href="{{ route('admin.messages') }}" class="card-action">Répondre</a>
            </div>
            @forelse(\App\Models\Message::where('repondu', false)->latest()->take(4)->get() as $msg)
            <div class="mini-list-item">
                <div class="mini-icon" style="background:#F4F1FF;color:#4318FF">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="info">
                    <p>{{ $msg->nom_complet }}</p>
                    <span>{{ Str::limit($msg->message, 35) }}</span>
                </div>
                <div class="time">{{ $msg->created_at->diffForHumans() }}</div>
            </div>
            @empty
            <p class="text-gray-400 text-center py-4">Aucun message en attente</p>
            @endforelse
        </div>
    </div>
</div>

<div class="card mt-6">
    <div class="card-header">
        <div>
            <h3 class="card-title">Progression globale</h3>
            <p class="card-subtitle">Contenu publié vs objectifs</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <div class="flex justify-between text-sm mb-1">
                <span>Catalogues</span>
                <span>{{ min(100, round(($totalCatalogues ?? 0) / 20 * 100)) }}%</span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: {{ min(100, round(($totalCatalogues ?? 0) / 20 * 100)) }}%"></div></div>
            <p class="text-xs text-gray-400 mt-1">Objectif : 20 catalogues</p>
        </div>
        <div>
            <div class="flex justify-between text-sm mb-1">
                <span>Formations</span>
                <span>{{ min(100, round(($totalFormations ?? 0) / 15 * 100)) }}%</span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: {{ min(100, round(($totalFormations ?? 0) / 15 * 100)) }}%"></div></div>
            <p class="text-xs text-gray-400 mt-1">Objectif : 15 formations</p>
        </div>
        <div>
            <div class="flex justify-between text-sm mb-1">
                <span>Expertises</span>
                <span>{{ min(100, round(($totalExpertises ?? 0) / 10 * 100)) }}%</span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: {{ min(100, round(($totalExpertises ?? 0) / 10 * 100)) }}%"></div></div>
            <p class="text-xs text-gray-400 mt-1">Objectif : 10 expertises</p>
        </div>
    </div>
</div>
@endsection
