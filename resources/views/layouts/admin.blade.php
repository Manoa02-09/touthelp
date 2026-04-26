<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Admin') — Tout Help</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           DESIGN TOKENS — Palette Tout Help
        ============================================================ */
        :root {
            --brand-blue:       #2563a8;
            --brand-blue-light: #3b82c4;
            --brand-blue-dark:  #1a4a82;
            --brand-pink:       #d63384;
            --brand-orange:     #e8722a;
            --brand-green:      #2d9c4f;
            --brand-teal:       #1a8fa0;

            --bg-base:      #f0f4f8;
            --bg-surface:   #ffffff;
            --bg-surface-2: #f8fafc;
            --bg-elevated:  #ffffff;
            --border-color: #e2e8f0;
            --border-subtle:#eef2f7;

            --text-primary:   #0f1923;
            --text-secondary: #4a5568;
            --text-muted:     #94a3b8;
            --text-inverse:   #ffffff;

            --sidebar-bg:           #0f1923;
            --sidebar-text:         #94a3b8;
            --sidebar-text-hover:   #e2e8f0;
            --sidebar-active-bg:    rgba(37,99,168,0.25);
            --sidebar-active-border:#2563a8;
            --sidebar-active-text:  #60a5fa;
            --sidebar-section:      #475569;
            --sidebar-brand-text:   #f1f5f9;

            --topbar-bg:    #ffffff;
            --topbar-border:#e2e8f0;

            --accent-gradient:  linear-gradient(135deg,#2563a8 0%,#d63384 50%,#e8722a 100%);
            --accent-gradient-2:linear-gradient(135deg,#2563a8,#3b82c4);
            --accent-gradient-3:linear-gradient(135deg,#d63384,#e8722a);

            --shadow-sm: 0 1px 3px rgba(15,25,35,.08);
            --shadow-md: 0 4px 16px rgba(15,25,35,.10);
            --shadow-lg: 0 8px 32px rgba(15,25,35,.12);
            --shadow-xl: 0 16px 48px rgba(15,25,35,.15);

            --sidebar-w: 260px;
            --transition: .22s cubic-bezier(.4,0,.2,1);
        }

        [data-theme="dark"] {
            --bg-base:      #0a0f16;
            --bg-surface:   #111827;
            --bg-surface-2: #1a2332;
            --bg-elevated:  #1e293b;
            --border-color: #1e2d3f;
            --border-subtle:#162030;
            --text-primary:   #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted:     #4a5568;
            --text-inverse:   #0f1923;
            --sidebar-bg:           #080d14;
            --sidebar-text:         #64748b;
            --sidebar-text-hover:   #cbd5e1;
            --sidebar-active-bg:    rgba(37,99,168,.2);
            --sidebar-active-border:#3b82c4;
            --sidebar-active-text:  #93c5fd;
            --sidebar-section:      #334155;
            --sidebar-brand-text:   #e2e8f0;
            --topbar-bg:    #111827;
            --topbar-border:#1e2d3f;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.3);
            --shadow-md: 0 4px 16px rgba(0,0,0,.35);
            --shadow-lg: 0 8px 32px rgba(0,0,0,.4);
        }

        /* ============================================================
           KEYFRAMES
        ============================================================ */
        @keyframes fadeIn       { from{opacity:0} to{opacity:1} }
        @keyframes fadeInDown   { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
        @keyframes slideInLeft  { from{transform:translateX(-100%);opacity:0} to{transform:translateX(0);opacity:1} }
        @keyframes slideInTop   { from{transform:translateY(-20px);opacity:0} to{transform:translateY(0);opacity:1} }
        @keyframes slideInRight { from{opacity:0;transform:translateX(60px) scale(.9)} to{opacity:1;transform:translateX(0) scale(1)} }
        @keyframes pulseBadge   { 0%,100%{transform:scale(1)} 50%{transform:scale(1.2)} }
        @keyframes fadeUp       { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
        @keyframes spin         { to{transform:rotate(360deg)} }

        /* ============================================================
           RESET & BASE
        ============================================================ */
        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        body {
            font-family:'DM Sans',sans-serif;
            background:var(--bg-base);
            color:var(--text-primary);
            transition:background var(--transition),color var(--transition);
            overflow-x:hidden;
        }

        /* ============================================================
           SIDEBAR — TOUJOURS FIXE
        ============================================================ */
        .sidebar {
            position:fixed; 
            left:0; 
            top:0; 
            height:100vh; 
            width:var(--sidebar-w);
            background:var(--sidebar-bg);
            display:flex; 
            flex-direction:column;
            z-index:100;
            transition:transform var(--transition);
            border-right:1px solid rgba(255,255,255,.04);
            overflow:hidden;
        }

        .sidebar-brand {
            padding:24px 20px 20px;
            border-bottom:1px solid rgba(255,255,255,.06);
            flex-shrink:0;
        }
        .sidebar-brand-inner { display:flex; align-items:center; gap:10px; margin-bottom:8px; }
        .sidebar-logo-mark {
            width:36px; height:36px; border-radius:10px;
            background:var(--accent-gradient);
            display:flex; align-items:center; justify-content:center;
            font-family:'Outfit',sans-serif; font-weight:800; font-size:14px; color:white;
            box-shadow:var(--shadow-md); flex-shrink:0;
        }
        .sidebar-brand-name {
            font-family:'Outfit',sans-serif; font-size:17px; font-weight:700;
            color:var(--sidebar-brand-text);
        }
        .sidebar-brand-name span {
            background:var(--accent-gradient);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }
        .sidebar-user-chip {
            display:flex; align-items:center; gap:8px; margin-top:10px;
            padding:8px 10px; background:rgba(255,255,255,.04); border-radius:10px;
            transition:background var(--transition);
        }
        .sidebar-user-chip:hover { background:rgba(255,255,255,.08); }
        .sidebar-user-dot {
            width:7px; height:7px; border-radius:50%; background:#2d9c4f;
            box-shadow:0 0 8px rgba(45,156,79,.7);
            animation:pulseBadge 2s infinite;
            flex-shrink:0;
        }
        .sidebar-user-name {
            font-size:12px; font-weight:500; color:var(--sidebar-text);
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }

        .sidebar-nav { flex:1; padding:16px 12px; overflow-y:auto; }
        .sidebar-nav::-webkit-scrollbar { width:4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background:rgba(255,255,255,.08); border-radius:4px; }

        .nav-section-label {
            font-size:9.5px; font-weight:700; color:var(--sidebar-section);
            letter-spacing:1.2px; text-transform:uppercase;
            padding:14px 10px 8px; display:block;
        }

        .sidebar-link {
            display:flex; align-items:center; gap:11px;
            padding:10px 12px; border-radius:10px;
            color:var(--sidebar-text); text-decoration:none;
            font-size:13.5px; font-weight:500;
            transition:all var(--transition);
            margin-bottom:3px; border:1px solid transparent;
            cursor:pointer; background:none; width:100%; text-align:left;
            position:relative; overflow:hidden;
        }
        .sidebar-link::before {
            content:''; position:absolute; left:0; top:0; bottom:0; width:3px;
            background:var(--sidebar-active-border);
            transform:scaleY(0); transform-origin:center;
            transition:transform var(--transition);
        }
        .sidebar-link svg { width:17px; height:17px; flex-shrink:0; opacity:.7; transition:all var(--transition); }
        .sidebar-link:hover { background:rgba(255,255,255,.06); color:var(--sidebar-text-hover); padding-left:16px; }
        .sidebar-link:hover svg { opacity:1; transform:scale(1.1); }
        .sidebar-link.active { background:var(--sidebar-active-bg); color:var(--sidebar-active-text); border-color:rgba(59,130,196,.3); font-weight:600; padding-left:16px; }
        .sidebar-link.active::before { transform:scaleY(1); }
        .sidebar-link.active svg { color:var(--sidebar-active-text); opacity:1; }

        .nav-badge {
            margin-left:auto;
            background:linear-gradient(135deg,#ef4444,#f87171);
            color:white; font-size:10px; font-weight:700;
            min-width:20px; height:20px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            padding:0 5px;
            animation:pulseBadge 2s infinite;
            box-shadow:0 0 12px rgba(239,68,68,.6);
            flex-shrink:0;
        }

        .sidebar-footer { padding:12px; border-top:1px solid rgba(255,255,255,.06); flex-shrink:0; }
        .sidebar-footer .sidebar-link { color:#f87171; }
        .sidebar-footer .sidebar-link:hover { background:rgba(248,113,113,.08); color:#fca5a5; }

        /* ============================================================
           MAIN & TOPBAR
        ============================================================ */
        .main-content {
            margin-left:var(--sidebar-w);
            min-height:100vh;
            transition:margin-left var(--transition);
            display:flex;
            flex-direction:column;
        }

        .top-nav {
            background:var(--topbar-bg);
            padding:0 28px;
            height:64px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            border-bottom:1px solid var(--topbar-border);
            position:sticky;
            top:0;
            z-index:50;
            box-shadow:var(--shadow-sm);
            flex-shrink:0;
        }
        .top-nav-left { display:flex; align-items:center; gap:16px; flex:1; }
        .top-nav-title { font-family:'Outfit',sans-serif; font-size:18px; font-weight:700; color:var(--text-primary); letter-spacing:-.3px; }
        .top-nav-subtitle { font-size:12px; color:var(--text-muted); margin-top:1px; }
        .top-nav-right { display:flex; align-items:center; gap:12px; }

        /* ============================================================
           SEARCH — Complet avec dropdown
        ============================================================ */
        .search-wrapper { position:relative; }

        .search-bar {
            display:flex; align-items:center;
            background:var(--bg-surface-2); border:1.5px solid var(--border-color);
            border-radius:12px; padding:9px 14px; gap:8px;
            transition:all var(--transition); width:280px;
        }
        .search-bar:focus-within {
            border-color:var(--brand-blue);
            box-shadow:0 0 0 3px rgba(37,99,168,.12);
            background:var(--bg-surface);
        }
        .search-bar svg { width:14px; height:14px; color:var(--text-muted); flex-shrink:0; transition:color var(--transition); }
        .search-bar:focus-within svg { color:var(--brand-blue); }
        .search-bar input {
            border:none; background:transparent; font-size:13px;
            color:var(--text-primary); outline:none; width:100%;
            font-family:'DM Sans',sans-serif;
        }
        .search-bar input::placeholder { color:var(--text-muted); }

        .search-kbd {
            display:flex; align-items:center; gap:2px; flex-shrink:0;
        }
        .search-kbd kbd {
            background:var(--border-color); color:var(--text-muted);
            font-size:10px; font-weight:700; padding:2px 5px;
            border-radius:5px; font-family:'DM Sans',sans-serif; line-height:1.4;
        }

        .search-dropdown {
            position:absolute; top:calc(100% + 8px); left:0; right:0;
            background:var(--bg-surface); border:1.5px solid var(--border-color);
            border-radius:14px; box-shadow:var(--shadow-xl);
            max-height:520px; overflow-y:auto; z-index:2000;
            display:none;
        }
        .search-dropdown.show { display:block; animation:fadeInDown .2s ease; }
        .search-dropdown::-webkit-scrollbar { width:4px; }
        .search-dropdown::-webkit-scrollbar-thumb { background:var(--border-color); border-radius:4px; }

        .search-section { border-bottom:1px solid var(--border-subtle); }
        .search-section:last-child { border-bottom:none; }
        .search-section-label {
            display:flex; align-items:center; gap:6px;
            padding:10px 14px 6px;
            font-size:10px; font-weight:700; text-transform:uppercase;
            letter-spacing:.8px; color:var(--text-muted);
        }
        .search-section-label .sct-icon {
            width:18px; height:18px; border-radius:5px;
            display:flex; align-items:center; justify-content:center;
            font-size:11px;
        }

        .search-item {
            display:flex; align-items:center; gap:10px;
            padding:9px 14px; cursor:pointer;
            text-decoration:none; color:var(--text-primary);
            transition:background .15s;
        }
        .search-item:hover, .search-item.kbd-active { background:var(--bg-surface-2); }
        .search-item.kbd-active { outline:2px solid var(--brand-blue); outline-offset:-2px; border-radius:8px; }

        .search-item-icon {
            width:32px; height:32px; border-radius:9px;
            display:flex; align-items:center; justify-content:center;
            font-size:15px; flex-shrink:0;
        }
        .search-item-body { flex:1; min-width:0; }
        .search-item-title {
            font-size:13px; font-weight:600; color:var(--text-primary);
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .search-item-sub {
            font-size:11px; color:var(--text-muted);
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
            margin-top:1px;
        }
        .search-item-badge {
            font-size:10px; font-weight:600; padding:2px 8px; border-radius:10px;
            white-space:nowrap; flex-shrink:0;
        }
        .search-item-arrow { color:var(--text-muted); opacity:0; transition:opacity .15s; }
        .search-item:hover .search-item-arrow { opacity:1; }

        .search-dropdown mark {
            background:rgba(245,158,11,.25); color:#b45309;
            padding:0 2px; border-radius:3px; font-weight:700;
        }
        [data-theme="dark"] .search-dropdown mark {
            background:rgba(245,158,11,.2); color:#fbbf24;
        }

        .search-empty {
            padding:32px 16px; text-align:center;
            color:var(--text-muted); font-size:13px;
            display:flex; flex-direction:column; align-items:center; gap:8px;
        }
        .search-empty svg { opacity:.4; }

        .search-loading {
            padding:24px 16px; text-align:center;
            display:flex; align-items:center; justify-content:center; gap:8px;
            color:var(--text-muted); font-size:13px;
        }
        .search-loading svg { animation:spin .8s linear infinite; }

        /* ============================================================
           ICON BUTTONS & THEME TOGGLE
        ============================================================ */
        .icon-btn {
            width:38px; height:38px; border-radius:10px;
            background:var(--bg-surface-2); border:1.5px solid var(--border-color);
            display:flex; align-items:center; justify-content:center;
            cursor:pointer; color:var(--text-secondary);
            transition:all var(--transition); position:relative; overflow:hidden;
        }
        .icon-btn:hover {
            background:var(--brand-blue); color:white; border-color:var(--brand-blue);
            transform:translateY(-2px); box-shadow:var(--shadow-md);
        }

        .notif-dot {
            position:absolute; top:7px; right:7px;
            width:8px; height:8px; background:#ef4444;
            border-radius:50%; border:2px solid var(--topbar-bg);
            animation:pulseBadge 2s infinite;
            box-shadow:0 0 10px rgba(239,68,68,.6);
        }

        .theme-toggle {
            width:56px; height:30px; border-radius:15px;
            background:var(--bg-surface-2); border:1.5px solid var(--border-color);
            position:relative; cursor:pointer;
            display:flex; align-items:center; padding:2px;
            transition:all var(--transition);
        }
        .theme-toggle:hover { border-color:var(--brand-blue); }
        .theme-toggle-thumb {
            width:24px; height:24px; border-radius:12px;
            background:var(--bg-surface); box-shadow:var(--shadow-md);
            display:flex; align-items:center; justify-content:center;
            transition:transform var(--transition); font-size:13px;
        }
        [data-theme="dark"] .theme-toggle-thumb { transform:translateX(26px); }

        .user-avatar {
            width:36px; height:36px; border-radius:10px;
            background:var(--accent-gradient);
            display:flex; align-items:center; justify-content:center;
            color:white; font-size:13px; font-weight:700;
            cursor:pointer; font-family:'Outfit',sans-serif;
            transition:all var(--transition); box-shadow:var(--shadow-sm); flex-shrink:0;
        }
        .user-avatar:hover { transform:scale(1.08); box-shadow:var(--shadow-md); }

        /* ============================================================
           PAGE BODY — SCROLLABLE SEULEMENT
        ============================================================ */
        .page-body {
            padding:28px;
            max-width:1600px;
            flex:1;
            overflow-y:auto;
        }

        /* ============================================================
           FLASH MESSAGES
        ============================================================ */
        .flash-container {
            position:fixed; top:76px; right:20px; z-index:300;
            display:flex; flex-direction:column; gap:10px; pointer-events:none;
        }
        .flash-msg {
            display:flex; align-items:center; gap:10px;
            padding:12px 16px; border-radius:12px;
            font-size:13px; font-weight:500;
            box-shadow:var(--shadow-lg); pointer-events:all;
            animation:slideInRight .35s ease; max-width:340px;
            border-left:4px solid; background:var(--bg-surface); color:var(--text-primary);
        }
        .flash-msg.success { border-color:var(--brand-green); }
        .flash-msg.error   { border-color:#ef4444; }
        .flash-msg.warning { border-color:var(--brand-orange); }

        /* ============================================================
           MOBILE
        ============================================================ */
        .menu-toggle {
            display:none; position:fixed; top:14px; left:14px; z-index:200;
            width:38px; height:38px; background:var(--brand-blue); color:white;
            border:none; border-radius:10px; align-items:center; justify-content:center;
            cursor:pointer; box-shadow:var(--shadow-md); transition:all var(--transition);
        }
        .menu-toggle:hover { transform:scale(1.05); }
        .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:99; backdrop-filter:blur(3px); }
        .sidebar-overlay.active { display:block; animation:fadeIn .3s ease; }

        /* ============================================================
           GENERIC UTILITY CLASSES
        ============================================================ */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:18px; margin-bottom:24px; }
        .stat-card  { background:var(--bg-surface); border-radius:16px; padding:20px; display:flex; align-items:center; gap:14px; border:1px solid var(--border-color); transition:all var(--transition); position:relative; overflow:hidden; box-shadow:var(--shadow-sm); animation:fadeUp .4s ease backwards; }
        .stat-card:nth-child(1) { animation-delay:.05s; }
        .stat-card:nth-child(2) { animation-delay:.1s; }
        .stat-card:nth-child(3) { animation-delay:.15s; }
        .stat-card:nth-child(4) { animation-delay:.2s; }
        .stat-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-lg); }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:var(--brand-blue); }
        .stat-card.pink::before   { background:linear-gradient(135deg,#d63384,#e8722a); }
        .stat-card.green::before  { background:linear-gradient(135deg,#2d9c4f,#1a8fa0); }
        .stat-card.orange::before { background:linear-gradient(135deg,#e8722a,#d63384); }

        .stat-icon  { width:50px; height:50px; border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .stat-icon.blue   { background:rgba(37,99,168,.1);  color:var(--brand-blue); }
        .stat-icon.green  { background:rgba(45,156,79,.1);  color:var(--brand-green); }
        .stat-icon.orange { background:rgba(232,114,42,.1); color:var(--brand-orange); }
        .stat-icon.pink   { background:rgba(214,51,132,.1); color:var(--brand-pink); }
        .stat-info p  { font-size:12px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px; }
        .stat-info h3 { font-family:'Outfit',sans-serif; font-size:28px; font-weight:800; color:var(--text-primary); line-height:1; }
        .stat-badge   { font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; display:inline-flex; align-items:center; gap:4px; }
        .stat-badge.up      { background:rgba(45,156,79,.12);  color:#2d9c4f; }
        .stat-badge.down    { background:rgba(232,114,42,.12); color:#e8722a; }
        .stat-badge.pending { background:rgba(239,68,68,.12);  color:#ef4444; }

        .card         { background:var(--bg-surface); border-radius:16px; border:1px solid var(--border-color); padding:22px; transition:all var(--transition); box-shadow:var(--shadow-sm); }
        .card:hover   { border-color:rgba(37,99,168,.3); box-shadow:var(--shadow-md); }
        .card-header  { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
        .card-title   { font-family:'Outfit',sans-serif; font-size:16px; font-weight:700; color:var(--text-primary); }
        .card-subtitle{ font-size:12px; color:var(--text-muted); margin-top:2px; }
        .card-action  { font-size:12px; font-weight:600; color:var(--brand-blue); background:rgba(37,99,168,.08); padding:6px 14px; border-radius:20px; text-decoration:none; transition:all var(--transition); display:inline-flex; align-items:center; gap:4px; }
        .card-action:hover { background:var(--brand-blue); color:white; }

        .content-grid { display:grid; grid-template-columns:1fr 340px; gap:18px; }
        .right-panel  { display:flex; flex-direction:column; gap:18px; }
        @media (max-width:1100px) { .content-grid { grid-template-columns:1fr; } }

        .data-table { width:100%; border-collapse:collapse; }
        .data-table th { font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.6px; padding:14px 12px 14px 0; text-align:left; border-bottom:2px solid var(--border-color); }
        .data-table td { padding:14px 12px 14px 0; font-size:13px; color:var(--text-secondary); border-bottom:1px solid var(--border-subtle); vertical-align:middle; transition:all var(--transition); }
        .data-table tbody tr:hover td { background:var(--bg-surface-2); color:var(--text-primary); }

        .status-badge { display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; white-space:nowrap; }
        .status-badge.active   { background:rgba(45,156,79,.12);  color:#2d9c4f; }
        .status-badge.pending  { background:rgba(232,114,42,.12); color:#e8722a; }
        .status-badge.inactive { background:rgba(148,163,184,.12);color:#94a3b8; }

        .progress-bar  { height:6px; background:var(--border-color); border-radius:10px; overflow:hidden; margin-top:10px; }
        .progress-fill { height:100%; border-radius:10px; background:var(--accent-gradient-2); }

        .btn-primary   { background:linear-gradient(135deg,var(--brand-blue),var(--brand-blue-light)); color:white; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:600; border:none; cursor:pointer; transition:all var(--transition); display:inline-flex; align-items:center; gap:8px; text-decoration:none; box-shadow:var(--shadow-md); }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:var(--shadow-lg); }
        .btn-primary:active { transform:translateY(0); }
        .btn-secondary { background:var(--bg-surface-2); border:1px solid var(--border-color); color:var(--text-secondary); padding:10px 20px; border-radius:10px; font-size:13px; font-weight:500; cursor:pointer; transition:all var(--transition); display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
        .btn-secondary:hover { background:var(--border-color); color:var(--text-primary); }
        .btn-icon { background:var(--bg-surface-2); border:1px solid var(--border-color); border-radius:8px; width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; color:var(--text-muted); transition:all var(--transition); }
        .btn-icon:hover { background:var(--brand-blue); border-color:var(--brand-blue); color:white; transform:translateY(-1px); }

        input,textarea,select { background:var(--bg-surface-2); border:1px solid var(--border-color); border-radius:10px; padding:10px 14px; font-size:13px; color:var(--text-primary); font-family:'DM Sans',sans-serif; transition:all var(--transition); width:100%; }
        input:focus,textarea:focus,select:focus { outline:none; border-color:var(--brand-blue); box-shadow:0 0 0 3px rgba(37,99,168,.1); background:var(--bg-surface); transform:translateY(-1px); }
        label { font-size:12px; font-weight:600; color:var(--text-secondary); margin-bottom:6px; display:block; }

        .mini-list-item { display:flex; align-items:center; gap:12px; padding:12px 0; border-bottom:1px solid var(--border-subtle); transition:all var(--transition); }
        .mini-list-item:last-child { border-bottom:none; }
        .mini-list-item:hover { transform:translateX(4px); }
        .mini-icon { width:38px; height:38px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .mini-list-item .info { flex:1; min-width:0; }
        .mini-list-item .info p { font-size:13px; font-weight:600; color:var(--text-primary); }
        .mini-list-item .info span { font-size:11px; color:var(--text-muted); margin-top:2px; display:block; }
        .mini-list-item .time { font-size:11px; color:var(--text-muted); white-space:nowrap; flex-shrink:0; }

        .profile-card { background:var(--accent-gradient); border-radius:16px; padding:24px; color:white; text-align:center; position:relative; overflow:hidden; }
        .profile-avatar { width:64px; height:64px; border-radius:50%; background:rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; font-family:'Outfit',sans-serif; font-size:22px; font-weight:700; margin:0 auto 12px; border:2px solid rgba(255,255,255,.3); transition:all var(--transition); }
        .profile-card:hover .profile-avatar { transform:scale(1.1); background:rgba(255,255,255,.3); }
        .profile-card h3 { font-family:'Outfit',sans-serif; font-size:16px; font-weight:700; }
        .profile-card p  { font-size:12px; opacity:.8; margin-top:2px; }
        .profile-stats   { display:flex; justify-content:space-around; margin-top:18px; padding-top:14px; border-top:1px solid rgba(255,255,255,.2); }
        .profile-stats .stat { text-align:center; transition:transform var(--transition); }
        .profile-stats .stat:hover { transform:scale(1.1); }
        .profile-stats .stat span  { display:block; font-family:'Outfit',sans-serif; font-size:20px; font-weight:700; }
        .profile-stats .stat label { font-size:10px; opacity:.7; margin-top:3px; }

        @media (max-width:900px) { .search-bar { width:220px; } .search-kbd { display:none; } }
        @media (max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .sidebar.active { transform:translateX(0); }
            .main-content { margin-left:0 !important; }
            .menu-toggle { display:flex; }
            .top-nav { padding:0 16px 0 60px; height:60px; }
            .page-body { padding:16px; }
        }
        @media (max-width:480px) {
            .top-nav-right { gap:8px; }
            .search-wrapper { display:none; }
            .stats-grid { grid-template-columns:1fr; }
        }
    </style>
</head>

<body>

<!-- ── MENU TOGGLE MOBILE ── -->
<button class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ── SIDEBAR ── -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-inner">
            <div class="sidebar-logo-mark">TH</div>
            <span class="sidebar-brand-name">Tout<span>Help</span></span>
        </div>
        <div class="sidebar-user-chip">
            <div class="sidebar-user-dot"></div>
            <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-section-label">Principal</span>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Tableau de bord</span>
        </a>

        <span class="nav-section-label">Gestion Contenu</span>
        <a href="{{ route('admin.catalogues.index') }}" class="sidebar-link {{ request()->routeIs('admin.catalogues.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>Catalogues</span>
        </a>
        <a href="{{ route('admin.formations.index') }}" class="sidebar-link {{ request()->routeIs('admin.formations.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Formations</span>
        </a>
        <a href="{{ route('admin.avis.index') }}" class="sidebar-link {{ request()->routeIs('admin.avis.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <span>Avis clients</span>
        </a>
        <a href="{{ route('admin.articles.index') }}" class="sidebar-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span>Blog & réussites</span>
        </a>
        <a href="{{ route('admin.partenaires.index') }}" class="sidebar-link {{ request()->routeIs('admin.partenaires.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Partenaires</span>
        </a>

<span class="nav-section-label">Communication</span>
@php 
use App\Models\Message;
// Hack: on soustrait 1 pour corriger l'affichage
$msgUnread = max(0, Message::where('repondu', false)->count() - 1);
@endphp
<a href="{{ route('admin.messages') }}" class="sidebar-link {{ request()->routeIs('admin.messages') ? 'active' : '' }}">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
    <span>Messages</span>
    @if($msgUnread > 0)
        <span class="nav-badge">{{ min($msgUnread, 99) }}</span>
    @endif
</a>

        <span class="nav-section-label">Système</span>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>Paramètres</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</aside>

<!-- ── MAIN CONTENT ── -->
<div class="main-content">

    <!-- TOPBAR -->
    <header class="top-nav">
        <div class="top-nav-left">
            <div>
                <div class="top-nav-title">@yield('page-title', 'Tableau de bord')</div>
                <div class="top-nav-subtitle">@yield('page-subtitle', 'Bienvenue')</div>
            </div>
        </div>
        <div class="top-nav-right">

            <!-- SEARCH -->
            <div class="search-wrapper" id="searchWrapper">
                <div class="search-bar">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                    <input type="text" id="searchInput" placeholder="Rechercher… (Ctrl+K)" autocomplete="off" spellcheck="false" maxlength="120">
                    <div class="search-kbd">
                        <kbd>Ctrl</kbd><kbd>K</kbd>
                    </div>
                </div>
                <div class="search-dropdown" id="searchDropdown"></div>
            </div>

            <!-- THEME TOGGLE -->
            <div class="theme-toggle" id="themeToggle" title="Mode sombre / clair">
                <div class="theme-toggle-thumb"><span id="themeIcon">☀️</span></div>
            </div>

            <!-- NOTIFICATIONS -->
            <button class="icon-btn" id="notificationBtn" title="Notifications">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($msgUnread > 0) <span class="notif-dot"></span> @endif
            </button>

            <!-- USER AVATAR -->
            <div class="user-avatar" title="{{ auth()->user()->name }}">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <!-- FLASH MESSAGES -->
    <div class="flash-container" id="flashContainer">
        @if(session('success'))
        <div class="flash-msg success">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flash-msg error">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif
    </div>

    <!-- PAGE CONTENT -->
    <main class="page-body">@yield('content')</main>
</div>

<!-- ============================================================
     SCRIPTS GLOBAUX — RECHERCHE COMPLÈTE AVEC OUVERTURE DIRECTE
============================================================ -->
<script>
(function () {
    'use strict';

    /* ── THEME ────────────────────────────────── */
    const html      = document.documentElement;
    const toggle    = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const KEY       = 'th_admin_theme';

    function applyTheme(t) {
        html.setAttribute('data-theme', t);
        themeIcon.textContent = t === 'dark' ? '🌙' : '☀️';
        localStorage.setItem(KEY, t);
    }

    const saved = localStorage.getItem(KEY);
    if (saved === 'dark' || (!saved && matchMedia('(prefers-color-scheme: dark)').matches)) {
        applyTheme('dark');
    }

    toggle?.addEventListener('click', () => {
        applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    });

    /* ── SIDEBAR MOBILE ───────────────────────── */
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const menuBtn  = document.getElementById('menuToggle');

    const openSidebar  = () => { sidebar.classList.add('active');  overlay.classList.add('active'); };
    const closeSidebar = () => { sidebar.classList.remove('active'); overlay.classList.remove('active'); };

    menuBtn?.addEventListener('click', () => sidebar.classList.contains('active') ? closeSidebar() : openSidebar());
    overlay?.addEventListener('click', closeSidebar);
    document.querySelectorAll('.sidebar-link').forEach(l => l.addEventListener('click', () => { if (innerWidth <= 768) closeSidebar(); }));

    /* ── FLASH AUTO-DISMISS ────────────────────── */
    document.querySelectorAll('.flash-msg').forEach((el, i) => {
        setTimeout(() => {
            el.style.transition = 'opacity .4s, transform .4s';
            el.style.opacity = '0';
            el.style.transform = 'translateX(30px)';
            setTimeout(() => el.remove(), 400);
        }, 4200 + i * 500);
    });

    /* ════════════════════════════════════════════
       RECHERCHE GLOBALE — Moteur complet
    ════════════════════════════════════════════ */
    const searchInput    = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');
    if (!searchInput || !searchDropdown) return;

    let debounceTimer   = null;
    let currentItems    = [];
    let kbdIndex        = -1;
    let lastQuery       = '';

    function esc(str) {
        if (!str) return '';
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    }

    function hl(text, q) {
        if (!q || q.length < 2) return esc(text);
        const safe = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return esc(text).replace(new RegExp(`(${safe})`, 'gi'), '<mark>$1</mark>');
    }

    function closeDropdown() {
        searchDropdown.classList.remove('show');
        kbdIndex = -1;
    }

    function updateKbdSelection() {
        document.querySelectorAll('.search-item').forEach((el, i) => {
            el.classList.toggle('kbd-active', i === kbdIndex);
        });
        const active = document.querySelectorAll('.search-item')[kbdIndex];
        active?.scrollIntoView({ block: 'nearest' });
    }

    function showLoading() {
        searchDropdown.innerHTML = `
            <div class="search-loading">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Recherche en cours…
            </div>`;
        searchDropdown.classList.add('show');
    }

    // Fonction pour ouvrir une conversation directement
    function openConversation(email) {
        if (!email) return;
        window.location.href = `/admin/messages?email=${encodeURIComponent(email)}`;
    }

    function renderResults(data, q) {
        currentItems = [];

        const navLinks = [
            { label:'Tableau de bord',  url:'{{ route("admin.dashboard") }}',           icon:'🏠', tags:'dashboard accueil principal' },
            { label:'Catalogues',       url:'{{ route("admin.catalogues.index") }}',     icon:'📚', tags:'catalogue formation liste' },
            { label:'Ajouter catalogue',url:'{{ route("admin.catalogues.create") }}',    icon:'➕', tags:'nouveau catalogue créer ajouter' },
            { label:'Formations',       url:'{{ route("admin.formations.index") }}',     icon:'📅', tags:'formation session liste' },
            { label:'Ajouter formation',url:'{{ route("admin.formations.create") }}',    icon:'➕', tags:'nouveau formation créer ajouter' },
            { label:'Avis clients',     url:'{{ route("admin.avis.index") }}',           icon:'⭐', tags:'avis note client témoignage' },
            { label:'Blog & Réussites', url:'{{ route("admin.articles.index") }}',       icon:'📝', tags:'article blog réussite actualité' },
            { label:'Partenaires',      url:'{{ route("admin.partenaires.index") }}',    icon:'🤝', tags:'partenaire entreprise client' },
            { label:'Messages',         url:'{{ route("admin.messages") }}',             icon:'💬', tags:'message discussion client chat' },
            { label:'Paramètres',       url:'{{ route("admin.settings.index") }}',       icon:'⚙️', tags:'paramètre configuration option contact email' },
            { label:'Mode sombre',      url:'#', icon:'🌙', tags:'thème sombre dark mode nuit', action: () => { document.getElementById('themeToggle').click(); closeDropdown(); } },
            { label:'Déconnexion',      url:'#', icon:'🚪', tags:'déconnexion logout quitter sortir', action: () => { document.querySelector('.sidebar-footer form').submit(); } },
        ];

        const lq = q.toLowerCase();
        const matchedNav = navLinks.filter(n =>
            n.label.toLowerCase().includes(lq) || n.tags.includes(lq)
        );

        const sections = [
            { key:'catalogues',  label:'Catalogues',    icon:'📚', bg:'rgba(37,99,168,.1)',  color:'#2563a8', type:'standard' },
            { key:'formations',  label:'Formations',    icon:'📅', bg:'rgba(26,143,160,.1)', color:'#1a8fa0', type:'standard' },
            { key:'articles',    label:'Articles / Blog',icon:'📝',bg:'rgba(124,58,237,.1)',  color:'#7c3aed', type:'standard' },
            { key:'partenaires', label:'Partenaires',   icon:'🤝', bg:'rgba(45,156,79,.1)',  color:'#2d9c4f', type:'standard' },
            { key:'avis',        label:'Avis clients',  icon:'⭐', bg:'rgba(232,114,42,.1)', color:'#e8722a', type:'standard' },
            { key:'messages',    label:'Messages',      icon:'💬', bg:'rgba(214,51,132,.1)', color:'#d63384', type:'message' },
        ];

        let html = '';

        // Navigation
        if (matchedNav.length > 0) {
            html += `<div class="search-section">
                <div class="search-section-label">
                    <span class="sct-icon" style="background:rgba(37,99,168,.1)">🧭</span>
                    Navigation & Paramètres
                </div>`;
            for (const n of matchedNav) {
                const idx = currentItems.length;
                currentItems.push({ url: n.url, action: n.action || null, type: 'nav' });
                html += `<div class="search-item" data-idx="${idx}" data-url="${esc(n.url)}" ${n.action ? 'data-action="1"' : ''}>
                    <div class="search-item-icon" style="background:rgba(37,99,168,.08)">${n.icon}</div>
                    <div class="search-item-body">
                        <div class="search-item-title">${hl(n.label, q)}</div>
                    </div>
                    <svg class="search-item-arrow" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>`;
            }
            html += `</div>`;
        }

        // Résultats API
        let hasApiResults = false;
        for (const sec of sections) {
            const items = data?.[sec.key] || [];
            if (!items.length) continue;
            hasApiResults = true;

            html += `<div class="search-section">
                <div class="search-section-label">
                    <span class="sct-icon" style="background:${sec.bg}">${sec.icon}</span>
                    ${sec.label}
                </div>`;

            for (const item of items) {
                const idx = currentItems.length;
                currentItems.push({ 
                    url: item.url, 
                    type: sec.type,
                    email: item.email_client || null,
                    messageId: item.id || null
                });
                
                const badge = item.badge ? `<span class="search-item-badge" style="background:${sec.bg};color:${sec.color}">${esc(item.badge)}</span>` : '';
                
                html += `<div class="search-item" data-idx="${idx}" data-type="${sec.type}" data-email="${esc(item.email_client || '')}" data-url="${esc(item.url)}">
                    <div class="search-item-icon" style="background:${sec.bg}">${sec.icon}</div>
                    <div class="search-item-body">
                        <div class="search-item-title">${hl(item.title, q)}</div>
                        ${item.subtitle ? `<div class="search-item-sub">${hl(item.subtitle, q)}</div>` : ''}
                    </div>
                    ${badge}
                    <svg class="search-item-arrow" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>`;
            }
            html += `</div>`;
        }

        if (!matchedNav.length && !hasApiResults) {
            html = `<div class="search-empty">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Aucun résultat pour <strong>"${esc(q)}"</strong></span>
                <span style="font-size:11px">Essayez un autre terme</span>
            </div>`;
        }

        searchDropdown.innerHTML = html;
        searchDropdown.classList.add('show');
        kbdIndex = -1;

        // Gestion des clics
        searchDropdown.querySelectorAll('.search-item').forEach(el => {
            el.addEventListener('click', function (e) {
                const idx = parseInt(this.dataset.idx);
                const item = currentItems[idx];
                if (!item) return;

                // Actions spéciales (mode sombre, déconnexion)
                if (this.dataset.action === '1' && item.action) {
                    e.preventDefault();
                    item.action();
                    searchInput.value = '';
                    closeDropdown();
                    return;
                }

                // Pour les messages : ouvrir directement la conversation
                if (item.type === 'message' && item.email) {
                    e.preventDefault();
                    openConversation(item.email);
                    searchInput.value = '';
                    closeDropdown();
                    return;
                }

                // Pour les éléments standards
                if (item.url && item.url !== '#') {
                    closeDropdown();
                    searchInput.value = '';
                    // Le lien sera suivi normalement
                }
            });
        });
    }

    async function doSearch(q) {
        if (q.length < 2) { closeDropdown(); return; }
        if (q === lastQuery) return;
        lastQuery = q;

        showLoading();

        try {
            const res = await fetch(`/api/admin/search?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();
            renderResults(data, q);
        } catch (err) {
            console.warn('Search API error:', err);
            renderResults({}, q);
        }
    }

    searchInput.addEventListener('input', function () {
        const q = this.value.trim();
        clearTimeout(debounceTimer);
        if (q.length < 2) { closeDropdown(); lastQuery = ''; return; }
        debounceTimer = setTimeout(() => doSearch(q), 280);
    });

    searchInput.addEventListener('keydown', function (e) {
        const items = searchDropdown.querySelectorAll('.search-item');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            kbdIndex = Math.min(kbdIndex + 1, items.length - 1);
            updateKbdSelection();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            kbdIndex = Math.max(kbdIndex - 1, -1);
            updateKbdSelection();
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (kbdIndex >= 0 && items[kbdIndex]) {
                items[kbdIndex].click();
            }
        } else if (e.key === 'Escape') {
            closeDropdown();
            searchInput.blur();
        }
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('searchWrapper')?.contains(e.target)) {
            closeDropdown();
        }
    });

    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
            if (searchInput.value.trim().length >= 2) doSearch(searchInput.value.trim());
        }
    });

    searchInput.addEventListener('focus', function () {
        const q = this.value.trim();
        if (q.length >= 2) doSearch(q);
    });

})();
</script>
</body>

</html>