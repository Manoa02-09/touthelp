@extends('layouts.admin')

@section('page-title', 'Paramètres')
@section('page-subtitle', 'Gérez la configuration du site et de l\'administration')

@section('content')

@php
    $activeTab = request()->get('tab', 'general');
@endphp

<div class="settings-container">

    {{-- Header ultra amélioré --}}
    <div class="settings-header">
        <div class="settings-header-content">
            <div>
                <h1 class="settings-title">⚙️ Paramètres</h1>
                <p class="settings-subtitle">Configuration globale du site et préférences administrateur</p>
            </div>
            <div class="settings-header-stats">
                <div class="setting-stat-chip">
                    <span class="stat-label">Statut</span>
                    <span class="stat-value" style="color: #2d9c4f;">● En ligne</span>
                </div>
                <div class="setting-stat-chip">
                    <span class="stat-label">Dernière synchro</span>
                    <span class="stat-value">Il y a 5 min</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation améliorée --}}
    <div class="settings-tabs-wrapper">
        <div class="settings-tabs">
            <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" 
               class="settings-tab {{ $activeTab == 'general' ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Général</span>
                <span class="tab-icon-check">✓</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'security']) }}" 
               class="settings-tab {{ $activeTab == 'security' ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Sécurité</span>
            </a>
        </div>
    </div>

    {{-- Contenu des onglets --}}
    <div class="settings-content">
        
        {{-- ONGLET GÉNÉRAL --}}
        @if($activeTab == 'general')
        <div class="settings-form-wrapper">
            <form action="{{ route('admin.settings.update.general') }}" method="POST" class="settings-form">
                @csrf
                <div class="db-card">
                    <div class="db-card-head">
                        <div>
                            <div class="db-card-title">
                                <div class="db-card-title-dot" style="background:linear-gradient(135deg,#2563a8,#3b82c4)"></div>
                                Informations générales
                            </div>
                            <div class="db-card-sub">Informations de base du site et coordonnées</div>
                        </div>
                    </div>
                    <div class="settings-form-body">
                        <div class="settings-field-group">
                            <div class="settings-field">
                                <label class="settings-label">🏢 Nom du site</label>
                                <input type="text" name="site_name" value="{{ old('site_name', $site_name) }}" class="settings-input" placeholder="Tout Help">
                                <p class="settings-hint">Nom affiché dans l'onglet du navigateur et le footer</p>
                            </div>
                        </div>
                        <div class="settings-field-group">
                            <div class="settings-field">
                                <label class="settings-label">📧 Email de contact <span class="required">*</span></label>
                                <input type="email" name="contact_email" value="{{ old('contact_email', $contact_email) }}" class="settings-input" required>
                                <p class="settings-hint">Email public affiché dans le pied de page</p>
                            </div>
                        </div>
                        <div class="settings-row">
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">📱 Téléphone / WhatsApp</label>
                                    <input type="text" name="phone" value="{{ old('phone', $phone) }}" class="settings-input" placeholder="+261 34 00 000 00">
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">📍 Adresse</label>
                                    <input type="text" name="address" value="{{ old('address', $address) }}" class="settings-input" placeholder="Antananarivo, Madagascar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-form-footer">
                        <button type="submit" class="settings-btn-save">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        {{-- ONGLET SÉCURITÉ --}}
        @if($activeTab == 'security')
        <div class="settings-form-wrapper">
            {{-- Profil admin --}}
            <div class="db-card" style="margin-bottom: 20px;">
                <div class="db-card-head">
                    <div>
                        <div class="db-card-title">
                            <div class="db-card-title-dot" style="background:linear-gradient(135deg,#2563a8,#3b82c4)"></div>
                            👤 Mon compte administrateur
                        </div>
                        <div class="db-card-sub">Modifiez vos informations personnelles et sécurisez votre compte</div>
                    </div>
                </div>
                <form action="{{ route('admin.settings.update.profile') }}" method="POST">
                    @csrf
                    <div class="settings-form-body">
                        <div class="settings-row">
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">Nom complet</label>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="settings-input" required>
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="settings-input" required>
                                </div>
                            </div>
                        </div>
                        <div class="settings-row">
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">🔑 Nouveau mot de passe</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" name="password" class="settings-input" placeholder="Laisser vide pour ne pas changer" id="newPassword">
                                        <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">Confirmer le mot de passe</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" name="password_confirmation" class="settings-input" placeholder="Répétez le mot de passe" id="confirmPassword">
                                        <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="settings-field-group">
                            <div class="settings-field">
                                <label class="settings-label">Mot de passe actuel <span class="required">*</span></label>
                                <div class="password-input-wrapper">
                                    <input type="password" name="current_password" class="settings-input" placeholder="Votre mot de passe actuel" id="currentPassword" required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('currentPassword')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-form-footer">
                        <button type="submit" class="settings-btn-save">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>

            {{-- Mode maintenance --}}
            <div class="db-card" style="margin-bottom: 20px;">
                <div class="db-card-head">
                    <div>
                        <div class="db-card-title">
                            <div class="db-card-title-dot" style="background:linear-gradient(135deg,#e8722a,#d63384)"></div>
                            🔧 Mode maintenance
                        </div>
                        <div class="db-card-sub">Activer le mode maintenance pour les visiteurs</div>
                    </div>
                </div>
                <form action="{{ route('admin.settings.update.security') }}" method="POST">
                    @csrf
                    <div class="settings-form-body">
                        <div class="settings-field-group">
                            <label class="settings-checkbox">
                                <input type="checkbox" name="maintenance_mode" value="1" {{ $maintenance_mode ? 'checked' : '' }}>
                                <span>Activer le mode maintenance</span>
                            </label>
                            <p class="settings-hint">Lorsqu'activé, seuls les administrateurs peuvent accéder au site</p>
                        </div>
                        <div class="settings-field-group">
                            <div class="settings-field">
                                <label class="settings-label">Message de maintenance</label>
                                <textarea name="maintenance_message" rows="4" class="settings-input" placeholder="Site en maintenance...">{{ old('maintenance_message', $maintenance_message) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="settings-form-footer">
                        <button type="submit" class="settings-btn-save">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            {{-- Zone dangereuse --}}
            <div class="db-card settings-danger-zone">
                <div class="db-card-head">
                    <div>
                        <div class="db-card-title">
                            <div class="db-card-title-dot" style="background:#ef4444"></div>
                            ⚠️ Zone dangereuse
                        </div>
                        <div class="db-card-sub">Actions irréversibles</div>
                    </div>
                </div>
                <div class="settings-form-body">
                    <div class="settings-field-group">
                        <label class="settings-label" style="color:#ef4444;">🗑️ Supprimer mon compte administrateur</label>
                        <p class="settings-hint" style="color:#ef4444;">Cette action est irréversible et supprimera toutes vos données.</p>
                        <form action="{{ route('admin.settings.account.destroy') }}" method="POST" id="deleteAccountForm">
                            @csrf
                            @method('DELETE')
                            <div style="display: flex; gap: 10px; align-items: center; margin-top: 12px; flex-wrap: wrap;">
                                <div class="password-input-wrapper" style="flex: 1; min-width: 200px;">
                                    <input type="password" name="confirm_password" class="settings-input" placeholder="Confirmez votre mot de passe" id="deletePassword" required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('deletePassword')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <button type="button" onclick="confirmDeleteAccount()" class="settings-btn-danger">Supprimer mon compte</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<style>
/* ── Container ── */
.settings-container {
    max-width: 1100px;
    margin: 0 auto;
}

/* ── Header amélioré ── */
.settings-header {
    margin-bottom: 24px;
}
.settings-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.settings-title {
    font-family: 'Inter', 'Segoe UI', sans-serif;
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary, #0f1923);
    margin-bottom: 4px;
    letter-spacing: -0.5px;
}
.settings-subtitle {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-muted, #94a3b8);
    letter-spacing: 0.3px;
}
.settings-header-stats {
    display: flex;
    gap: 12px;
}
.setting-stat-chip {
    background: var(--bg-surface-2, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    padding: 10px 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.stat-label {
    font-size: 10px;
    font-weight: 700;
    color: var(--text-muted, #94a3b8);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.stat-value {
    font-size: 13px;
    font-weight: 600;
    margin-top: 3px;
    color: var(--text-primary, #0f1923);
}

/* ── Tabs améliorées ── */
.settings-tabs-wrapper {
    background: var(--bg-surface, #fff);
    border-radius: 14px 14px 0 0;
    border: 1px solid var(--border-color, #e2e8f0);
    border-bottom: none;
    padding: 0 12px;
    overflow-x: auto;
}
.settings-tabs {
    display: flex;
    gap: 6px;
    position: relative;
}
.settings-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 18px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary, #4a5568);
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: all 0.2s ease;
    position: relative;
    white-space: nowrap;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
.settings-tab:hover {
    color: #2563a8;
}
.settings-tab.active {
    color: #2563a8;
    border-bottom-color: #2563a8;
}
.settings-tab svg {
    width: 16px;
    height: 16px;
}
.tab-icon-check {
    display: none;
    margin-left: 4px;
    font-size: 12px;
    color: #2d9c4f;
}
.settings-tab.active .tab-icon-check {
    display: inline;
}

/* ── Content ── */
.settings-content {
    background: var(--bg-surface, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-top: none;
    border-radius: 0 0 14px 14px;
    padding: 24px;
    animation: fadeIn 0.3s ease;
}
.settings-form-wrapper {
    animation: fadeUp 0.3s ease;
}
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

/* ── Forms ── */
.settings-form {
    margin: 0;
}
.settings-form-body {
    padding: 20px 0;
}
.settings-form-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 20px;
    border-top: 1px solid var(--border-subtle, #eef2f7);
}
.settings-field-group {
    width: 100%;
}
.settings-field {
    margin-bottom: 0;
}
.settings-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
.settings-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-secondary, #4a5568);
    margin-bottom: 10px;
    letter-spacing: 0.3px;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
.settings-label .required {
    color: #ef4444;
    font-weight: 800;
}
.settings-input, .settings-textarea {
    width: 100%;
    padding: 12px 14px;
    background: var(--bg-surface-2, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    font-size: 14px;
    color: var(--text-primary, #0f1923);
    font-family: 'Inter', 'Segoe UI', sans-serif;
    transition: all 0.2s ease;
}
.settings-input:focus, .settings-textarea:focus {
    outline: none;
    border-color: #2563a8;
    box-shadow: 0 0 0 3px rgba(37,99,168,0.1);
    background: var(--bg-surface, #fff);
}
.settings-textarea {
    resize: vertical;
    font-family: 'Courier New', monospace;
    font-size: 13px;
}
.settings-hint {
    font-size: 12px;
    color: var(--text-muted, #94a3b8);
    margin-top: 6px;
    display: block;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
.settings-hint.success {
    color: #2d9c4f;
    font-weight: 600;
}

/* ── Password Input Wrapper ── */
.password-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}
.password-input-wrapper .settings-input {
    padding-right: 40px;
}
.password-toggle {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-muted, #94a3b8);
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.password-toggle:hover {
    color: #2563a8;
}
.password-toggle svg {
    width: 16px;
    height: 16px;
}

/* ── Checkbox ── */
.settings-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
.settings-checkbox input {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #2563a8;
}

/* ── Buttons ── */
.settings-btn-save {
    background: linear-gradient(135deg, #2563a8, #3b82c4);
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
.settings-btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37,99,168,0.3);
}
.settings-btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.settings-btn-secondary {
    background: var(--bg-surface-2, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    color: var(--text-secondary, #4a5568);
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
.settings-btn-secondary:hover {
    background: #2563a8;
    border-color: #2563a8;
    color: white;
}
.settings-btn-danger {
    background: rgba(239,68,68,0.1);
    border: 1px solid #ef4444;
    color: #ef4444;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    white-space: nowrap;
}
.settings-btn-danger:hover {
    background: #ef4444;
    color: white;
    box-shadow: 0 4px 12px rgba(239,68,68,0.3);
}

/* ── Danger zone ── */
.settings-danger-zone {
    border-color: #ef4444 !important;
    border: 2px solid #ef4444;
}
.settings-danger-zone .db-card-head {
    background: rgba(239,68,68,0.05);
}

/* ── Responsive ── */
@media (max-width: 900px) {
    .settings-header-content {
        flex-direction: column;
    }
    .settings-header-stats {
        margin-top: 12px;
    }
    .settings-row {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 600px) {
    .settings-tabs {
        gap: 4px;
    }
    .settings-tab {
        padding: 10px 12px;
        font-size: 12px;
    }
    .settings-tab span:not(.tab-icon-check) {
        display: none;
    }
    .settings-form-footer {
        flex-direction: column;
    }
    .settings-btn-save, .settings-btn-secondary, .settings-btn-danger {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
// Toggle Password Visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

// Confirmation suppression compte
function confirmDeleteAccount() {
    if (confirm('⚠️ ATTENTION : Cette action est irréversible !\n\nVotre compte administrateur sera définitivement supprimé.\n\nÊtes-vous absolument sûr ?')) {
        document.getElementById('deleteAccountForm').submit();
    }
}
</script>

@endsection