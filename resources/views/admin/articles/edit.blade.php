@extends('layouts.admin')

@section('page-title', 'Modifier l\'article')
@section('page-subtitle', 'Mettre à jour le contenu de l\'article')

@section('content')

<style>
/* ============================================================
   ARTICLES EDIT — Design system harmonisé
============================================================ */
.form-page {
    max-width: 1200px;
}

.form-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    font-size: 13px;
    color: var(--text-muted, #94a3b8);
}

.form-breadcrumb a {
    color: var(--brand-blue, #2563a8);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.form-breadcrumb a:hover { color: var(--brand-blue-light, #3b82c4); }
.form-breadcrumb svg { width: 14px; height: 14px; opacity: 0.5; }

.form-shell {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
}

.form-section {
    background: var(--bg-surface, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 20px;
    transition: box-shadow 0.2s ease;
    animation: fadeUp 0.35s ease both;
}

.form-section:hover {
    box-shadow: 0 4px 20px rgba(37,99,168,0.07);
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.form-section-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 22px;
    border-bottom: 1px solid var(--border-subtle, #eef2f7);
    background: var(--bg-surface-2, #f8fafc);
}

.form-section-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.icon-blue   { background: rgba(37,99,168,0.12); color: var(--brand-blue, #2563a8); }
.icon-purple { background: rgba(139,92,246,0.12); color: #8b5cf6; }
.icon-teal   { background: rgba(26,143,160,0.12);  color: var(--brand-teal, #1a8fa0); }
.icon-green  { background: rgba(45,156,79,0.12);  color: var(--brand-green, #2d9c4f); }

.form-section-head-text h3 {
    font-family: 'Outfit', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary, #0f1923);
    line-height: 1.2;
}

.form-section-head-text p {
    font-size: 11.5px;
    color: var(--text-muted, #94a3b8);
    margin-top: 1px;
}

.form-section-body {
    padding: 22px;
}

.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.field-row.single { grid-template-columns: 1fr; }

.field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.field-label {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-secondary, #4a5568);
    display: flex;
    align-items: center;
    gap: 6px;
    letter-spacing: 0.01em;
}

.field-label .required-dot {
    width: 5px;
    height: 5px;
    background: var(--brand-pink, #d63384);
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}

.field-hint {
    font-size: 11px;
    color: var(--text-muted, #94a3b8);
    margin-top: -2px;
}

.field-input,
.field-textarea,
.field-select {
    width: 100%;
    background: var(--bg-surface-2, #f8fafc);
    border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 13.5px;
    color: var(--text-primary, #0f1923);
    font-family: 'DM Sans', sans-serif;
    outline: none;
    transition: all 0.2s ease;
}

.field-input:focus,
.field-textarea:focus,
.field-select:focus {
    border-color: var(--brand-blue, #2563a8);
    background: var(--bg-surface, #fff);
    box-shadow: 0 0 0 3px rgba(37,99,168,0.1);
}

.field-input::placeholder,
.field-textarea::placeholder { color: var(--text-muted, #94a3b8); }

.field-textarea {
    resize: vertical;
    line-height: 1.6;
}

.field-textarea.content-editor {
    min-height: 320px;
    font-family: 'DM Sans', monospace;
    font-size: 13px;
}

.field-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.field-input-wrap .input-icon {
    position: absolute;
    left: 12px;
    color: var(--text-muted, #94a3b8);
    pointer-events: none;
    display: flex;
}

.field-input-wrap .field-input { padding-left: 36px; }

/* ===== TOGGLE SWITCH ===== */
.toggle-field {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--bg-surface-2, #f8fafc);
    border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    gap: 12px;
}

.toggle-field:hover {
    border-color: var(--brand-green, #2d9c4f);
    background: rgba(45,156,79,0.04);
}

.toggle-field-text p {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--text-primary, #0f1923);
}

.toggle-field-text span {
    font-size: 11.5px;
    color: var(--text-muted, #94a3b8);
}

.toggle-switch {
    position: relative;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}

.toggle-switch input { display: none; }

.toggle-slider {
    position: absolute;
    inset: 0;
    border-radius: 12px;
    background: var(--border-color, #e2e8f0);
    cursor: pointer;
    transition: background 0.25s ease;
}

.toggle-slider::before {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: white;
    top: 3px;
    left: 3px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.15);
    transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
}

.toggle-switch input:checked + .toggle-slider {
    background: var(--brand-green, #2d9c4f);
}

.toggle-switch input:checked + .toggle-slider::before {
    transform: translateX(20px);
}

/* ===== IMAGE UPLOAD ===== */
.existing-img {
    position: relative;
    width: 100%;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 12px;
}

.existing-img img {
    width: 100%;
    height: 140px;
    object-fit: cover;
}

.existing-img-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: var(--brand-blue, #2563a8);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    pointer-events: none;
}

.clear-img-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(239,68,68,0.9);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    z-index: 10;
}

.clear-img-btn:hover {
    background: #ef4444;
}

.img-preview-box {
    width: 100%;
    height: 140px;
    border-radius: 14px;
    border: 2px dashed var(--border-color, #e2e8f0);
    background: var(--bg-surface-2, #f8fafc);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.25s ease;
    position: relative;
}

.img-preview-box:hover { border-color: var(--brand-blue, #2563a8); background: rgba(37,99,168,0.04); }

.img-preview-box input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}

.img-preview-box img#imgPreview {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
    display: none;
}

.img-placeholder { display: flex; flex-direction: column; align-items: center; gap: 6px; color: var(--text-muted, #94a3b8); }
.img-placeholder svg { opacity: 0.5; }
.img-placeholder span { font-size: 12px; font-weight: 500; }

.img-upload-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: var(--brand-blue, #2563a8);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    pointer-events: none;
}

/* ===== PREVIEW CARD ===== */
.preview-card {
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border: 1px solid #bae6fd;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 20px;
}

.preview-card-header {
    background: #0284c7;
    padding: 14px 18px;
    color: white;
}

.preview-card-header h3 {
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}

.preview-card-body {
    padding: 20px;
}

.preview-article {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
}

.preview-image {
    width: 100%;
    height: 120px;
    background: linear-gradient(135deg, #cbd5e1, #e2e8f0);
    border-radius: 12px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 12px;
    overflow: hidden;
}

.preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-title {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
    font-family: 'Outfit', sans-serif;
}

.preview-date {
    font-size: 11px;
    color: #94a3b8;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.preview-extrait {
    font-size: 12px;
    color: #475569;
    line-height: 1.5;
    margin-bottom: 12px;
    font-style: italic;
}

.preview-readmore {
    font-size: 11px;
    color: #0284c7;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ===== SIDEBAR ===== */
.form-sidebar {
    position: sticky;
    top: 88px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.submit-card {
    background: var(--bg-surface, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 20px;
    overflow: hidden;
    margin-top: 20px;
}

.submit-card-head {
    background: linear-gradient(135deg, #2563a8, #3b82c4);
    padding: 16px 20px;
    color: white;
}

.submit-card-head h3 {
    font-family: 'Outfit', sans-serif;
    font-size: 14px;
    font-weight: 700;
}

.submit-card-head p {
    font-size: 11.5px;
    opacity: 0.8;
    margin-top: 2px;
}

.submit-card-body { padding: 18px; }

.btn-submit {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 20px;
    background: linear-gradient(135deg, #2563a8, #3b82c4);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    font-family: 'Outfit', sans-serif;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(37,99,168,0.3);
    margin-bottom: 10px;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(37,99,168,0.4);
    background: linear-gradient(135deg, #1d4d8a, #2563a8);
}

.btn-submit:active { transform: scale(0.98); }

.btn-cancel {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 11px 20px;
    background: var(--bg-surface-2, #f8fafc);
    color: var(--text-secondary, #4a5568);
    border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    text-align: center;
}

.btn-cancel:hover {
    background: var(--border-color, #e2e8f0);
    color: var(--text-primary, #0f1923);
}

.tips-card {
    background: var(--bg-surface, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 20px;
    padding: 18px;
    margin-top: 20px;
}

.tips-title {
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary, #0f1923);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 7px;
}

.tips-title svg { color: var(--brand-orange, #e8722a); }

.tip-item {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    padding: 7px 0;
    border-bottom: 1px solid var(--border-subtle, #eef2f7);
    font-size: 12px;
    color: var(--text-secondary, #4a5568);
    line-height: 1.5;
}

.tip-item:last-child { border-bottom: none; }

.tip-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 5px;
}

.form-status-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    background: rgba(45,156,79,0.08);
    border: 1px solid rgba(45,156,79,0.2);
    border-radius: 10px;
    margin-bottom: 12px;
    font-size: 12px;
    color: var(--brand-green, #2d9c4f);
    font-weight: 500;
}

.field-input.has-error,
.field-textarea.has-error {
    border-color: #ef4444;
    background: rgba(239,68,68,0.03);
}

.field-error-msg {
    font-size: 11px;
    color: #ef4444;
    font-weight: 500;
    display: none;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
}

.field-error-msg.show { display: flex; }

.textarea-wrap { position: relative; }

.char-count {
    position: absolute;
    bottom: 9px;
    right: 12px;
    font-size: 10.5px;
    color: var(--text-muted, #94a3b8);
    pointer-events: none;
    font-family: 'DM Sans', sans-serif;
    background: var(--bg-surface-2, #f8fafc);
    padding: 1px 6px;
    border-radius: 8px;
}

@media (max-width: 900px) {
    .form-shell { grid-template-columns: 1fr; }
    .form-sidebar { position: static; }
    .preview-card { position: static; margin-top: 24px; }
}

@media (max-width: 640px) {
    .field-row { grid-template-columns: 1fr; }
}
</style>

<div class="form-page">

    {{-- Breadcrumb --}}
    <div class="form-breadcrumb">
        <a href="{{ route('admin.articles.index') }}">Articles</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="#">Modifier</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>{{ $article->titre }}</span>
    </div>

    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="articleForm" novalidate>
        @csrf
        @method('PUT')

        <div class="form-shell">

            {{-- ===== LEFT COLUMN ===== --}}
            <div>

                {{-- Section 1 : Informations générales --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-blue">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Informations générales</h3>
                            <p>Titre, date et image de couverture</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-row">
                            {{-- Titre --}}
                            <div class="field-group">
                                <label class="field-label" for="titre">
                                    Titre de l'article
                                    <span class="required-dot"></span>
                                </label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                    </span>
                                    <input type="text" id="titre" name="titre" class="field-input" placeholder="Ex : Les nouvelles tendances RH 2025" required maxlength="200" value="{{ old('titre', $article->titre) }}">
                                </div>
                                <div class="field-error-msg" id="titreError">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    Le titre est requis
                                </div>
                            </div>

                            {{-- Date publication --}}
                            <div class="field-group">
                                <label class="field-label" for="date_publication">
                                    Date de publication
                                    <span class="required-dot"></span>
                                </label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </span>
                                    <input type="date" id="date_publication" name="date_publication" class="field-input" required value="{{ old('date_publication', $article->date_publication ? $article->date_publication->format('Y-m-d') : date('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Image à la une --}}
                        <div class="field-group">
                            <label class="field-label">Image à la une</label>

                            @if($article->image_une)
                            <div class="existing-img" id="existingImgBlock">
                                <img src="{{ asset('storage/'.$article->image_une) }}" alt="Image actuelle">
                                <button type="button" class="clear-img-btn" id="clearImgBtn">✕ Supprimer</button>
                                <span class="existing-img-badge">Image actuelle</span>
                            </div>
                            @endif

                            <div class="img-preview-box" id="imgBox">
                                <input type="file" name="image_une" id="imageInput" accept="image/*">
                                <img id="imgPreview" src="" alt="Aperçu">
                                <div class="img-placeholder" id="imgPlaceholder">
                                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    <span>Cliquer pour modifier</span>
                                    <span style="font-size:10.5px;">JPG, PNG, WEBP — max 5 Mo</span>
                                </div>
                                <span class="img-upload-badge" id="imgBadge">+ Image</span>
                            </div>
                            <span class="field-hint">Laissez vide pour conserver l'image actuelle</span>
                        </div>

                    </div>
                </div>

                {{-- Section 2 : Contenu de l'article --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-purple">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Contenu de l'article</h3>
                            <p>Extrait (résumé) et contenu principal</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        {{-- Extrait --}}
                        <div class="field-group" style="margin-bottom: 20px;">
                            <label class="field-label" for="extrait">Extrait (résumé)</label>
                            <div class="textarea-wrap" style="position: relative;">
                                <textarea id="extrait" name="extrait" class="field-textarea" rows="3" maxlength="300" placeholder="Résumé de l'article qui apparaîtra dans les cartes…">{{ old('extrait', $article->extrait) }}</textarea>
                                <span class="char-count"><span id="extraitCount">0</span>/300</span>
                            </div>
                            <span class="field-hint">Ce texte apparaît dans la carte de l'article sur la page d'accueil</span>
                        </div>

                        {{-- Contenu principal --}}
                        <div class="field-group">
                            <label class="field-label" for="contenu">
                                Contenu
                                <span class="required-dot"></span>
                            </label>
                            <div class="textarea-wrap" style="position: relative;">
                                <textarea id="contenu" name="contenu" class="field-textarea content-editor" rows="12" placeholder="Rédigez votre article ici… (HTML accepté)" required>{{ old('contenu', $article->contenu) }}</textarea>
                                <span class="char-count"><span id="contentCount">0</span> caractères</span>
                            </div>
                            <div class="field-error-msg" id="contenuError">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                Le contenu est requis
                            </div>
                            <span class="field-hint">Vous pouvez utiliser du HTML : &lt;p&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;h2&gt;, &lt;h3&gt;</span>
                        </div>

                    </div>
                </div>

                {{-- Section 3 : Publication --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-green">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Publication</h3>
                            <p>Visibilité de l'article sur le site</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <label class="toggle-field" for="publieToggle">
                            <div class="toggle-field-text">
                                <p>Article publié</p>
                                <span>Visible immédiatement sur le blog</span>
                            </div>
                            <div class="toggle-switch">
                                <input type="checkbox" id="publieToggle" name="publie" value="1" {{ old('publie', $article->publie) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </div>
                        </label>

                    </div>
                </div>

            </div>{{-- end left column --}}

            {{-- ===== RIGHT SIDEBAR ===== --}}
            <div class="form-sidebar">

                {{-- Preview Card (aperçu en temps réel) --}}
                <div class="preview-card">
                    <div class="preview-card-header">
                        <h3>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Aperçu en direct
                        </h3>
                        <p style="font-size: 10px; opacity: 0.7;">Ce que verra le lecteur</p>
                    </div>
                    <div class="preview-card-body">
                        <div class="preview-article">
                            <div class="preview-image" id="previewImage">
                                <img id="previewImg" src="" style="display: none;">
                                <span id="previewPlaceholder">🖼️ Image à la une</span>
                            </div>
                            <div class="preview-title" id="previewTitle">Titre de l'article</div>
                            <div class="preview-date" id="previewDate">📅 --/--/----</div>
                            <div class="preview-extrait" id="previewExtrait">L'extrait de l'article apparaîtra ici...</div>
                            <div class="preview-readmore">Lire la suite →</div>
                        </div>
                    </div>
                </div>

                {{-- Submit Card --}}
                <div class="submit-card">
                    <div class="submit-card-head">
                        <h3>Mettre à jour</h3>
                        <p>Enregistrez les modifications</p>
                    </div>
                    <div class="submit-card-body">

                        <div class="form-status-bar" id="formStatusBar">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Formulaire en cours…
                        </div>

                        <button type="submit" class="btn-submit" id="submitBtn">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Mettre à jour
                        </button>

                        <a href="{{ route('admin.articles.index') }}" class="btn-cancel">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Annuler
                        </a>

                    </div>
                </div>

                {{-- Conseils rédactionnels --}}
                <div class="tips-card">
                    <div class="tips-title">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Conseils
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: #2563a8"></span>
                        Un titre accrocheur augmente le taux de clics
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: #2d9c4f"></span>
                        L'extrait doit être court et percutant (150-200 caractères)
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: #e8722a"></span>
                        Une image de qualité améliore l'engagement
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: #d63384"></span>
                        Structurez votre contenu avec des sous-titres (h2, h3)
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--text-muted, #94a3b8);padding:0 2px;">
                    <span class="required-dot"></span>
                    Champs obligatoires
                </div>

            </div>{{-- end right sidebar --}}

        </div>{{-- end form-shell --}}
    </form>
</div>

<script>
(function() {
    'use strict';

    /* ============================================================
       CHAR COUNTERS
    ============================================================ */
    const extraitTextarea = document.getElementById('extrait');
    const extraitCount = document.getElementById('extraitCount');
    if (extraitTextarea && extraitCount) {
        function updateExtraitCount() {
            const len = extraitTextarea.value.length;
            extraitCount.textContent = len;
            extraitCount.style.color = len > 270 ? (len >= 300 ? '#ef4444' : 'var(--brand-orange, #e8722a)') : '';
        }
        extraitTextarea.addEventListener('input', updateExtraitCount);
        updateExtraitCount();
    }

    const contenuTextarea = document.getElementById('contenu');
    const contentCount = document.getElementById('contentCount');
    if (contenuTextarea && contentCount) {
        function updateContentCount() {
            contentCount.textContent = contenuTextarea.value.length;
        }
        contenuTextarea.addEventListener('input', updateContentCount);
        updateContentCount();
    }

    /* ============================================================
       PREVIEW EN TEMPS RÉEL
    ============================================================ */
    const titreInput = document.getElementById('titre');
    const dateInput = document.getElementById('date_publication');
    const extraitInput = document.getElementById('extrait');
    const previewTitle = document.getElementById('previewTitle');
    const previewDate = document.getElementById('previewDate');
    const previewExtrait = document.getElementById('previewExtrait');

    function formatDate(dateStr) {
        if (!dateStr) return '--/--/----';
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return dateStr;
    }

    function updatePreview() {
        const titre = titreInput?.value.trim() || 'Titre de l\'article';
        const date = dateInput?.value || '';
        const extrait = extraitInput?.value.trim() || 'L\'extrait de l\'article apparaîtra ici...';
        
        previewTitle.textContent = titre;
        previewDate.textContent = `📅 ${formatDate(date)}`;
        previewExtrait.textContent = extrait;
    }

    titreInput?.addEventListener('input', updatePreview);
    dateInput?.addEventListener('change', updatePreview);
    extraitInput?.addEventListener('input', updatePreview);
    updatePreview();

    /* ============================================================
       FORM STATUS BAR
    ============================================================ */
    const statusBar = document.getElementById('formStatusBar');

    function updateFormStatus() {
        const titre = titreInput?.value.trim() || '';
        const contenu = contenuTextarea?.value.trim() || '';
        const date = dateInput?.value || '';

        if (titre && contenu && date) {
            statusBar.innerHTML = `
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Prêt à publier
            `;
            statusBar.style.background = 'rgba(45,156,79,0.08)';
            statusBar.style.borderColor = 'rgba(45,156,79,0.2)';
            statusBar.style.color = 'var(--brand-green, #2d9c4f)';
        } else {
            statusBar.innerHTML = `
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Formulaire en cours…
            `;
            statusBar.style.background = 'rgba(37,99,168,0.06)';
            statusBar.style.borderColor = 'rgba(37,99,168,0.15)';
            statusBar.style.color = 'var(--brand-blue, #2563a8)';
        }
    }

    titreInput?.addEventListener('input', updateFormStatus);
    contenuTextarea?.addEventListener('input', updateFormStatus);
    dateInput?.addEventListener('change', updateFormStatus);
    updateFormStatus();

    /* ============================================================
       IMAGE UPLOAD
    ============================================================ */
    const imageInput = document.getElementById('imageInput');
    const imgPreview = document.getElementById('imgPreview');
    const imgPlaceholder = document.getElementById('imgPlaceholder');
    const imgBadge = document.getElementById('imgBadge');
    const existingImgBlock = document.getElementById('existingImgBlock');
    const clearImgBtn = document.getElementById('clearImgBtn');
    const previewImg = document.getElementById('previewImg');
    const previewPlaceholder = document.getElementById('previewPlaceholder');

    const ALLOWED_IMG_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    const MAX_IMG_SIZE = 5 * 1024 * 1024;

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' o';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' Ko';
        return (bytes / (1024 * 1024)).toFixed(2) + ' Mo';
    }

    imageInput?.addEventListener('change', function() {
        const file = this.files?.[0];
        if (!file) return;

        if (!ALLOWED_IMG_TYPES.includes(file.type)) {
            alert('Format non supporté. Utilisez JPG, PNG, WEBP ou GIF.');
            this.value = '';
            return;
        }

        if (file.size > MAX_IMG_SIZE) {
            alert(`Image trop lourde. Taille max : 5 Mo (${formatBytes(file.size)}).`);
            this.value = '';
            return;
        }

        if (existingImgBlock) existingImgBlock.style.display = 'none';

        const reader = new FileReader();
        reader.onload = e => {
            // Preview dans le formulaire
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
            imgPlaceholder.style.display = 'none';
            imgBadge.textContent = '✓ Image modifiée';
            imgBadge.style.background = 'var(--brand-green, #2d9c4f)';
            
            // Preview dans la carte
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            previewPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    clearImgBtn?.addEventListener('click', function() {
        if (confirm('Supprimer l\'image actuelle ?')) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'delete_image';
            hiddenInput.value = '1';
            document.getElementById('articleForm').appendChild(hiddenInput);
            
            if (existingImgBlock) existingImgBlock.style.display = 'none';
            imgBadge.textContent = 'Image supprimée';
            imgBadge.style.background = '#ef4444';
            
            // Supprimer aussi dans la preview
            previewImg.style.display = 'none';
            previewPlaceholder.style.display = 'flex';
        }
    });

    /* ============================================================
       FORM VALIDATION
    ============================================================ */
    const form = document.getElementById('articleForm');
    const submitBtn = document.getElementById('submitBtn');
    const titreError = document.getElementById('titreError');
    const contenuError = document.getElementById('contenuError');

    form?.addEventListener('submit', function(e) {
        let valid = true;

        const titre = titreInput?.value.trim() || '';
        if (!titre) {
            e.preventDefault();
            titreInput?.classList.add('has-error');
            titreError?.classList.add('show');
            valid = false;
        } else {
            titreInput?.classList.remove('has-error');
            titreError?.classList.remove('show');
        }

        const contenu = contenuTextarea?.value.trim() || '';
        if (!contenu) {
            e.preventDefault();
            contenuTextarea?.classList.add('has-error');
            contenuError?.classList.add('show');
            valid = false;
        } else {
            contenuTextarea?.classList.remove('has-error');
            contenuError?.classList.remove('show');
        }

        if (!valid) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation:spin 1s linear infinite">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Mise à jour…
        `;
    });

    titreInput?.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('has-error');
            titreError?.classList.remove('show');
        }
    });

    contenuTextarea?.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('has-error');
            contenuError?.classList.remove('show');
        }
    });

    const spinStyle = document.createElement('style');
    spinStyle.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(spinStyle);

})();
</script>

@endsection