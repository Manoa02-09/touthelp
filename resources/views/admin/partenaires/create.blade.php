@extends('layouts.admin')

@section('page-title', 'Nouveau partenaire')
@section('page-subtitle', 'Ajouter un partenaire ou client à la section "Ils nous font confiance"')

@section('content')

<style>
/* ============================================================
   PARTENAIRES CREATE — Design system harmonisé admin Tout Help
============================================================ */
.form-page {
    max-width: 1000px;
}

/* ===== BREADCRUMB ===== */
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

/* ===== FORM LAYOUT ===== */
.form-shell {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 20px;
    align-items: start;
}

/* ===== SECTION CARDS ===== */
.form-section {
    background: var(--bg-surface, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 18px;
    overflow: hidden;
    margin-bottom: 16px;
    transition: box-shadow 0.2s ease;
    animation: fadeUp 0.35s ease both;
}

.form-section:hover {
    box-shadow: 0 4px 20px rgba(37,99,168,0.07);
}

.form-section:nth-child(1) { animation-delay: 0.05s; }
.form-section:nth-child(2) { animation-delay: 0.10s; }

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
.icon-green  { background: rgba(45,156,79,0.12);  color: var(--brand-green, #2d9c4f); }
.icon-purple { background: rgba(139,92,246,0.12); color: #8b5cf6; }

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

/* ===== FIELD GROUPS ===== */
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

/* ===== INPUTS ===== */
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
    min-height: 90px;
    line-height: 1.6;
}

/* Input with icon */
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

/* ===== TOGGLE SWITCH (actif) ===== */
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
.img-upload-wrap {
    position: relative;
    width: 100%;
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

/* ===== SIDEBAR (right panel) ===== */
.form-sidebar {
    position: sticky;
    top: 88px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* Submit card */
.submit-card {
    background: var(--bg-surface, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 18px;
    overflow: hidden;
    animation: fadeUp 0.35s ease 0.15s both;
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
    border-color: #cbd5e1;
}

/* Tips card */
.tips-card {
    background: var(--bg-surface, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 18px;
    padding: 18px;
    animation: fadeUp 0.35s ease 0.2s both;
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

/* Form status bar */
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

/* ===== ERROR STATES ===== */
.field-input.has-error {
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

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .form-shell { grid-template-columns: 1fr; }
    .form-sidebar { position: static; }
}

@media (max-width: 640px) {
    .field-row { grid-template-columns: 1fr; }
}
</style>

<div class="form-page">

    {{-- Breadcrumb --}}
    <div class="form-breadcrumb">
        <a href="{{ route('admin.partenaires.index') }}">Partenaires</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>Nouveau partenaire</span>
    </div>

    <form action="{{ route('admin.partenaires.store') }}" method="POST" enctype="multipart/form-data" id="partenaireForm" novalidate>
        @csrf

        <div class="form-shell">

            {{-- ===== LEFT COLUMN ===== --}}
            <div>

                {{-- Section 1 : Informations du partenaire --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-blue">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1M5 21h14"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Identité du partenaire</h3>
                            <p>Informations générales et visibilité</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-row">
                            {{-- Nom entreprise --}}
                            <div class="field-group">
                                <label class="field-label" for="nom_entreprise">
                                    Nom de l'entreprise
                                    <span class="required-dot"></span>
                                </label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1M5 21h14"/></svg>
                                    </span>
                                    <input type="text" id="nom_entreprise" name="nom_entreprise" class="field-input" placeholder="Ex : Société Générale" required maxlength="150" value="{{ old('nom_entreprise') }}">
                                </div>
                                <div class="field-error-msg" id="nomError">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    Le nom est requis
                                </div>
                            </div>

                            {{-- Ordre d'affichage --}}
                            <div class="field-group">
                                <label class="field-label" for="ordre_affichage">Ordre d'affichage</label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                                    </span>
                                    <input type="number" id="ordre_affichage" name="ordre_affichage" class="field-input" value="{{ old('ordre_affichage', 0) }}" min="0" max="999">
                                    <span class="input-suffix">position</span>
                                </div>
                                <span class="field-hint">Plus le chiffre est petit, plus il apparaît en premier</span>
                            </div>
                        </div>

                        {{-- Site web --}}
                        <div class="field-group" style="margin-bottom:16px;">
                            <label class="field-label" for="site_web">Site web</label>
                            <div class="field-input-wrap">
                                <span class="input-icon">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                </span>
                                <input type="url" id="site_web" name="site_web" class="field-input" placeholder="https://www.exemple.com" value="{{ old('site_web') }}">
                            </div>
                            <span class="field-hint">Lien cliquable sur la page partenaire</span>
                        </div>

                        {{-- Description --}}
                        <div class="field-group" style="margin-bottom:0;">
                            <label class="field-label" for="description">Description</label>
                            <div class="textarea-wrap" style="position:relative; margin-top:6px;">
                                <textarea id="description" name="description" class="field-textarea" rows="3" placeholder="Brève présentation du partenaire…" maxlength="500">{{ old('description') }}</textarea>
                                <span class="char-count" style="position:absolute;bottom:8px;right:12px;font-size:10px;color:var(--text-muted)"><span id="descCount">0</span>/500</span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Section 2 : Logo & visibilité --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-green">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Logo & visibilité</h3>
                            <p>Image du partenaire et statut d'affichage</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-row">
                            {{-- Logo upload --}}
                            <div class="field-group">
                                <label class="field-label">Logo du partenaire</label>
                                <div class="img-preview-box" id="imgBox">
                                    <input type="file" name="logo" id="imageInput" accept="image/*">
                                    <img id="imgPreview" src="" alt="Aperçu">
                                    <div class="img-placeholder" id="imgPlaceholder">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        <span>Cliquer pour importer</span>
                                        <span style="font-size:10.5px;">JPG, PNG, WEBP — max 2 Mo</span>
                                    </div>
                                    <span class="img-upload-badge" id="imgBadge">+ Logo</span>
                                </div>
                            </div>

                            {{-- Toggle actif --}}
                            <div class="field-group">
                                <label class="field-label">Statut</label>
                                <label class="toggle-field" for="actifToggle">
                                    <div class="toggle-field-text">
                                        <p>Partenaire actif</p>
                                        <span>Visible sur la page d'accueil</span>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="actifToggle" name="actif" value="1" {{ old('actif', 1) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

            </div>{{-- end left column --}}

            {{-- ===== RIGHT SIDEBAR ===== --}}
            <div class="form-sidebar">

                {{-- Submit Card --}}
                <div class="submit-card">
                    <div class="submit-card-head">
                        <h3>Publier le partenaire</h3>
                        <p>Vérifiez les informations</p>
                    </div>
                    <div class="submit-card-body">

                        <div class="form-status-bar" id="formStatusBar">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Formulaire en cours…
                        </div>

                        <button type="submit" class="btn-submit" id="submitBtn">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Enregistrer le partenaire
                        </button>

                        <a href="{{ route('admin.partenaires.index') }}" class="btn-cancel">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Annuler
                        </a>

                    </div>
                </div>

                {{-- Tips Card --}}
                <div class="tips-card">
                    <div class="tips-title">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Conseils
                    </div>

                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-blue, #2563a8)"></span>
                        Un logo de bonne qualité (carré de préférence) améliore l'affichage.
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-green, #2d9c4f)"></span>
                        L'ordre d'affichage détermine la position dans le carrousel.
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-orange, #e8722a)"></span>
                        Le site web s'ouvre dans un nouvel onglet au clic.
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-pink, #d63384)"></span>
                        Désactivez un partenaire sans le supprimer pour le cacher temporairement.
                    </div>
                </div>

                {{-- Champs requis --}}
                <div style="display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--text-muted, #94a3b8);padding:0 2px;">
                    <span class="required-dot"></span>
                    Champs obligatoires
                </div>

            </div>{{-- end sidebar --}}

        </div>{{-- end form-shell --}}
    </form>
</div>

<script>
(function() {
    'use strict';

    /* ============================================================
       CHAR COUNTER
    ============================================================ */
    const descTextarea = document.getElementById('description');
    const descCount = document.getElementById('descCount');
    if (descTextarea && descCount) {
        function updateDescCount() {
            const len = descTextarea.value.length;
            descCount.textContent = len;
            descCount.style.color = len > 450 ? (len >= 500 ? '#ef4444' : 'var(--brand-orange, #e8722a)') : '';
        }
        descTextarea.addEventListener('input', updateDescCount);
        updateDescCount();
    }

    /* ============================================================
       FORM STATUS BAR
    ============================================================ */
    const statusBar    = document.getElementById('formStatusBar');
    const nomInput     = document.getElementById('nom_entreprise');

    function updateStatus() {
        const nom = nomInput?.value.trim() || '';
        if (nom) {
            statusBar.innerHTML = `
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Prêt à publier
            `;
            statusBar.style.background  = 'rgba(45,156,79,0.08)';
            statusBar.style.borderColor = 'rgba(45,156,79,0.2)';
            statusBar.style.color       = 'var(--brand-green, #2d9c4f)';
        } else {
            statusBar.innerHTML = `
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Formulaire en cours…
            `;
            statusBar.style.background  = 'rgba(37,99,168,0.06)';
            statusBar.style.borderColor = 'rgba(37,99,168,0.15)';
            statusBar.style.color       = 'var(--brand-blue, #2563a8)';
        }
    }

    nomInput?.addEventListener('input', updateStatus);
    updateStatus();

    /* ============================================================
       IMAGE UPLOAD
    ============================================================ */
    const imageInput    = document.getElementById('imageInput');
    const imgPreview    = document.getElementById('imgPreview');
    const imgPlaceholder = document.getElementById('imgPlaceholder');
    const imgBadge      = document.getElementById('imgBadge');

    const ALLOWED_IMG_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    const MAX_IMG_SIZE = 2 * 1024 * 1024; // 2 Mo

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
            alert(`Image trop lourde. Taille max : 2 Mo (${formatBytes(file.size)}).`);
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
            imgPlaceholder.style.display = 'none';
            imgBadge.textContent = '✓ Logo ajouté';
            imgBadge.style.background = 'var(--brand-green, #2d9c4f)';
        };
        reader.readAsDataURL(file);
    });

    /* ============================================================
       FORM VALIDATION
    ============================================================ */
    const form      = document.getElementById('partenaireForm');
    const submitBtn = document.getElementById('submitBtn');
    const nomError  = document.getElementById('nomError');

    form?.addEventListener('submit', function(e) {
        let valid = true;

        const nom = nomInput?.value.trim() || '';
        if (!nom) {
            e.preventDefault();
            nomInput?.classList.add('has-error');
            nomError?.classList.add('show');
            nomInput?.focus();
            valid = false;
        } else {
            nomInput?.classList.remove('has-error');
            nomError?.classList.remove('show');
        }

        if (!valid) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation:spin 1s linear infinite">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Enregistrement…
        `;
    });

    nomInput?.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('has-error');
            nomError?.classList.remove('show');
        }
    });

    const spinStyle = document.createElement('style');
    spinStyle.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(spinStyle);

})();
</script>

@endsection