@extends('layouts.admin')

@section('page-title', 'Partenaires')
@section('page-subtitle', 'Gérez les entreprises qui nous font confiance')

@section('content')

<!-- PAGE HEADER -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
            🤝 Partenaires
        </h1>
        <p style="color: var(--text-muted); font-size: 13px;">
            Total: <strong>{{ $partenaires->count() }} partenaires</strong> • Ils nous font confiance
        </p>
    </div>
    <a href="{{ route('admin.partenaires.create') }}" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nouveau partenaire
    </a>
</div>

@if($partenaires->count())
    <!-- STATS RAPIDES -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Total partenaires</p>
                <h3>{{ $partenaires->count() }}</h3>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon green">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Partenaires actifs</p>
                <h3>{{ $partenaires->where('actif', true)->count() }}</h3>
            </div>
        </div>

        <div class="stat-card pink">
            <div class="stat-icon pink">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Avec site web</p>
                <h3>{{ $partenaires->whereNotNull('site_web')->where('site_web', '!=', '')->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- TABLE DES PARTENAIRES -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Liste des partenaires</h3>
                <p class="card-subtitle">Entreprises partenaires et clients</p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Entreprise</th>
                        <th>Site web</th>
                        <th>Description</th>
                        <th>Ordre</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partenaires as $partenaire)
                    <tr>
                        <td>
                            @if($partenaire->logo)
                                <img src="{{ asset('storage/'.$partenaire->logo) }}" alt="{{ $partenaire->nom_entreprise }}" 
                                     style="width: 40px; height: 40px; object-fit: contain; background: white; border-radius: 8px; padding: 4px; border: 1px solid var(--border-color);">
                            @else
                                <div style="width: 40px; height: 40px; background: var(--bg-surface-2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong style="color: var(--text-primary);">{{ $partenaire->nom_entreprise }}</strong>
                        </td>
                        <td>
                            @if($partenaire->site_web)
                                <a href="{{ $partenaire->site_web }}" target="_blank" rel="noopener noreferrer" 
                                   style="color: var(--brand-blue); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Visiter
                                </a>
                            @else
                                <span style="color: var(--text-muted); font-size: 12px;">—</span>
                            @endif
                        </td>
                        <td>
                            <span style="color: var(--text-secondary); font-size: 13px;">
                                {{ Str::limit($partenaire->description, 50) ?: '—' }}
                            </span>
                        </td>
                        <td>
                            <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; background: var(--bg-surface-2); padding: 4px 8px; border-radius: 20px; font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                                #{{ $partenaire->ordre_affichage ?? 0 }}
                            </span>
                        </td>
                        <td>
                            @if($partenaire->actif)
                                <span class="status-badge active">✓ Actif</span>
                            @else
                                <span class="status-badge inactive">✗ Inactif</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <!-- MODIFIER -->
                                <a href="{{ route('admin.partenaires.edit', $partenaire) }}" class="btn-icon" title="Modifier">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- SUPPRIMER -->
                                <form method="POST" action="{{ route('admin.partenaires.destroy', $partenaire) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce partenaire ?')">
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
            Aucun partenaire pour le moment
        </h3>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">
            Ajoutez votre premier partenaire pour la section "Ils nous font confiance"
        </p>
        <a href="{{ route('admin.partenaires.create') }}" class="btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajouter un partenaire
        </a>
    </div>
@endif

@endsection