@extends('layouts.admin')

@section('page-title', 'Catalogues')
@section('page-subtitle', 'Gérez vos catalogues de formations')

@section('content')

<!-- PAGE HEADER -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
            📚 Catalogues
        </h1>
        <p style="color: var(--text-muted); font-size: 13px;">
            Total: <strong>{{ $catalogues->count() }} catalogues</strong> • Gestion complète de vos syllabus
        </p>
    </div>
    <a href="{{ route('admin.catalogues.create') }}" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter un catalogue
    </a>
</div>

@if($catalogues->count())
    <!-- STATS RAPIDES -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Total Catalogues</p>
                <h3>{{ $catalogues->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- TABLE DES CATALOGUES -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Liste des catalogues</h3>
                <p class="card-subtitle">Tous vos catalogues de formations</p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Créé le</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($catalogues as $catalogue)
                    <tr>
                        <td>
                            <strong style="color: var(--text-primary);">{{ $catalogue->titre }}</strong>
                        </td>
                        <td>
                            {{ Str::limit($catalogue->description, 50) }}
                        </td>
                        <td>
                            <span style="color: var(--text-muted); font-size: 12px;">
                                {{ $catalogue->created_at->format('d/m/Y H:i') }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge active">✓ Publié</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <!-- VOIR -->
                                <a href="{{ route('admin.catalogues.show', $catalogue) }}" class="btn-icon" title="Voir détails">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                <!-- MODIFIER -->
                                <a href="{{ route('admin.catalogues.edit', $catalogue) }}" class="btn-icon" title="Modifier">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- SUPPRIMER -->
                                <form method="POST" action="{{ route('admin.catalogues.destroy', $catalogue) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="Supprimer" onclick="return confirm('Êtes-vous vraiment sûr de vouloir supprimer ce catalogue?')">
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
            Aucun catalogue pour le moment
        </h3>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">
            Créez votre premier catalogue de formations pour commencer
        </p>
        <a href="{{ route('admin.catalogues.create') }}" class="btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Créer votre premier catalogue
        </a>
    </div>
@endif

@endsection