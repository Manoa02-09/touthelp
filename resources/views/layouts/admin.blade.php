<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tout Help</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* (tout ton style existant, inchangé) */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F4F7FE;
            color: #1B2559;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 260px;
            background: #fff;
            border-right: 1px solid #EEF0F7;
            display: flex;
            flex-direction: column;
            transition: left 0.3s ease;
            z-index: 40;
        }

        .sidebar-brand {
            padding: 28px 24px 20px;
            border-bottom: 1px solid #EEF0F7;
        }

        .sidebar-brand h2 {
            font-size: 18px;
            font-weight: 700;
            color: #1B2559;
            letter-spacing: -0.3px;
        }

        .sidebar-brand span {
            color: #4318FF;
        }

        .sidebar-brand p {
            font-size: 12px;
            color: #A3AED0;
            margin-top: 4px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            color: #A3AED0;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 8px 12px 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 12px;
            color: #A3AED0;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: #F4F7FE;
            color: #1B2559;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #4318FF 0%, #9F7AEA 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(67, 24, 255, 0.3);
        }

        .sidebar-link.active svg {
            color: #fff;
        }

        .sidebar-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .badge-dot {
            margin-left: auto;
            background: #4318FF;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-link.active .badge-dot {
            background: rgba(255,255,255,0.3);
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid #EEF0F7;
        }

        .sidebar-promo {
            background: linear-gradient(135deg, #4318FF 0%, #9F7AEA 100%);
            border-radius: 16px;
            padding: 16px;
            margin: 0 12px 16px;
            text-align: center;
            color: #fff;
        }

        .sidebar-promo .promo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        .sidebar-promo h4 {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .sidebar-promo p {
            font-size: 11px;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .sidebar-promo a {
            display: inline-block;
            background: #fff;
            color: #4318FF;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 18px;
            border-radius: 20px;
            text-decoration: none;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        .top-nav {
            background: #fff;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #EEF0F7;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .top-nav-left h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1B2559;
        }

        .top-nav-left p {
            font-size: 13px;
            color: #A3AED0;
        }

        .top-nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #F4F7FE;
            border-radius: 50px;
            padding: 8px 16px;
            gap: 8px;
            border: 1.5px solid transparent;
            transition: border-color 0.2s;
        }

        .search-bar:focus-within {
            border-color: #4318FF;
        }

        .search-bar input {
            border: none;
            background: transparent;
            font-size: 13px;
            color: #1B2559;
            outline: none;
            width: 200px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .search-bar input::placeholder {
            color: #A3AED0;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #F4F7FE;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #A3AED0;
            transition: all 0.2s;
            position: relative;
        }

        .icon-btn:hover {
            background: #EEF0F7;
            color: #1B2559;
        }

        .icon-btn .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #FF5733;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4318FF, #9F7AEA);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            flex-shrink: 0;
        }

        .page-body {
            padding: 28px 32px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #fff;
            border-radius: 20px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid #EEF0F7;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(67, 24, 255, 0.08);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.purple { background: #F4F1FF; color: #4318FF; }
        .stat-icon.teal   { background: #E1F5EE; color: #0F6E56; }
        .stat-icon.coral  { background: #FAECE7; color: #D85A30; }
        .stat-icon.amber  { background: #FAEEDA; color: #BA7517; }

        .stat-info p {
            font-size: 12px;
            color: #A3AED0;
            font-weight: 500;
        }

        .stat-info h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1B2559;
            margin: 2px 0;
        }

        .stat-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .stat-badge.up   { background: #E1F5EE; color: #0F6E56; }
        .stat-badge.down { background: #FAECE7; color: #D85A30; }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #EEF0F7;
            padding: 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1B2559;
        }

        .card-subtitle {
            font-size: 12px;
            color: #A3AED0;
            margin-top: 2px;
        }

        .card-action {
            font-size: 12px;
            font-weight: 600;
            color: #4318FF;
            text-decoration: none;
            background: #F4F1FF;
            padding: 6px 14px;
            border-radius: 20px;
            transition: background 0.2s;
        }

        .card-action:hover { background: #e8e2ff; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            font-size: 11px;
            font-weight: 600;
            color: #A3AED0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 12px 12px 0;
            text-align: left;
            border-bottom: 1px solid #EEF0F7;
        }

        .data-table td {
            padding: 14px 12px 14px 0;
            font-size: 13px;
            color: #1B2559;
            border-bottom: 1px solid #EEF0F7;
            vertical-align: middle;
        }

        .data-table tr:last-child td { border-bottom: none; }

        .table-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .table-name-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .status-badge.active   { background: #E1F5EE; color: #0F6E56; }
        .status-badge.pending  { background: #FAEEDA; color: #BA7517; }
        .status-badge.inactive { background: #F4F1FF; color: #4318FF; }

        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-card {
            background: linear-gradient(135deg, #4318FF 0%, #9F7AEA 100%);
            border-radius: 20px;
            padding: 24px;
            color: #fff;
            text-align: center;
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(255,255,255,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;
            margin: 0 auto 12px;
            border: 3px solid rgba(255,255,255,0.4);
        }

        .profile-card h3 {
            font-size: 16px;
            font-weight: 700;
        }

        .profile-card p {
            font-size: 12px;
            opacity: 0.75;
            margin-top: 2px;
        }

        .profile-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .profile-stats .stat { text-align: center; }
        .profile-stats .stat span { display: block; font-size: 18px; font-weight: 700; }
        .profile-stats .stat label { font-size: 10px; opacity: 0.7; }

        .mini-list-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #EEF0F7;
        }

        .mini-list-item:last-child { border-bottom: none; }

        .mini-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mini-list-item .info p {
            font-size: 13px;
            font-weight: 600;
            color: #1B2559;
        }

        .mini-list-item .info span {
            font-size: 11px;
            color: #A3AED0;
        }

        .mini-list-item .time {
            margin-left: auto;
            font-size: 11px;
            color: #A3AED0;
            white-space: nowrap;
        }

        .progress-bar {
            height: 6px;
            background: #F4F7FE;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(90deg, #4318FF, #9F7AEA);
        }

        .menu-toggle {
            display: none;
        }

        @media (max-width: 1100px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .menu-toggle {
                display: flex;
                position: fixed;
                top: 16px;
                left: 16px;
                z-index: 50;
                width: 40px;
                height: 40px;
                background: #4318FF;
                color: #fff;
                border: none;
                border-radius: 12px;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 14px rgba(67, 24, 255, 0.4);
            }

            .top-nav { padding: 12px 20px 12px 68px; }
            .page-body { padding: 20px; }
            .search-bar input { width: 120px; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

    <!-- Mobile toggle -->
    <button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>Tout<span>Help</span></h2>
            <p>{{ auth()->user()->name }}</p>
        </div>

        <nav class="sidebar-nav">
            <p class="nav-section-label">Menu principal</p>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('admin.catalogues.index') }}" class="sidebar-link {{ request()->routeIs('admin.catalogues.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Catalogues</span>
            </a>

            <a href="{{ route('admin.formations.index') }}" class="sidebar-link {{ request()->routeIs('admin.formations.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Formations</span>
            </a>

            <p class="nav-section-label" style="margin-top:12px;">Contenu</p>

            <a href="{{ route('admin.avis.index') }}" class="sidebar-link {{ request()->routeIs('admin.avis.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span>Avis clients</span>
            </a>

            <a href="{{ route('admin.articles.index') }}" class="sidebar-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span>Blog & réussites</span>
            </a>

            <a href="{{ route('admin.partenaires.index') }}" class="sidebar-link {{ request()->routeIs('admin.partenaires.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Partenaires</span>
            </a>

            <a href="{{ route('admin.messages') }}" class="sidebar-link {{ request()->routeIs('admin.messages') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>Messages</span>
                @php $unread = \App\Models\Message::where('lu', false)->count(); @endphp
                @if($unread > 0)
                    <span class="badge-dot">{{ $unread }}</span>
                @endif
            </a>

            <!-- Lien Paramètres -->
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Paramètres</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link" style="background:none;border:none;width:100%;cursor:pointer;color:#E53E3E;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;color:#E53E3E;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span style="color:#E53E3E;">Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="main-content">

        <!-- Top Nav -->
        <header class="top-nav">
            <div class="top-nav-left">
                <h1>@yield('page-title', 'Tableau de bord')</h1>
                <p>@yield('page-subtitle', 'Bienvenue sur votre espace admin')</p>
            </div>
            <div class="top-nav-right">
                <div class="search-bar">
                    <svg width="15" height="15" fill="none" stroke="#A3AED0" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <button class="icon-btn">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="notif-dot"></span>
                </button>
                <div class="avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="page-body">
            @yield('content')
        </main>
    </div>

    <script>
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    document.querySelector('.sidebar').classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>