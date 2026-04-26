@extends('layouts.admin')

@section('page-title', 'Modifier le catalogue')
@section('page-subtitle', 'Mettre à jour les informations du catalogue de formation')

@section('content')

<style>
/* ============================================================
   CATALOGUE EDIT — Design system harmonisé (identique à create)
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

.form-section:nth-child(1) { animation-delay: 0.05s; }
.form-section:nth-child(2) { animation-delay: 0.10s; }
.form-section:nth-child(3) { animation-delay: 0.15s; }
.form-section:nth-child(4) { animation-delay: 0.20s; }

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
.icon-pink   { background: rgba(214,51,132,0.12); color: var(--brand-pink, #d63384); }
.icon-green  { background: rgba(45,156,79,0.12);  color: var(--brand-green, #2d9c4f); }
.icon-orange { background: rgba(232,114,42,0.12); color: var(--brand-orange, #e8722a); }
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
    min-height: 90px;
    line-height: 1.6;
}

.field-textarea.tall { min-height: 160px; }

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

/* ===== FILE UPLOAD WITH EXISTING FILE ===== */
.file-drop-zone {
    border: 2px dashed var(--border-color, #e2e8f0);
    border-radius: 14px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease;
    background: var(--bg-surface-2, #f8fafc);
    position: relative;
    overflow: hidden;
}

.file-drop-zone:hover,
.file-drop-zone.dragover {
    border-color: var(--brand-blue, #2563a8);
    background: rgba(37,99,168,0.04);
}

.file-drop-zone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}

.file-drop-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(37,99,168,0.1);
    color: var(--brand-blue, #2563a8);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
}

.file-drop-title {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--text-primary, #0f1923);
    margin-bottom: 4px;
}

.file-drop-types {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 10px;
    flex-wrap: wrap;
}

.file-type-chip {
    font-size: 10.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.chip-pdf  { background: rgba(239,68,68,0.1);  color: #ef4444; }
.chip-doc  { background: rgba(37,99,168,0.1);  color: var(--brand-blue, #2563a8); }
.chip-docx { background: rgba(37,99,168,0.12); color: var(--brand-blue-light, #3b82c4); }

/* Existing file info */
.existing-file {
    background: rgba(45,156,79,0.08);
    border: 1.5px solid rgba(45,156,79,0.2);
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

.existing-file-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}

.existing-file-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--brand-green, #2d9c4f);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: white;
}

.existing-file-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--brand-green, #2d9c4f);
    word-break: break-all;
}

.existing-file-link {
    background: rgba(37,99,168,0.1);
    color: var(--brand-blue, #2563a8);
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    flex-shrink: 0;
}

.existing-file-link:hover {
    background: rgba(37,99,168,0.2);
}

.file-selected-state {
    display: none;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: rgba(45,156,79,0.08);
    border: 1.5px solid rgba(45,156,79,0.3);
    border-radius: 12px;
    margin-top: 8px;
}

.file-selected-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: var(--brand-green, #2d9c4f);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: white;
}

.file-selected-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--brand-green, #2d9c4f);
    word-break: break-all;
}

.clear-file-btn {
    background: rgba(239,68,68,0.1);
    color: #ef4444;
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.clear-file-btn:hover {
    background: rgba(239,68,68,0.2);
}

/* Image preview */
.img-upload-wrap { position: relative; width: 100%; }

.img-preview-box {
    width: 100%;
    height: 160px;
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

/* Existing image */
.existing-img {
    position: relative;
    width: 100%;
    height: 160px;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 12px;
}

.existing-img img {
    width: 100%;
    height: 100%;
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

<div class="form-page">

    {{-- Breadcrumb --}}
    <div class="form-breadcrumb">
        <a href="{{ route('admin.catalogues.index') }}">Catalogues</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="#">Modifier</a>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>{{ $catalogue->titre }}</span>
    </div>

    <form action="{{ route('admin.catalogues.update', $catalogue) }}" method="POST" enctype="multipart/form-data" id="catalogueForm" novalidate>
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block font-bold mb-2">Titre</label><input type="text" name="titre" value="{{ old('titre', $catalogue->titre) }}" class="w-full border rounded px-3 py-2" required></div>
            <div><label class="block font-bold mb-2">Ordre</label><input type="number" name="ordre" value="{{ old('ordre', $catalogue->ordre) }}" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block font-bold mb-2">Actif</label><input type="checkbox" name="actif" value="1" {{ $catalogue->actif ? 'checked' : '' }}> Oui</div>

            <!-- NOUVEAU : Champ Image avec aperçu -->
            <div>
                <label class="block font-bold mb-2">Image actuelle</label>
                @if($catalogue->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$catalogue->image) }}" alt="Image" class="w-32 h-32 object-cover rounded border">
                    </div>
                    <p class="text-sm text-gray-500 mb-2">Laissez vide pour conserver cette image.</p>
                @else
                    <p class="text-gray-500 mb-2">Aucune image actuellement.</p>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">Nouvelle image PNG, JPG, JPEG (remplacera l’ancienne).</p>
            </div>

            <div>
                <label class="block font-bold mb-2">Fichier actuel</label>
                @if($catalogue->fichier_pdf)
                    <p><a href="{{ asset('storage/'.$catalogue->fichier_pdf) }}" target="_blank" class="text-blue-600">Voir le fichier</a></p>
                @else
                    <p class="text-gray-500">Aucun fichier</p>
                @endif
                <input type="file" name="fichier_pdf" accept=".pdf,.doc,.docx" class="w-full border rounded px-3 py-2 mt-2">
                <p class="text-sm text-gray-500">Laissez vide pour conserver le fichier actuel.</p>
            </div>

            <div class="col-span-2"><label class="block font-bold mb-2">Description</label><textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $catalogue->description) }}</textarea></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Objectifs</label><textarea name="objectifs" rows="3" class="w-full border rounded px-3 py-2">{{ old('objectifs', $catalogue->objectifs) }}</textarea></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Public visé</label><textarea name="public_vise" rows="3" class="w-full border rounded px-3 py-2">{{ old('public_vise', $catalogue->public_vise) }}</textarea></div>
            <div class="col-span-2"><label class="block font-bold mb-2">Programme (HTML)</label><textarea name="programme" rows="8" class="w-full border rounded px-3 py-2">{{ old('programme', $catalogue->programme) }}</textarea></div>
        </div>

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
                            <p>Identité et configuration du catalogue</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-row">
                            <div class="field-group">
                                <label class="field-label" for="titre">
                                    Titre du catalogue
                                    <span class="required-dot"></span>
                                </label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </span>
                                    <input type="text" id="titre" name="titre" class="field-input" placeholder="Ex : Management FMFP" required maxlength="200" value="{{ old('titre', $catalogue->titre) }}">
                                </div>
                                <div class="field-error-msg" id="titreError">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    Le titre est requis
                                </div>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="ordre">Ordre d'affichage</label>
                                <div class="field-input-wrap">
                                    <span class="input-icon">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                                    </span>
                                    <input type="number" id="ordre" name="ordre" class="field-input" value="{{ old('ordre', $catalogue->ordre ?? 0) }}" min="0" max="999">
                                    <span class="input-suffix">pos.</span>
                                </div>
                                <span class="field-hint">Position dans la liste (0 = premier)</span>
                            </div>
                        </div>

                        {{-- Toggle actif --}}
                        <div class="field-group" style="margin-bottom: 0;">
                            <label class="field-label">Statut de publication</label>
                            <label class="toggle-field" for="actifToggle">
                                <div class="toggle-field-text">
                                    <p>Catalogue actif</p>
                                    <span>Visible sur le site public</span>
                                </div>
                                <div class="toggle-switch">
                                    <input type="checkbox" id="actifToggle" name="actif" value="1" {{ old('actif', $catalogue->actif) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </div>
                            </label>
                        </div>

                    </div>
                </div>

                {{-- Section 2 : Contenu --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-teal">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Contenu du catalogue</h3>
                            <p>Description, objectifs et public cible</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        {{-- Description --}}
                        <div class="field-group" style="margin-bottom:16px;">
                            <label class="field-label" for="description">Description</label>
                            <div class="textarea-wrap">
                                <textarea id="description" name="description" class="field-textarea" rows="3" placeholder="Présentez brièvement ce catalogue de formation…" maxlength="1000">{{ old('description', $catalogue->description) }}</textarea>
                                <span class="char-count"><span id="descCount">0</span>/1000</span>
                            </div>
                        </div>

                        {{-- Objectifs --}}
                        <div class="field-group" style="margin-bottom:16px;">
                            <label class="field-label" for="objectifs">Objectifs pédagogiques</label>
                            <span class="field-hint">Listez les compétences acquises à l'issue de la formation</span>
                            <div class="textarea-wrap" style="margin-top:6px;">
                                <textarea id="objectifs" name="objectifs" class="field-textarea" rows="3" placeholder="— Maîtriser les fondamentaux de…&#10;— Savoir appliquer…" maxlength="2000">{{ old('objectifs', $catalogue->objectifs) }}</textarea>
                                <span class="char-count"><span id="objCount">0</span>/2000</span>
                            </div>
                        </div>

                        {{-- Public visé --}}
                        <div class="field-group" style="margin-bottom:0;">
                            <label class="field-label" for="public_vise">Public visé</label>
                            <div class="textarea-wrap" style="margin-top:6px;">
                                <textarea id="public_vise" name="public_vise" class="field-textarea" rows="2" placeholder="Ex : Responsables RH, Managers, Cadres opérationnels…" maxlength="500">{{ old('public_vise', $catalogue->public_vise) }}</textarea>
                                <span class="char-count"><span id="pubCount">0</span>/500</span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Section 3 : Programme --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-pink">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Programme détaillé</h3>
                            <p>Contenu complet — HTML accepté pour la mise en forme</p>
                        </div>
                    </div>
                    <div class="form-section-body">
                        <div class="field-group">
                            <div class="textarea-wrap">
                                <textarea id="programme" name="programme" class="field-textarea tall" rows="10" placeholder="Décrivez le programme jour par jour, module par module…">{{ old('programme', $catalogue->programme) }}</textarea>
                                <span class="char-count"><span id="progCount">0</span>/∞</span>
                            </div>
                            <span class="field-hint" style="margin-top:4px;">Balises HTML autorisées : &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;h3&gt;, &lt;h4&gt;, &lt;br&gt;</span>
                        </div>
                    </div>
                </div>

                {{-- Section 4 : Fichiers & Médias --}}
                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-icon icon-orange">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </div>
                        <div class="form-section-head-text">
                            <h3>Fichiers & Médias</h3>
                            <p>Syllabus téléchargeable et image de couverture</p>
                        </div>
                    </div>
                    <div class="form-section-body">

                        <div class="field-row">
                            {{-- Fichier PDF/DOC --}}
                            <div class="field-group">
                                <label class="field-label">Syllabus (PDF / DOC / DOCX)</label>

                                {{-- Fichier existant --}}
                                @if($catalogue->fichier_pdf)
                                <div class="existing-file" id="existingFileBlock">
                                    <div class="existing-file-info">
                                        <div class="existing-file-icon">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <div class="existing-file-name">Fichier actuel : {{ basename($catalogue->fichier_pdf) }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/'.$catalogue->fichier_pdf) }}" target="_blank" class="existing-file-link">📄 Télécharger</a>
                                </div>
                                @endif

                                <div class="file-drop-zone" id="fileDropZone">
                                    <input type="file" name="fichier_pdf" id="fichierInput" accept=".pdf,.doc,.docx">
                                    <div class="file-drop-icon">
                                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    </div>
                                    <div class="file-drop-title">Glissez ou cliquez pour remplacer</div>
                                    <div class="file-drop-types">
                                        <span class="file-type-chip chip-pdf">PDF</span>
                                        <span class="file-type-chip chip-doc">DOC</span>
                                        <span class="file-type-chip chip-docx">DOCX</span>
                                    </div>
                                </div>

                                <div class="file-selected-state" id="fileSelectedState">
                                    <div class="file-selected-icon">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="file-selected-name" id="selectedFileName">—</div>
                                    </div>
                                    <button type="button" class="clear-file-btn" id="clearFileBtn">Annuler</button>
                                </div>
                                <span class="field-hint">Laissez vide pour conserver le fichier actuel. Taille max 10 Mo.</span>
                            </div>

                            {{-- Image de couverture --}}
                            <div class="field-group">
                                <label class="field-label">Image de couverture</label>

                                @if($catalogue->image)
                                <div class="existing-img" id="existingImgBlock">
                                    <img src="{{ asset('storage/'.$catalogue->image) }}" alt="Image actuelle">
                                    <span class="existing-img-badge" id="clearImgBtn" style="cursor:pointer; background:#ef4444;">✕ Supprimer</span>
                                </div>
                                @endif

                                <div class="img-preview-box" id="imgBox">
                                    <input type="file" name="image" id="imageInput" accept="image/*">
                                    <img id="imgPreview" src="" alt="Aperçu" style="display: none;">
                                    <div class="img-placeholder" id="imgPlaceholder">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        <span>Cliquer pour modifier</span>
                                        <span style="font-size:10.5px;">JPG, PNG, WEBP — max 5 Mo</span>
                                    </div>
                                    <span class="img-upload-badge" id="imgBadge">+ Image</span>
                                </div>
                                <span class="field-hint">Laissez vide pour conserver l'image actuelle.</span>
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

                        <a href="{{ route('admin.catalogues.index') }}" class="btn-cancel">
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
                        Un titre clair et accrocheur améliore le taux de consultation.
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-green, #2d9c4f)"></span>
                        Les objectifs rédigés en verbes d'action sont plus percutants.
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-orange, #e8722a)"></span>
                        Une image de couverture professionnelle augmente l'engagement.
                    </div>
                    <div class="tip-item">
                        <span class="tip-dot" style="background: var(--brand-pink, #d63384)"></span>
                        Uploadez le syllabus en DOCX pour permettre le téléchargement.
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
    function setupCharCounter(textareaId, counterId, max) {
        const ta = document.getElementById(textareaId);
        const ct = document.getElementById(counterId);
        if (!ta || !ct) return;
        function update() {
            const len = ta.value.length;
            ct.textContent = len;
            if (max && len > max * 0.9) {
                ct.style.color = len >= max ? '#ef4444' : 'var(--brand-orange, #e8722a)';
            } else {
                ct.style.color = '';
            }
        }
        ta.addEventListener('input', update);
        update();
    }

    setupCharCounter('description',  'descCount', 1000);
    setupCharCounter('objectifs',    'objCount',  2000);
    setupCharCounter('public_vise',  'pubCount',  500);
    setupCharCounter('programme',    'progCount', null);

    /* ============================================================
       FORM STATUS BAR
    ============================================================ */
    const statusBar  = document.getElementById('formStatusBar');
    const titreInput = document.getElementById('titre');

    function updateStatus() {
        const titre = titreInput?.value.trim() || '';
        if (titre) {
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

    titreInput?.addEventListener('input', updateStatus);
    updateStatus();

    /* ============================================================
       FILE UPLOAD — Syllabus
    ============================================================ */
    const fichierInput    = document.getElementById('fichierInput');
    const fileDropZone    = document.getElementById('fileDropZone');
    const fileSelectedSt  = document.getElementById('fileSelectedState');
    const selectedName    = document.getElementById('selectedFileName');
    const clearFileBtn    = document.getElementById('clearFileBtn');
    const existingFileBlock = document.getElementById('existingFileBlock');

    const ALLOWED_FILE_TYPES = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    const MAX_FILE_SIZE = 10 * 1024 * 1024;

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' o';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' Ko';
        return (bytes / (1024 * 1024)).toFixed(2) + ' Mo';
    }

    function validateFile(file) {
        if (!ALLOWED_FILE_TYPES.includes(file.type)) {
            alert('Type de fichier non autorisé. Acceptés : PDF, DOC, DOCX.');
            return false;
        }
        if (file.size > MAX_FILE_SIZE) {
            alert(`Fichier trop volumineux. Taille max : 10 Mo (${formatBytes(file.size)}).`);
            return false;
        }
        return true;
    }

    fichierInput?.addEventListener('change', function() {
        const file = this.files?.[0];
        if (!file) return;
        if (!validateFile(file)) { this.value = ''; return; }

        // Masquer le bloc fichier existant
        if (existingFileBlock) existingFileBlock.style.display = 'none';
        
        selectedName.textContent = file.name.length > 40 ? file.name.substring(0, 38) + '…' : file.name;
        fileSelectedSt.style.display = 'flex';
    });

    clearFileBtn?.addEventListener('click', function() {
        fichierInput.value = '';
        fileSelectedSt.style.display = 'none';
        if (existingFileBlock) existingFileBlock.style.display = 'flex';
    });

    /* ============================================================
       IMAGE UPLOAD — Preview
    ============================================================ */
    const imageInput    = document.getElementById('imageInput');
    const imgPreview    = document.getElementById('imgPreview');
    const imgPlaceholder = document.getElementById('imgPlaceholder');
    const imgBadge      = document.getElementById('imgBadge');
    const existingImgBlock = document.getElementById('existingImgBlock');
    const clearImgBtn   = document.getElementById('clearImgBtn');

    const ALLOWED_IMG_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    const MAX_IMG_SIZE = 5 * 1024 * 1024;

    imageInput?.addEventListener('change', function() {
        const file = this.files?.[0];
        if (!file) return;

        if (!ALLOWED_IMG_TYPES.includes(file.type)) {
            alert('Image non supportée. Formats acceptés : JPG, PNG, WEBP, GIF.');
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
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
            imgPlaceholder.style.display = 'none';
            imgBadge.textContent = '✓ Image modifiée';
            imgBadge.style.background = 'var(--brand-green, #2d9c4f)';
        };
        reader.readAsDataURL(file);
    });

    // Suppression de l'image existante
    clearImgBtn?.addEventListener('click', function() {
        if (confirm('Supprimer l\'image actuelle ?')) {
            // Créer un champ caché pour indiquer la suppression
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'delete_image';
            hiddenInput.value = '1';
            document.getElementById('catalogueForm').appendChild(hiddenInput);
            
            if (existingImgBlock) existingImgBlock.style.display = 'none';
            imgBadge.textContent = 'Image supprimée';
            imgBadge.style.background = '#ef4444';
        }
    });

    /* ============================================================
       FORM VALIDATION
    ============================================================ */
    const form       = document.getElementById('catalogueForm');
    const submitBtn  = document.getElementById('submitBtn');
    const titreErr   = document.getElementById('titreError');

    form?.addEventListener('submit', function(e) {
        let valid = true;

        const titre = titreInput?.value.trim() || '';
        if (!titre) {
            e.preventDefault();
            titreInput?.classList.add('has-error');
            titreErr?.classList.add('show');
            titreInput?.focus();
            valid = false;
        } else {
            titreInput?.classList.remove('has-error');
            titreErr?.classList.remove('show');
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
            titreErr?.classList.remove('show');
        }
    });

    const spinStyle = document.createElement('style');
    spinStyle.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(spinStyle);

})();
</script>

@endsection