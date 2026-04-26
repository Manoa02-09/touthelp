@extends('layouts.admin')

@section('page-title', 'Articles')
@section('page-subtitle', 'Gérez votre blog et actualités')

@section('content')

<!-- PAGE HEADER -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
            📝 Articles
        </h1>
        <p style="color: var(--text-muted); font-size: 13px;">
            Total: <strong>{{ $articles->count() }} articles</strong> • Blog et actualités
        </p>
    </div>
    <a href="{{ route('admin.articles.create') }}" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nouvel article
    </a>
</div>

@if($articles->count())
    <!-- STATS RAPIDES -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Total articles</p>
                <h3>{{ $articles->count() }}</h3>
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
                <h3>{{ $articles->where('publie', true)->count() }}</h3>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon orange">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Brouillons</p>
                <h3>{{ $articles->where('publie', false)->count() }}</h3>
            </div>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon purple">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M9 3v18m6-18v18" />
                </svg>
            </div>
            <div class="stat-info">
                <p>Ce mois-ci</p>
                <h3>{{ $articles->where('date_publication', '>=', now()->startOfMonth())->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- TABLE DES ARTICLES -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Liste des articles</h3>
                <p class="card-subtitle">Tous vos articles de blog</p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Date publication</th>
                        <th>Extrait</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                    <tr>
                        <td style="width: 50px;">
                            @if($article->image_une)
                                <img src="{{ asset('storage/'.$article->image_une) }}" alt="{{ $article->titre }}" 
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
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
                            <div>
                                <strong style="color: var(--text-primary); display: block;">{{ Str::limit($article->titre, 50) }}</strong>
                                <span style="color: var(--text-muted); font-size: 11px;">
                                    @if($article->type == 'blog')
                                        📝 Blog
                                    @elseif($article->type == 'reussite')
                                        🏆 Réussite
                                    @else
                                        🤝 Partenariat
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td>
                            <span style="color: var(--text-secondary); font-size: 13px; display: flex; align-items: center; gap: 4px;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $article->date_publication->format('d/m/Y') }}
                            </span>
                        </td>
                        <td style="max-width: 250px;">
                            <span style="color: var(--text-secondary); font-size: 12px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit($article->extrait ?? $article->contenu, 80) }}
                            </span>
                        </td>
                        <td>
                            @if($article->publie)
                                <span class="status-badge active">✓ Publié</span>
                            @else
                                <span class="status-badge inactive">✗ Brouillon</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <!-- VOIR SUR LE SITE -->
                                @if($article->publie)
                                <a href="{{ route('blog.show', $article->slug) }}" target="_blank" class="btn-icon" title="Voir sur le site">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @endif

                                <!-- MODIFIER -->
                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn-icon" title="Modifier">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- SUPPRIMER -->
                                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
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
    @if(method_exists($articles, 'links'))
        <div style="margin-top: 24px;">
            {{ $articles->links() }}
        </div>
    @endif

@else
    <!-- EMPTY STATE -->
    <div style="background: var(--bg-surface); border-radius: 16px; border: 1px dashed var(--border-color); padding: 60px 20px; text-align: center;">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; color: var(--text-muted); opacity: 0.4;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
        </svg>
        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
            Aucun article pour le moment
        </h3>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">
            Créez votre premier article pour la section blog
        </p>
        <a href="{{ route('admin.articles.create') }}" class="btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Écrire un article
        </a>
    </div>
@endif

@endsection