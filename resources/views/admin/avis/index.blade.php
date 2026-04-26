@extends('layouts.admin')

@section('page-title', 'Avis clients')
@section('page-subtitle', 'Gérez les témoignages de vos clients')

@section('content')

<!-- PAGE HEADER -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
            ⭐ Avis clients
        </h1>
        <p style="color: var(--text-muted); font-size: 13px;">
            Total: <strong>{{ $avis->count() }} avis</strong> • Témoignages de nos clients
        </p>
    </div>
    <a href="{{ route('admin.avis.create') }}" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nouvel avis
    </a>
</div>

@if($avis->count())
    <!-- STATS RAPIDES -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Total avis</p>
                <h3>{{ $avis->count() }}</h3>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon green">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Publiés</p>
                <h3>{{ $avis->where('statut', 'publie')->count() }}</h3>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon orange">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>En attente</p>
                <h3>{{ $avis->where('statut', 'en_attente')->count() }}</h3>
            </div>
        </div>

        <div class="stat-card pink">
            <div class="stat-icon pink">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Note moyenne</p>
                <h3>{{ number_format($avis->avg('note'), 1) }}/5</h3>
            </div>
        </div>
    </div>

    <!-- TABLE DES AVIS -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Liste des avis</h3>
                <p class="card-subtitle">Témoignages et retours clients</p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Contact</th>
                        <th>Avis</th>
                        <th>Note</th>
                        <th>Mise en avant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($avis as $a)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($a->logo_entreprise)
                                    <img src="{{ asset('storage/'.$a->logo_entreprise) }}" alt="{{ $a->entreprise_nom }}" 
                                         style="width: 32px; height: 32px; object-fit: cover; border-radius: 8px; background: white; border: 1px solid var(--border-color);">
                                @else
                                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600;">
                                        {{ substr($a->entreprise_nom, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <strong style="color: var(--text-primary); display: block;">{{ Str::limit($a->entreprise_nom, 30) }}</strong>
                                    @if($a->contact_nom)
                                        <span style="color: var(--text-muted); font-size: 11px;">{{ $a->contact_nom }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($a->contact_fonction)
                                <span style="color: var(--text-secondary); font-size: 12px; display: flex; align-items: center; gap: 4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ Str::limit($a->contact_fonction, 25) }}
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-size: 12px;">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="max-width: 250px;">
                                <span style="color: var(--text-secondary); font-size: 13px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    "{{ Str::limit($a->contenu, 80) }}"
                                </span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <div style="display: flex; gap: 2px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $a->note)
                                            <svg width="14" height="14" fill="#f59e0b" viewBox="0 0 20 20" style="color: #f59e0b;">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @else
                                            <svg width="14" height="14" fill="none" stroke="#cbd5e1" viewBox="0 0 20 20" style="color: #cbd5e1;">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span style="color: var(--text-muted); font-size: 11px;">({{ $a->note }}/5)</span>
                            </div>
                        </td>
                        <td>
                            @if($a->mis_en_avant)
                                <span style="display: inline-flex; align-items: center; gap: 4px; background: rgba(245,158,11,0.12); color: #f59e0b; padding: 3px 8px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                    En avant
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-size: 12px;">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = '';
                                $statusText = '';
                                if($a->statut == 'publie') {
                                    $statusClass = 'active';
                                    $statusText = '✓ Publié';
                                } elseif($a->statut == 'en_attente') {
                                    $statusClass = 'pending';
                                    $statusText = '⏳ En attente';
                                } else {
                                    $statusClass = 'inactive';
                                    $statusText = '✗ Rejeté';
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <!-- VOIR -->
                                @if($a->statut == 'publie')
                                <a href="{{ route('admin.avis.show', $a) }}" class="btn-icon" title="Voir">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @endif

                                <!-- MODIFIER -->
                                <a href="{{ route('admin.avis.edit', $a) }}" class="btn-icon" title="Modifier">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- ACCEPTER (si en attente) -->
                                @if($a->statut == 'en_attente')
                                <form action="{{ route('admin.avis.accept', $a) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-icon" style="color: var(--brand-green);" title="Accepter" onclick="return confirm('Accepter et publier cet avis ?')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </form>
                                @endif

                                <!-- SUPPRIMER -->
                                <form method="POST" action="{{ route('admin.avis.destroy', $a) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    @if(method_exists($avis, 'links'))
        <div style="margin-top: 24px;">
            {{ $avis->links() }}
        </div>
    @endif

@else
    <!-- EMPTY STATE -->
    <div style="background: var(--bg-surface); border-radius: 16px; border: 1px dashed var(--border-color); padding: 60px 20px; text-align: center;">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; color: var(--text-muted); opacity: 0.4;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
        </svg>
        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
            Aucun avis client pour le moment
        </h3>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">
            Créez votre premier témoignage pour la section "Ce qu'ils disent de nous"
        </p>
        <a href="{{ route('admin.avis.create') }}" class="btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajouter un avis
        </a>
    </div>
@endif

@endsection