@extends('layouts.admin')

@section('page-title', 'Nouvelle formation')
@section('page-subtitle', 'Créer une nouvelle session de formation')

@section('content')

<style>
/* ============================================================
   FORMATIONS CREATE — Design system harmonisé
============================================================ */
.form-page {
    max-width: 1000px;
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
    grid-template-columns: 1fr 280px;
    gap: 20px;
    align-items: start;
}

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
.icon-orange { background: rgba(232,114,42,0.12); color: var(--brand-orange, #e8722a); }
.icon-purple { background: rgba(139,92,246,0.12); color: #8b5cf6; }
.icon-teal   { background: rgba(26,143,160,0.12); color: var(--brand-teal, #1a8fa0); }

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

.field-textarea.short { min-height: 80px; }

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
    border-radius: 18px;
    overflow: hidden;
    animation: fadeUp 0.35s ease 0.25s both;
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
    border-radius: 18px;
    padding: 18px;
    animation: fadeUp 0.35s ease 0.3s both;
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
}

@media (max-width: 640px) {
    .field-row { grid-template-columns: 1fr; }
}
</style>
@if($errors->any())
    <div style="background: #fef2f2; border: 1px solid #ef4444; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="form-page">

    {{-- Breadcrumb --}}
    <div class="form-breadcrumb">
        <a href="{{ route('admin.formations.index') }}">Formations</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>Nouvelle formation</span>
    </div>

    <form action="{{ route('admin.formations.store') }}" method="POST" enctype="multipart/form-data" id="formationForm" novalidate>
        @csrf

        <div class="form-shell">

            {{-- ===== LEFT COLUMN ===== --}}
            <div>

                {{-- Section 1 : Informations générales --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-blue">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Informations générales</h3>
                            <p>Identité et configuration de la formation</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        {{-- Catalogue associé --}}
                        <div class="field-group" style="margin-bottom: 16px;">
                            <label class="field-label" for="catalogue_id">
                                Catalogue associé
                                <span class="required-dot"></span>
                            </label>
                            <select id="catalogue_id" name="catalogue_id" class="field-select" required>
                                <option value="">-- Sélectionnez un catalogue --</option>
                                @foreach($catalogues as $catalogue)
                                    <option value="{{ $catalogue->id }}" {{ old('catalogue_id') == $catalogue->id ? 'selected' : '' }}>
                                        {{ $catalogue->titre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="field-error-msg" id="catalogueError">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                Veuillez sélectionner un catalogue
                            </div>
                        </div>

                        {{-- Titre --}}
                        <div class="field-group" style="margin-bottom: 16px;">
                            <label class="field-label" for="titre">
                                Titre de la formation
                                <span class="required-dot"></span>
                            </label>
                            <div class="field-input-wrap">
                                <span class="input-icon">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </span>
                                <input type="text" id="titre" name="titre" class="field-input" placeholder="Ex : Management des équipes" required maxlength="200" value="{{ old('titre') }}">
                            </div>
                            <div class="field-error-msg" id="titreError">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                Le titre est requis
                            </div>
                        </div>

                        {{-- Lieu --}}
                        <div class="field-group" style="margin-bottom: 16px;">
                            <label class="field-label" for="lieu">
                                Lieu
                                <span class="required-dot"></span>
                            </label>
                            <div class="field-input-wrap">
                                <span class="input-icon">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <input type="text" id="lieu" name="lieu" class="field-input" placeholder="Ex : Antananarivo, en ligne" required maxlength="150" value="{{ old('lieu') }}">
                            </div>
                        </div>

                        {{-- Actif toggle --}}
                        <div class="field-group" style="margin-bottom: 0;">
                            <label class="field-label">Statut</label>
                            <label class="toggle-field" for="actifToggle">
                                <div class="toggle-field-text">
                                    <p>Formation active</p>
                                    <span>Visible sur le calendrier public</span>
                                </div>
                                <div class="toggle-switch">
                                    <input type="checkbox" id="actifToggle" name="actif" value="1" checked>
                                    <span class="toggle-slider"></span>
                                </div>
                            </label>
                        </div>

                    </div>
                </div>

                {{-- Section 2 : Dates & horaires --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-green">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Dates & horaires</h3>
                            <p>Programmation de la formation</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-row">
                            <div class="field-group">
                                <label class="field-label" for="date_debut">
                                    Date de début
                                    <span class="required-dot"></span>
                                </label>
                                <input type="date" id="date_debut" name="date_debut" class="field-input" required value="{{ old('date_debut') }}">
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="date_fin">Date de fin</label>
                                <input type="date" id="date_fin" name="date_fin" class="field-input" value="{{ old('date_fin') }}">
                                <span class="field-hint">Laissez vide si journée unique</span>
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="field-group">
                                <label class="field-label" for="heure">Heure</label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    </span>
                                    <input type="time" id="heure" name="heure" class="field-input" value="{{ old('heure') }}">
                                </div>
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="places_max">Places maximum</label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </span>
                                    <input type="number" id="places_max" name="places_max" class="field-input" min="0" value="{{ old('places_max') }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Section 3 : Tarifs & inscription --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-orange">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Tarifs & inscription</h3>
                            <p>Prix et lien d'inscription</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-row">
                            <div class="field-group">
                                <label class="field-label" for="prix">Prix (Ar)</label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <input type="number" id="prix" name="prix" class="field-input" min="0" step="1000" value="{{ old('prix') }}">
                                </div>
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="lien_inscription">Lien d'inscription externe</label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    </span>
                                    <input type="url" id="lien_inscription" name="lien_inscription" class="field-input" placeholder="https://exemple.com/inscription" value="{{ old('lien_inscription') }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Section 4 : Description --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-teal">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Description</h3>
                            <p>Présentation de la formation</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-group" style="margin-bottom: 20px;">
                            <label class="field-label" for="description_courte">Description courte</label>
                            <div class="textarea-wrap">
                                <textarea id="description_courte" name="description_courte" class="field-textarea short" rows="3" maxlength="300" placeholder="Résumé de la formation…">{{ old('description_courte') }}</textarea>
                                <span class="char-count"><span id="courteCount">0</span>/300</span>
                            </div>
                            <span class="field-hint">Apparaît dans les cartes du calendrier</span>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="description">Description détaillée</label>
                            <div class="textarea-wrap">
                                <textarea id="description" name="description" class="field-textarea" rows="6" placeholder="Programme détaillé, prérequis, intervenants…">{{ old('description') }}</textarea>
                                <span class="char-count"><span id="descCount">0</span> caractères</span>
                            </div>
                            <span class="field-hint">HTML accepté pour la mise en forme</span>
                        </div>

                    </div>
                </div>

                {{-- Section 5 : Image --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-purple">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Image de couverture</h3>
                            <p>Visuel de la formation</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="img-preview-box" id="imgBox">
                            <input type="file" name="image" id="imageInput" accept="image/*">
                            <img id="imgPreview" src="" alt="Aperçu">
                            <div class="img-placeholder" id="imgPlaceholder">
                                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <span>Cliquer pour importer</span>
                                <span style="font-size:10.5px;">JPG, PNG, WEBP — max 5 Mo</span>
                            </div>
                            <span class="img-upload-badge" id="imgBadge">+ Image</span>
                        </div>

                    </div>
                </div>

            </div>{{-- end left column --}}

            {{-- ===== RIGHT SIDEBAR ===== --}}
            <div class="form-sidebar">

                {{-- Submit Card --}}
                <div class="submit-card">
                    <div class="submit-card-head">
                        <h3>Publier la formation</h3>
                        <p>Vérifiez les informations</p>
                    </div>
                    <div class="submit-card-body">

                        <div class="form-status-bar" id="formStatusBar">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Formulaire en cours…
                        </div>

                        <button type="submit" class="btn-submit" id="submitBtn">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Enregistrer la formation
                        </button>

                        <a href="{{ route('admin.formations.index') }}" class="btn-cancel">
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
                        Une image attractive augmente les inscriptions.
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-green, #2d9c4f)"></span>
                        Renseignez toutes les dates pour un meilleur affichage.
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-orange, #e8722a)"></span>
                        Le prix peut être laissé vide si la formation est gratuite.
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-pink, #d63384)"></span>
                        Désactivez la formation pour la masquer temporairement.
                    </div>
                </div>

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
       CHAR COUNTERS
    ============================================================ */
    const courteTextarea = document.getElementById('description_courte');
    const courteCount = document.getElementById('courteCount');
    if (courteTextarea && courteCount) {
        function updateCourte() {
            const len = courteTextarea.value.length;
            courteCount.textContent = len;
            courteCount.style.color = len > 270 ? (len >= 300 ? '#ef4444' : 'var(--brand-orange, #e8722a)') : '';
        }
        courteTextarea.addEventListener('input', updateCourte);
        updateCourte();
    }

    const descTextarea = document.getElementById('description');
    const descCount = document.getElementById('descCount');
    if (descTextarea && descCount) {
        function updateDesc() {
            descCount.textContent = descTextarea.value.length;
        }
        descTextarea.addEventListener('input', updateDesc);
        updateDesc();
    }

    /* ============================================================
       FORM STATUS BAR
    ============================================================ */
    const statusBar = document.getElementById('formStatusBar');
    const titreInput = document.getElementById('titre');
    const lieuInput = document.getElementById('lieu');
    const dateDebutInput = document.getElementById('date_debut');
    const catalogueSelect = document.getElementById('catalogue_id');

    function updateStatus() {
        const titre = titreInput?.value.trim() || '';
        const lieu = lieuInput?.value.trim() || '';
        const dateDebut = dateDebutInput?.value || '';
        const catalogue = catalogueSelect?.value || '';

        if (titre && lieu && dateDebut && catalogue) {
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

    titreInput?.addEventListener('input', updateStatus);
    lieuInput?.addEventListener('input', updateStatus);
    dateDebutInput?.addEventListener('change', updateStatus);
    catalogueSelect?.addEventListener('change', updateStatus);
    updateStatus();

    /* ============================================================
       IMAGE UPLOAD
    ============================================================ */
    const imageInput = document.getElementById('imageInput');
    const imgPreview = document.getElementById('imgPreview');
    const imgPlaceholder = document.getElementById('imgPlaceholder');
    const imgBadge = document.getElementById('imgBadge');

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

        const reader = new FileReader();
        reader.onload = e => {
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
            imgPlaceholder.style.display = 'none';
            imgBadge.textContent = '✓ Image ajoutée';
            imgBadge.style.background = 'var(--brand-green, #2d9c4f)';
        };
        reader.readAsDataURL(file);
    });

    /* ============================================================
       FORM VALIDATION
    ============================================================ */
    const form = document.getElementById('formationForm');
    const submitBtn = document.getElementById('submitBtn');
    const titreError = document.getElementById('titreError');
    const catalogueError = document.getElementById('catalogueError');

    form?.addEventListener('submit', function(e) {
        let valid = true;

        // Catalogue
        const catalogue = catalogueSelect?.value || '';
        if (!catalogue) {
            e.preventDefault();
            catalogueSelect?.classList.add('has-error');
            catalogueError?.classList.add('show');
            valid = false;
        } else {
            catalogueSelect?.classList.remove('has-error');
            catalogueError?.classList.remove('show');
        }

        // Titre
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

        if (!valid) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation:spin 1s linear infinite">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Enregistrement…
        `;
    });

    titreInput?.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('has-error');
            titreError?.classList.remove('show');
        }
    });

    catalogueSelect?.addEventListener('change', function() {
        if (this.value) {
            this.classList.remove('has-error');
            catalogueError?.classList.remove('show');
        }
    });

    const spinStyle = document.createElement('style');
    spinStyle.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(spinStyle);

})();
</script>

@endsection