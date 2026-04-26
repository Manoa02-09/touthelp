@extends('layouts.admin')

@section('page-title', 'Formations')
@section('page-subtitle', 'Gérez votre calendrier de formations')

@section('content')

<!-- PAGE HEADER -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
            📅 Formations
        </h1>
        <p style="color: var(--text-muted); font-size: 13px;">
            Total: <strong>{{ $formations->count() }} formations</strong> • Calendrier des sessions à venir
        </p>
    </div>
    <a href="{{ route('admin.formations.create') }}" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nouvelle formation
    </a>
</div>

@if($formations->count())
    <!-- STATS RAPIDES -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Total formations</p>
                <h3>{{ $formations->count() }}</h3>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon green">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Formations actives</p>
                <h3>{{ $formations->where('actif', true)->count() }}</h3>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon orange">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>À venir</p>
                <h3>{{ $formations->where('date_debut', '>=', now())->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- TABLE DES FORMATIONS -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Liste des formations</h3>
                <p class="card-subtitle">Toutes vos sessions de formation</p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Lieu</th>
                        <th>Places</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($formations as $formation)
                    @php
                        $isUpcoming = $formation->date_debut && $formation->date_debut->isFuture();
                        $isOngoing = $formation->date_debut && $formation->date_debut->isPast() && (!$formation->date_fin || $formation->date_fin->isFuture());
                        $isPast = $formation->date_fin && $formation->date_fin->isPast();
                    @endphp
                    <tr>
                        <td>
                            <strong style="color: var(--text-primary);">{{ $formation->titre }}</strong>
                        </td>
                        <td>
                            <span style="color: var(--text-primary); font-size: 13px;">
                                {{ $formation->date_debut ? $formation->date_debut->format('d/m/Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            <span style="color: var(--text-muted); font-size: 12px;">
                                {{ $formation->date_fin ? $formation->date_fin->format('d/m/Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            <span style="display: flex; align-items: center; gap: 4px;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-muted);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $formation->lieu }}
                            </span>
                        </td>
                        <td>
                            <span style="color: var(--text-muted); font-size: 12px;">
                                {{ $formation->places_max ? $formation->places_max . ' places' : 'Illimité' }}
                            </span>
                        </td>
                        <td>
                            @if($formation->actif)
                                <span class="status-badge active">✓ Active</span>
                            @else
                                <span class="status-badge inactive">✗ Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <!-- VOIR DÉTAILS -->
                                <a href="{{ route('admin.formations.show', $formation) }}" class="btn-icon" title="Voir détails">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                <!-- MODIFIER -->
                                <a href="{{ route('admin.formations.edit', $formation) }}" class="btn-icon" title="Modifier">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- SUPPRIMER -->
                                <form method="POST" action="{{ route('admin.formations.destroy', $formation) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')">
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

@else
    <!-- EMPTY STATE -->
    <div style="background: var(--bg-surface); border-radius: 16px; border: 1px dashed var(--border-color); padding: 60px 20px; text-align: center;">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; color: var(--text-muted); opacity: 0.4;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
            Aucune formation pour le moment
        </h3>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">
            Créez votre première formation pour alimenter le calendrier
        </p>
        <a href="{{ route('admin.formations.create') }}" class="btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Créer votre première formation
        </a>
    </div>
@endif

@endsection