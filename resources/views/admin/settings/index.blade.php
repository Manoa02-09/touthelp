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
            <a href="{{ route('admin.settings.index', ['tab' => 'email']) }}" 
               class="settings-tab {{ $activeTab == 'email' ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Email (SMTP)</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'security']) }}" 
               class="settings-tab {{ $activeTab == 'security' ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Sécurité</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'social']) }}" 
               class="settings-tab {{ $activeTab == 'social' ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Réseaux</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'backup']) }}" 
               class="settings-tab {{ $activeTab == 'backup' ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <span>Sauvegarde</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'legal']) }}" 
               class="settings-tab {{ $activeTab == 'legal' ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Légal</span>
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

        {{-- ONGLET EMAIL (SMTP) --}}
        @if($activeTab == 'email')
        <div class="settings-form-wrapper">
            <form action="{{ route('admin.settings.update.email') }}" method="POST" class="settings-form" id="smtpForm">
                @csrf
                <div class="db-card">
                    <div class="db-card-head">
                        <div>
                            <div class="db-card-title">
                                <div class="db-card-title-dot" style="background:linear-gradient(135deg,#ef4444,#d63384)"></div>
                                Configuration SMTP
                            </div>
                            <div class="db-card-sub">Configuration du serveur d'envoi d'emails</div>
                        </div>
                    </div>
                    <div class="settings-form-body">
                        <div class="settings-info-box">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Utilisez vos identifiants SMTP pour configurer l'envoi d'emails automatiques</span>
                        </div>
                        <div class="settings-row">
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">🖥️ Serveur SMTP</label>
                                    <input type="text" name="smtp_host" value="{{ old('smtp_host', $smtp_host) }}" class="settings-input" placeholder="smtp.gmail.com">
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">🔌 Port</label>
                                    <input type="text" name="smtp_port" value="{{ old('smtp_port', $smtp_port) }}" class="settings-input" placeholder="587">
                                </div>
                            </div>
                        </div>
                        <div class="settings-row">
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">🔐 Chiffrement</label>
                                    <select name="smtp_encryption" class="settings-input">
                                        <option value="tls" {{ $smtp_encryption == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ $smtp_encryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">📮 Email d'envoi</label>
                                    <input type="email" name="smtp_email" value="{{ old('smtp_email', $smtp_email) }}" class="settings-input" placeholder="no-reply@touthelp.com">
                                </div>
                            </div>
                        </div>
                        <div class="settings-field-group">
                            <div class="settings-field">
                                <label class="settings-label">🔒 Mot de passe SMTP</label>
                                <input type="password" name="smtp_password" class="settings-input" placeholder="••••••••">
                                @if($smtp_password_configured)
                                    <p class="settings-hint success">✓ Mot de passe déjà configuré (laisser vide pour le conserver)</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="settings-form-footer" style="justify-content: space-between;">
                        <button type="button" onclick="testSMTP()" class="settings-btn-test" id="testEmailBtn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Tester la connexion
                        </button>
                        <button type="submit" class="settings-btn-save">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Modal test email --}}
        <div id="testEmailModal" class="settings-modal" style="display: none;">
            <div class="settings-modal-content">
                <div class="settings-modal-header">
                    <h3>📧 Tester l'envoi d'email</h3>
                    <button onclick="closeModal()" class="settings-modal-close">&times;</button>
                </div>
                <div class="settings-modal-body">
                    <p style="font-size: 13px; color: var(--text-secondary, #4a5568); margin-bottom: 12px;">Entrez une adresse email pour recevoir un test :</p>
                    <input type="email" id="testEmailInput" class="settings-input" placeholder="test@exemple.com">
                    <div id="testResult" style="margin-top: 15px; font-size: 12px; min-height: 20px;"></div>
                </div>
                <div class="settings-modal-footer">
                    <button onclick="closeModal()" class="settings-btn-secondary">Annuler</button>
                    <button onclick="sendTestEmail()" class="settings-btn-save" id="sendTestBtn">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Envoyer le test
                    </button>
                </div>
            </div>
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
                        <div class="db-card-sub">Modifiez vos informations personnelles</div>
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
                                    <input type="password" name="password" class="settings-input" placeholder="Laisser vide pour ne pas changer">
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">Confirmer</label>
                                    <input type="password" name="password_confirmation" class="settings-input" placeholder="Répétez le mot de passe">
                                </div>
                            </div>
                        </div>
                        <div class="settings-field-group">
                            <div class="settings-field">
                                <label class="settings-label">Mot de passe actuel <span class="required">*</span></label>
                                <input type="password" name="current_password" class="settings-input" placeholder="Votre mot de passe actuel" required>
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
                                <input type="password" name="confirm_password" class="settings-input" placeholder="Confirmez votre mot de passe" style="max-width: 250px; flex: 1; min-width: 200px;" required>
                                <button type="button" onclick="confirmDeleteAccount()" class="settings-btn-danger">Supprimer mon compte</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ONGLET RÉSEAUX SOCIAUX --}}
        @if($activeTab == 'social')
        <div class="settings-form-wrapper">
            <form action="{{ route('admin.settings.update.social') }}" method="POST" class="settings-form">
                @csrf
                <div class="db-card">
                    <div class="db-card-head">
                        <div>
                            <div class="db-card-title">
                                <div class="db-card-title-dot" style="background:linear-gradient(135deg,#2563a8,#1a8fa0)"></div>
                                🌐 Liens des réseaux sociaux
                            </div>
                            <div class="db-card-sub">Liens affichés dans le footer du site</div>
                        </div>
                    </div>
                    <div class="settings-form-body">
                        <div class="settings-info-box">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Laissez vide les réseaux que vous n'utilisez pas</span>
                        </div>
                        <div class="settings-social-grid">
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">📘 Facebook</label>
                                    <input type="url" name="social_facebook" value="{{ old('social_facebook', $social_facebook) }}" class="settings-input" placeholder="https://facebook.com/touthelp">
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">🔗 LinkedIn</label>
                                    <input type="url" name="social_linkedin" value="{{ old('social_linkedin', $social_linkedin) }}" class="settings-input" placeholder="https://linkedin.com/company/touthelp">
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">📸 Instagram</label>
                                    <input type="url" name="social_instagram" value="{{ old('social_instagram', $social_instagram) }}" class="settings-input" placeholder="https://instagram.com/touthelp">
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">𝕏 Twitter / X</label>
                                    <input type="url" name="social_x" value="{{ old('social_x', $social_x) }}" class="settings-input" placeholder="https://x.com/touthelp">
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">🎬 YouTube</label>
                                    <input type="url" name="social_youtube" value="{{ old('social_youtube', $social_youtube) }}" class="settings-input" placeholder="https://youtube.com/@touthelp">
                                </div>
                            </div>
                            <div class="settings-field-group">
                                <div class="settings-field">
                                    <label class="settings-label">🎵 TikTok</label>
                                    <input type="url" name="social_tiktok" value="{{ old('social_tiktok', $social_tiktok ?? '') }}" class="settings-input" placeholder="https://tiktok.com/@touthelp">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-form-footer">
                        <button type="submit" class="settings-btn-save">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        {{-- ONGLET SAUVEGARDE --}}
        @if($activeTab == 'backup')
        <div class="settings-form-wrapper">
            <div class="db-card" style="margin-bottom: 20px;">
                <div class="db-card-head">
                    <div>
                        <div class="db-card-title">
                            <div class="db-card-title-dot" style="background:linear-gradient(135deg,#2d9c4f,#1a8fa0)"></div>
                            💾 Sauvegardes
                        </div>
                        <div class="db-card-sub">Gérez les sauvegardes de votre site</div>
                    </div>
                </div>
                <div class="settings-form-body">
                    <div class="settings-backup-stats">
                        <div class="backup-stat">
                            <span class="stat-icon">📅</span>
                            <div>
                                <div class="stat-label">Dernière sauvegarde</div>
                                <div class="stat-value">Il y a 2 heures</div>
                            </div>
                        </div>
                        <div class="backup-stat">
                            <span class="stat-icon">💾</span>
                            <div>
                                <div class="stat-label">Taille totale</div>
                                <div class="stat-value">156 MB</div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color, #e2e8f0);">
                        <p style="font-size: 13px; margin-bottom: 12px; color: var(--text-secondary, #4a5568);">Les sauvegardes automatiques sont effectuées tous les jours à 2h du matin.</p>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button type="button" onclick="createBackup()" class="settings-btn-save">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Créer une sauvegarde
                            </button>
                            <button type="button" class="settings-btn-test">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Télécharger
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ONGLET LÉGAL --}}
        @if($activeTab == 'legal')
        <div class="settings-form-wrapper">
            <form action="{{ route('admin.settings.update.legal') }}" method="POST" class="settings-form">
                @csrf
                <div class="db-card">
                    <div class="db-card-head">
                        <div>
                            <div class="db-card-title">
                                <div class="db-card-title-dot" style="background:linear-gradient(135deg,#7c3aed,#2563a8)"></div>
                                ⚖️ Contenus légaux
                            </div>
                            <div class="db-card-sub">Mentions légales, confidentialité et CGU</div>
                        </div>
                    </div>
                    <div class="settings-form-body">
                        <div class="settings-field-group">
                            <div class="settings-field">
                                <label class="settings-label">📄 Mentions légales</label>
                                <textarea name="legal_mentions" rows="8" class="settings-input" placeholder="Contenu des mentions légales...">{{ old('legal_mentions', $legal_mentions) }}</textarea>
                            </div>
                        </div>
                        <div class="settings-field-group">
                            <div class="settings-field">
                                <label class="settings-label">🔒 Politique de confidentialité</label>
                                <textarea name="legal_privacy" rows="8" class="settings-input" placeholder="Politique de confidentialité...">{{ old('legal_privacy', $legal_privacy) }}</textarea>
                            </div>
                        </div>
                        <div class="settings-field-group">
                            <div class="settings-field">
                                <label class="settings-label">⚖️ Conditions générales (CGU)</label>
                                <textarea name="legal_terms" rows="8" class="settings-input" placeholder="Conditions générales d'utilisation...">{{ old('legal_terms', $legal_terms) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="settings-form-footer">
                        <button type="submit" class="settings-btn-save">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </form>
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
    font-family: 'Outfit', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: var(--text-primary, #0f1923);
    margin-bottom: 4px;
}
.settings-subtitle {
    font-size: 13px;
    color: var(--text-muted, #94a3b8);
}
.settings-header-stats {
    display: flex;
    gap: 12px;
}
.setting-stat-chip {
    background: var(--bg-surface-2, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    padding: 8px 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.stat-label {
    font-size: 9px;
    font-weight: 700;
    color: var(--text-muted, #94a3b8);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.stat-value {
    font-size: 12px;
    font-weight: 600;
    margin-top: 2px;
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
    padding: 12px 16px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary, #4a5568);
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: all 0.2s ease;
    position: relative;
    white-space: nowrap;
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
    font-size: 12px;
    font-weight: 700;
    color: var(--text-secondary, #4a5568);
    margin-bottom: 8px;
    letter-spacing: 0.3px;
}
.settings-label .required {
    color: #ef4444;
    font-weight: 800;
}
.settings-input, .settings-textarea {
    width: 100%;
    padding: 11px 14px;
    background: var(--bg-surface-2, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    font-size: 13px;
    color: var(--text-primary, #0f1923);
    font-family: 'DM Sans', sans-serif;
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
    font-family: monospace;
    font-size: 12px;
}
.settings-hint {
    font-size: 11px;
    color: var(--text-muted, #94a3b8);
    margin-top: 6px;
    display: block;
}
.settings-hint.success {
    color: #2d9c4f;
    font-weight: 600;
}

/* ── Info box ── */
.settings-info-box {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(37,99,168,0.08);
    border-left: 3px solid #2563a8;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 12px;
    color: #2563a8;
}
.settings-info-box svg {
    flex-shrink: 0;
}

/* ── Social grid ── */
.settings-social-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 18px;
    margin-bottom: 0;
}

/* ── Backup stats ── */
.settings-backup-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.backup-stat {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    background: var(--bg-surface-2, #f8fafc);
    border-radius: 10px;
    border: 1px solid var(--border-color, #e2e8f0);
}
.stat-icon {
    font-size: 24px;
}

/* ── Checkbox ── */
.settings-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
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
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.settings-btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37,99,168,0.3);
}
.settings-btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.settings-btn-test {
    background: var(--bg-surface-2, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    color: var(--text-secondary, #4a5568);
    padding: 9px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}
.settings-btn-test:hover {
    background: #2563a8;
    border-color: #2563a8;
    color: white;
}
.settings-btn-secondary {
    background: var(--bg-surface-2, #f8fafc);
    border: 1px solid var(--border-color, #e2e8f0);
    color: var(--text-secondary, #4a5568);
    padding: 9px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}
.settings-btn-secondary:hover {
    background: var(--bg-surface-2);
    border-color: #2563a8;
    color: #2563a8;
}
.settings-btn-danger {
    background: rgba(239,68,68,0.1);
    border: 1px solid #ef4444;
    color: #ef4444;
    padding: 9px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
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

/* ── Modal ── */
.settings-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    backdrop-filter: blur(4px);
}
.settings-modal-content {
    background: var(--bg-surface, #fff);
    border-radius: 16px;
    width: 90%;
    max-width: 480px;
    box-shadow: var(--shadow-xl);
    animation: slideUp 0.3s ease;
}
@keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
.settings-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
}
.settings-modal-header h3 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary, #0f1923);
}
.settings-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-muted, #94a3b8);
    transition: color 0.2s;
}
.settings-modal-close:hover {
    color: var(--text-primary, #0f1923);
}
.settings-modal-body {
    padding: 20px;
}
.settings-modal-footer {
    padding: 12px 20px;
    border-top: 1px solid var(--border-color, #e2e8f0);
    display: flex;
    justify-content: flex-end;
    gap: 10px;
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
    .settings-social-grid {
        grid-template-columns: 1fr;
    }
    .settings-backup-stats {
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
    .settings-btn-save, .settings-btn-test, .settings-btn-secondary, .settings-btn-danger {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
// Test SMTP modal
function testSMTP() {
    document.getElementById('testEmailModal').style.display = 'flex';
    document.getElementById('testEmailInput').focus();
}
function closeModal() {
    document.getElementById('testEmailModal').style.display = 'none';
    document.getElementById('testResult').innerHTML = '';
    document.getElementById('testEmailInput').value = '';
}
async function sendTestEmail() {
    const email = document.getElementById('testEmailInput').value;
    const resultDiv = document.getElementById('testResult');
    const sendBtn = document.getElementById('sendTestBtn');
    
    if (!email) {
        resultDiv.innerHTML = '<span style="color:#ef4444;">❌ Veuillez entrer une adresse email</span>';
        return;
    }
    
    sendBtn.disabled = true;
    sendBtn.textContent = 'Envoi...';
    resultDiv.innerHTML = '<span style="color:#2563a8;">📤 Envoi en cours...</span>';
    
    try {
        const response = await fetch('{{ route("admin.settings.test.email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ test_email: email })
        });
        const data = await response.json();
        if (data.success) {
            resultDiv.innerHTML = '<span style="color:#2d9c4f;">✅ ' + data.message + '</span>';
            setTimeout(closeModal, 2000);
        } else {
            resultDiv.innerHTML = '<span style="color:#ef4444;">❌ ' + data.message + '</span>';
        }
    } catch (error) {
        resultDiv.innerHTML = '<span style="color:#ef4444;">❌ Erreur de connexion</span>';
    } finally {
        sendBtn.disabled = false;
        sendBtn.textContent = 'Envoyer le test';
    }
}

// Confirmation suppression compte
function confirmDeleteAccount() {
    if (confirm('⚠️ ATTENTION : Cette action est irréversible !\n\nVotre compte administrateur sera définitivement supprimé.\n\nÊtes-vous absolument sûr ?')) {
        document.getElementById('deleteAccountForm').submit();
    }
}

// Créer backup
function createBackup() {
    alert('⏳ Sauvegarde en cours...\n\nCette opération peut prendre quelques minutes.');
}

// Fermer modal en cliquant dehors
document.addEventListener('click', function(e) {
    const modal = document.getElementById('testEmailModal');
    if (e.target === modal) {
        closeModal();
    }
});
</script>

@endsection