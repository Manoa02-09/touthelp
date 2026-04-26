@extends('layouts.admin')

@section('page-title', 'Modifier l\'avis')
@section('page-subtitle', 'Mettre à jour le témoignage client')

@section('content')

<style>
/* ============================================================
   AVIS EDIT — Design system harmonisé
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

.icon-amber  { background: rgba(245,158,11,0.12); color: #f59e0b; }
.icon-blue   { background: rgba(37,99,168,0.12); color: var(--brand-blue, #2563a8); }
.icon-purple { background: rgba(139,92,246,0.12); color: #8b5cf6; }
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
.field-row.triple { grid-template-columns: 1fr 1fr 1fr; }

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
    min-height: 100px;
    line-height: 1.6;
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

/* ===== STAR RATING ===== */
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    gap: 6px;
    justify-content: flex-end;
}

.star-rating input {
    display: none;
}

.star-rating label {
    font-size: 24px;
    color: #cbd5e1;
    cursor: pointer;
    transition: all 0.15s ease;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #f59e0b;
}

.star-rating input:checked ~ label {
    text-shadow: 0 0 2px rgba(245,158,11,0.4);
}

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
    border-color: var(--brand-purple, #8b5cf6);
    background: rgba(139,92,246,0.04);
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

/* ===== STATUS SELECT WITH BADGE ===== */
.status-select-wrap {
    position: relative;
}

.status-badge-preview {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    margin-top: 8px;
    width: fit-content;
}

.status-badge-preview.pending  { background: rgba(245,158,11,0.12); color: #f59e0b; }
.status-badge-preview.published { background: rgba(45,156,79,0.12);  color: #2d9c4f; }
.status-badge-preview.rejected { background: rgba(239,68,68,0.12);  color: #ef4444; }

/* ===== IMAGE UPLOAD ===== */
.existing-logo {
    position: relative;
    width: 100%;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 12px;
    background: var(--bg-surface-2, #f8fafc);
    padding: 16px;
    text-align: center;
    border: 1px solid var(--border-color, #e2e8f0);
}

.existing-logo img {
    max-height: 80px;
    max-width: 100%;
    object-fit: contain;
}

.clear-logo-btn {
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

.clear-logo-btn:hover {
    background: #ef4444;
}

.img-preview-box {
    width: 100%;
    height: 120px;
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
    object-fit: contain;
    background: var(--bg-surface-2, #f8fafc);
    display: none;
    padding: 16px;
}

.img-placeholder { display: flex; flex-direction: column; align-items: center; gap: 6px; color: var(--text-muted, #94a3b8); }
.img-placeholder svg { opacity: 0.5; }
.img-placeholder span { font-size: 12px; font-weight: 500; }
.img-placeholder small { font-size: 10.5px; }

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
    background: linear-gradient(135deg, #fef9e8, #fff5e6);
    border: 1px solid #fde68a;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 20px;
}

.preview-card-header {
    background: #f59e0b;
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

.preview-avis-content {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
}

.preview-stars {
    display: flex;
    gap: 4px;
    margin-bottom: 12px;
}

.preview-stars svg {
    width: 18px;
    height: 18px;
}

.preview-text {
    font-size: 13px;
    color: #4b5563;
    line-height: 1.6;
    font-style: italic;
    margin-bottom: 16px;
    min-height: 60px;
}

.preview-author {
    display: flex;
    align-items: center;
    gap: 12px;
}

.preview-avatar {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 14px;
}

.preview-author-info h4 {
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
}

.preview-author-info p {
    font-size: 11px;
    color: #9ca3af;
}

/* ===== SUBMIT CARD ===== */
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
    .field-row.triple { grid-template-columns: 1fr; }
}
</style>

<div class="form-page">

    {{-- Breadcrumb --}}
    <div class="form-breadcrumb">
        <a href="{{ route('admin.avis.index') }}">Avis clients</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="#">Modifier</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>{{ $avi->entreprise_nom }}</span>
    </div>

    <form action="{{ route('admin.avis.update', $avi) }}" method="POST" enctype="multipart/form-data" id="avisForm" novalidate>
        @csrf
        @method('PUT')

        <div class="form-shell">

            {{-- ===== LEFT COLUMN ===== --}}
            <div>

                {{-- Section 1 : Informations client --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-amber">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Identité du client</h3>
                            <p>Entreprise, personne contact et logo</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-row">
                            {{-- Entreprise --}}
                            <div class="field-group">
                                <label class="field-label" for="entreprise_nom">
                                    Nom de l'entreprise
                                    <span class="required-dot"></span>
                                </label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1M5 21h14"/></svg>
                                    </span>
                                    <input type="text" id="entreprise_nom" name="entreprise_nom" class="field-input" placeholder="Ex : Société Générale" required maxlength="150" value="{{ old('entreprise_nom', $avi->entreprise_nom) }}">
                                </div>
                                <div class="field-error-msg" id="entrepriseError">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    Le nom de l'entreprise est requis
                                </div>
                            </div>

                            {{-- Fonction --}}
                            <div class="field-group">
                                <label class="field-label" for="contact_fonction">Fonction</label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </span>
                                    <input type="text" id="contact_fonction" name="contact_fonction" class="field-input" placeholder="Ex : Directeur RH" maxlength="100" value="{{ old('contact_fonction', $avi->contact_fonction) }}">
                                </div>
                            </div>
                        </div>

                        <div class="field-row">
                            {{-- Contact --}}
                            <div class="field-group">
                                <label class="field-label" for="contact_nom">Nom du contact</label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </span>
                                    <input type="text" id="contact_nom" name="contact_nom" class="field-input" placeholder="Ex : Jean Dupont" maxlength="100" value="{{ old('contact_nom', $avi->contact_nom) }}">
                                </div>
                            </div>

                            {{-- Logo --}}
                            <div class="field-group">
                                <label class="field-label">Logo de l'entreprise</label>

                                @if($avi->logo_entreprise)
                                <div class="existing-logo" id="existingLogoBlock">
                                    <img src="{{ asset('storage/'.$avi->logo_entreprise) }}" alt="Logo actuel">
                                    <button type="button" class="clear-logo-btn" id="clearLogoBtn">✕ Supprimer</button>
                                </div>
                                @endif

                                <div class="img-preview-box" id="imgBox">
                                    <input type="file" name="logo_entreprise" id="logoInput" accept="image/*">
                                    <img id="imgPreview" src="" alt="Aperçu">
                                    <div class="img-placeholder" id="imgPlaceholder">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        <span>Cliquer pour modifier</span>
                                        <small>JPG, PNG, WEBP — max 2 Mo</small>
                                    </div>
                                    <span class="img-upload-badge" id="imgBadge">+ Logo</span>
                                </div>
                                <span class="field-hint">Laissez vide pour conserver le logo actuel</span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Section 2 : Avis & notation --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-purple">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Témoignage & note</h3>
                            <p>L'avis du client et sa notation</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        {{-- Note avec étoiles --}}
                        <div class="field-group" style="margin-bottom: 20px;">
                            <label class="field-label">
                                Note / 5
                                <span class="required-dot"></span>
                            </label>
                            <div class="star-rating" id="starRating">
                                <input type="radio" id="star5" name="note" value="5" {{ old('note', $avi->note) == 5 ? 'checked' : '' }}>
                                <label for="star5">★</label>
                                <input type="radio" id="star4" name="note" value="4" {{ old('note', $avi->note) == 4 ? 'checked' : '' }}>
                                <label for="star4">★</label>
                                <input type="radio" id="star3" name="note" value="3" {{ old('note', $avi->note) == 3 ? 'checked' : '' }}>
                                <label for="star3">★</label>
                                <input type="radio" id="star2" name="note" value="2" {{ old('note', $avi->note) == 2 ? 'checked' : '' }}>
                                <label for="star2">★</label>
                                <input type="radio" id="star1" name="note" value="1" {{ old('note', $avi->note) == 1 ? 'checked' : '' }}>
                                <label for="star1">★</label>
                            </div>
                            <div class="field-error-msg" id="noteError">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                Veuillez sélectionner une note
                            </div>
                        </div>

                        {{-- Avis --}}
                        <div class="field-group">
                            <label class="field-label" for="contenu">
                                Avis du client
                                <span class="required-dot"></span>
                            </label>
                            <div class="textarea-wrap" style="position: relative;">
                                <textarea id="contenu" name="contenu" class="field-textarea" rows="4" placeholder="Le témoignage du client…" maxlength="2000" required>{{ old('contenu', $avi->contenu) }}</textarea>
                                <span class="char-count"><span id="contentCount">0</span>/2000</span>
                            </div>
                            <div class="field-error-msg" id="contenuError">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                Le contenu de l'avis est requis
                            </div>
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
                            <h3>Publication & mise en avant</h3>
                            <p>Statut de l'avis et visibilité</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-row">
                            {{-- Statut --}}
                            <div class="field-group">
                                <label class="field-label" for="statut">Statut</label>
                                <select id="statut" name="statut" class="field-select">
                                    <option value="en_attente" {{ old('statut', $avi->statut) == 'en_attente' ? 'selected' : '' }}>⏳ En attente</option>
                                    <option value="publie" {{ old('statut', $avi->statut) == 'publie' ? 'selected' : '' }}>✅ Publié</option>
                                    <option value="rejete" {{ old('statut', $avi->statut) == 'rejete' ? 'selected' : '' }}>❌ Rejeté</option>
                                </select>
                                <div id="statusPreviewBadge" class="status-badge-preview pending">⏳ En attente</div>
                            </div>

                            {{-- Mise en avant --}}
                            <div class="field-group">
                                <label class="field-label">Visibilité</label>
                                <label class="toggle-field" for="misEnAvant">
                                    <div class="toggle-field-text">
                                        <p>Mettre en avant</p>
                                        <span>Apparaît en priorité sur la page</span>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="misEnAvant" name="mis_en_avant" value="1" {{ old('mis_en_avant', $avi->mis_en_avant) ? 'checked' : '' }}>
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

                {{-- Preview Card (simulateur d'affichage) --}}
                <div class="preview-card">
                    <div class="preview-card-header">
                        <h3>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Aperçu visuel
                        </h3>
                        <p style="font-size: 10px; opacity: 0.7;">Ce que verra le client</p>
                    </div>
                    <div class="preview-card-body">
                        <div class="preview-avis-content">
                            <div class="preview-stars" id="previewStars">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <div class="preview-text" id="previewText">Aucun avis saisi pour le moment.</div>
                            <div class="preview-author">
                                <div class="preview-avatar" id="previewAvatar">TH</div>
                                <div class="preview-author-info">
                                    <h4 id="previewCompany">Entreprise</h4>
                                    <p id="previewRole">Client</p>
                                </div>
                            </div>
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

                        <a href="{{ route('admin.avis.index') }}" class="btn-cancel">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Annuler
                        </a>

                    </div>
                </div>

                {{-- Conseils --}}
                <div class="tips-card">
                    <div class="tips-title">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Conseils
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: #f59e0b"></span>
                        Les avis avec une note élevée sont plus visibles
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: #2d9c4f"></span>
                        Mettez en avant les meilleurs témoignages
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: #2563a8"></span>
                        Un logo professionnel renforce la crédibilité
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: #8b5cf6"></span>
                        Les avis récents apparaissent en premier
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
       CHAR COUNTER
    ============================================================ */
    const contenuTextarea = document.getElementById('contenu');
    const contentCount = document.getElementById('contentCount');
    if (contenuTextarea && contentCount) {
        function updateCount() {
            const len = contenuTextarea.value.length;
            contentCount.textContent = len;
            contentCount.style.color = len > 1800 ? (len >= 2000 ? '#ef4444' : 'var(--brand-orange, #e8722a)') : '';
        }
        contenuTextarea.addEventListener('input', updateCount);
        updateCount();
    }

    /* ============================================================
       PREVIEW EN TEMPS RÉEL
    ============================================================ */
    const entrepriseInput = document.getElementById('entreprise_nom');
    const fonctionInput = document.getElementById('contact_fonction');
    const avisTextarea = document.getElementById('contenu');
    const previewCompany = document.getElementById('previewCompany');
    const previewRole = document.getElementById('previewRole');
    const previewText = document.getElementById('previewText');
    const previewAvatar = document.getElementById('previewAvatar');

    function updatePreview() {
        const company = entrepriseInput?.value.trim() || 'Entreprise';
        const fonction = fonctionInput?.value.trim() || 'Client';
        const avis = avisTextarea?.value.trim() || 'Aucun avis saisi pour le moment.';
        
        previewCompany.textContent = company;
        previewRole.textContent = fonction;
        previewText.textContent = avis;
        
        const initials = company.substring(0, 2).toUpperCase();
        previewAvatar.textContent = initials;
    }

    entrepriseInput?.addEventListener('input', updatePreview);
    fonctionInput?.addEventListener('input', updatePreview);
    avisTextarea?.addEventListener('input', updatePreview);
    updatePreview();

    /* ============================================================
       PREVIEW STARS
    ============================================================ */
    const starInputs = document.querySelectorAll('input[name="note"]');
    const previewStars = document.getElementById('previewStars');

    function updateStarsPreview() {
        let selectedValue = 0;
        for (const input of starInputs) {
            if (input.checked) {
                selectedValue = parseInt(input.value);
                break;
            }
        }
        
        previewStars.innerHTML = '';
        for (let i = 1; i <= 5; i++) {
            const star = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            star.setAttribute('fill', i <= selectedValue ? 'currentColor' : 'none');
            star.setAttribute('viewBox', '0 0 20 20');
            star.setAttribute('width', '18');
            star.setAttribute('height', '18');
            star.style.color = i <= selectedValue ? '#f59e0b' : '#cbd5e1';
            star.innerHTML = '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>';
            previewStars.appendChild(star);
        }
    }

    starInputs.forEach(input => {
        input.addEventListener('change', updateStarsPreview);
    });
    updateStarsPreview();

    /* ============================================================
       STATUS BADGE PREVIEW
    ============================================================ */
    const statutSelect = document.getElementById('statut');
    const statusBadge = document.getElementById('statusPreviewBadge');

    function updateStatusBadge() {
        const value = statutSelect?.value;
        if (value === 'publie') {
            statusBadge.className = 'status-badge-preview published';
            statusBadge.innerHTML = '✅ Publié';
        } else if (value === 'rejete') {
            statusBadge.className = 'status-badge-preview rejected';
            statusBadge.innerHTML = '❌ Rejeté';
        } else {
            statusBadge.className = 'status-badge-preview pending';
            statusBadge.innerHTML = '⏳ En attente';
        }
    }

    statutSelect?.addEventListener('change', updateStatusBadge);
    updateStatusBadge();

    /* ============================================================
       FORM STATUS BAR
    ============================================================ */
    const statusBar = document.getElementById('formStatusBar');

    function updateFormStatus() {
        const entreprise = entrepriseInput?.value.trim() || '';
        const contenu = contenuTextarea?.value.trim() || '';
        let noteSelected = false;
        for (const input of starInputs) {
            if (input.checked) { noteSelected = true; break; }
        }

        if (entreprise && contenu && noteSelected) {
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

    entrepriseInput?.addEventListener('input', updateFormStatus);
    contenuTextarea?.addEventListener('input', updateFormStatus);
    starInputs.forEach(input => input.addEventListener('change', updateFormStatus));
    updateFormStatus();

    /* ============================================================
       LOGO UPLOAD
    ============================================================ */
    const logoInput = document.getElementById('logoInput');
    const imgPreview = document.getElementById('imgPreview');
    const imgPlaceholder = document.getElementById('imgPlaceholder');
    const imgBadge = document.getElementById('imgBadge');
    const existingLogoBlock = document.getElementById('existingLogoBlock');
    const clearLogoBtn = document.getElementById('clearLogoBtn');

    const ALLOWED_IMG_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    const MAX_IMG_SIZE = 2 * 1024 * 1024;

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' o';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' Ko';
        return (bytes / (1024 * 1024)).toFixed(2) + ' Mo';
    }

    logoInput?.addEventListener('change', function() {
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

        if (existingLogoBlock) existingLogoBlock.style.display = 'none';

        const reader = new FileReader();
        reader.onload = e => {
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
            imgPlaceholder.style.display = 'none';
            imgBadge.textContent = '✓ Logo modifié';
            imgBadge.style.background = 'var(--brand-green, #2d9c4f)';
            
            // Update preview avatar also
            const initials = entrepriseInput?.value.trim().substring(0, 2).toUpperCase() || 'TH';
            previewAvatar.textContent = initials;
        };
        reader.readAsDataURL(file);
    });

    clearLogoBtn?.addEventListener('click', function() {
        if (confirm('Supprimer le logo actuel ?')) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'delete_logo';
            hiddenInput.value = '1';
            document.getElementById('avisForm').appendChild(hiddenInput);
            
            if (existingLogoBlock) existingLogoBlock.style.display = 'none';
            imgBadge.textContent = 'Logo supprimé';
            imgBadge.style.background = '#ef4444';
        }
    });

    /* ============================================================
       FORM VALIDATION
    ============================================================ */
    const form = document.getElementById('avisForm');
    const submitBtn = document.getElementById('submitBtn');
    const entrepriseError = document.getElementById('entrepriseError');
    const contenuError = document.getElementById('contenuError');
    const noteError = document.getElementById('noteError');

    form?.addEventListener('submit', function(e) {
        let valid = true;

        // Entreprise
        const entreprise = entrepriseInput?.value.trim() || '';
        if (!entreprise) {
            e.preventDefault();
            entrepriseInput?.classList.add('has-error');
            entrepriseError?.classList.add('show');
            valid = false;
        } else {
            entrepriseInput?.classList.remove('has-error');
            entrepriseError?.classList.remove('show');
        }

        // Contenu
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

        // Note
        let noteSelected = false;
        for (const input of starInputs) {
            if (input.checked) { noteSelected = true; break; }
        }
        if (!noteSelected) {
            e.preventDefault();
            noteError?.classList.add('show');
            valid = false;
        } else {
            noteError?.classList.remove('show');
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

    entrepriseInput?.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('has-error');
            entrepriseError?.classList.remove('show');
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