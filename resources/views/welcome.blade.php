<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Tout Help - Accueil</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://js.pusher.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.gstatic.com; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; connect-src 'self' https://*.pusher.com; frame-ancestors 'none';">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    <meta http-equiv="Permissions-Policy" content="geolocation=(), microphone=(), camera=()">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400;1,700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <style>
        /* === STYLES EXISTANTS CONSERVÉS === */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        .scroll-mt-header {
            scroll-margin-top: 110px;
        }

        header {
            border-bottom: 4px solid #0a2e5a;
        }

        .nav-link {
            position: relative;
            padding: 10px 0;
            transition: color 0.3s ease;
            font-weight: 800 !important;
            letter-spacing: 0.05em;
            color: #374151;
            text-decoration: none;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #1e3a8a);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: #1e3a8a !important;
        }

        .nav-link.active {
            color: #f97316 !important;
        }

        .nav-link.active::after {
            width: 100%;
            background: linear-gradient(90deg, #f97316, #0a2e5a);
        }

        .btn-primary {
            background: linear-gradient(135deg, #0a2e5a, #1e3a8a);
            color: white;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(10, 46, 90, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(10, 46, 90, 0.35);
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
        }

        .btn-secondary {
            background: rgba(0, 0, 0, 0.05);
            color: #1f2937;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
            gap: 12px;
        }

        /* CHAT MODAL RESPONSIVE */
        .chat-modal {
            display: none;
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 380px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            z-index: 99999;
            overflow: hidden;
            flex-direction: column;
        }

        .chat-modal.active {
            display: flex;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-header {
            background: linear-gradient(135deg, #e63946, #ff6b6b, #f8c291);
            color: white;
            padding: 15px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .chat-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-header-avatar {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .chat-header-name {
            font-weight: 700;
            font-size: 14px;
        }

        .chat-header-status {
            font-size: 11px;
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .chat-status-dot {
            width: 6px;
            height: 6px;
            background: #4ade80;
            border-radius: 50%;
            display: inline-block;
        }

        .chat-close-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .chat-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            background-color: #fef9f9;
            background-image: radial-gradient(circle at 10px 10px, rgba(230, 57, 70, 0.03) 1px, transparent 1px);
            background-size: 20px 20px;
            max-height: 380px;
        }

        .chat-messages-area {
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-height: 100px;
        }

        .chat-input-area {
            background: white;
            border-top: 1px solid #ffe0e0;
            padding: 10px 12px;
            display: flex;
            gap: 8px;
            align-items: flex-end;
            flex-shrink: 0;
        }

        .chat-textarea {
            flex: 1;
            border: 1px solid #ffe0e0;
            border-radius: 20px;
            padding: 9px 14px;
            font-size: 13px;
            resize: none;
            outline: none;
            max-height: 100px;
            line-height: 1.4;
            transition: border-color 0.2s;
        }

        .chat-textarea:focus {
            border-color: #e63946;
        }

        .chat-send-btn {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.15s, opacity 0.15s;
        }

        .chat-send-btn:hover {
            transform: scale(1.05);
        }

        .chat-send-btn:active {
            transform: scale(0.95);
        }

        .chat-send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .chat-init-form {
            padding: 16px;
        }

        .chat-init-form input,
        .chat-init-form textarea {
            width: 100%;
            padding: 9px 12px;
            margin-bottom: 10px;
            border: 1px solid #ffe0e0;
            border-radius: 10px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
            background: white;
            color: #1f2937;
        }

        .chat-init-form input:focus,
        .chat-init-form textarea:focus {
            border-color: #e63946;
        }

        .chat-init-btn {
            width: 100%;
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .chat-init-btn:hover {
            opacity: 0.9;
        }

        .chat-init-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .bubble-sent {
            display: flex;
            justify-content: flex-end;
        }

        .bubble-sent-inner {
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            color: white;
            border-radius: 18px 18px 4px 18px;
            padding: 9px 13px;
            max-width: 75%;
            box-shadow: 0 2px 6px rgba(230, 57, 70, 0.25);
            word-break: break-word;
        }

        .bubble-received {
            display: flex;
            justify-content: flex-start;
            align-items: flex-end;
            gap: 8px;
        }

        .bubble-received-avatar {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
        }

        .bubble-received-inner {
            background: white;
            color: #1f2937;
            border-radius: 18px 18px 18px 4px;
            padding: 9px 13px;
            max-width: 75%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.07);
            word-break: break-word;
        }

        .bubble-text {
            font-size: 13px;
            line-height: 1.45;
        }

        .bubble-time {
            font-size: 10px;
            margin-top: 3px;
            text-align: right;
            opacity: 0.65;
        }

        .bubble-time-left {
            font-size: 10px;
            margin-top: 3px;
            opacity: 0.55;
        }

        .pending-tag {
            text-align: center;
            font-size: 11px;
            color: #e63946;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 4px 12px;
            margin: 6px auto;
            width: fit-content;
            border: 1px solid #ffe0e0;
        }

        .change-identity-btn {
            font-size: 11px;
            color: #e63946;
            background: none;
            border: none;
            cursor: pointer;
            text-align: center;
            width: 100%;
            padding: 6px;
            display: block;
            transition: color 0.15s;
        }

        .change-identity-btn:hover {
            color: #c1121f;
        }

        .robot-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #e63946, #ff6b6b);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(230, 57, 70, 0.4);
            transition: all 0.3s ease;
            z-index: 9999;
        }

        .robot-icon:hover {
            transform: scale(1.1);
        }

        .robot-icon i {
            font-size: 28px;
            color: white;
        }

        .robot-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: bold;
            min-width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 2px solid white;
            animation: badgePulse 0.6s ease-in-out;
        }

        @keyframes badgePulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
                background-color: #ef4444;
            }
        }

        .chat-footer {
            background: white;
            padding: 6px;
            text-align: center;
            font-size: 11px;
            color: #e63946;
            border-top: 1px solid #ffe0e0;
            flex-shrink: 0;
        }

        .chat-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 16px;
            color: #e63946;
            font-size: 12px;
        }

        .chat-loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-message-new {
            animation: messageSlideIn 0.3s ease-out;
        }

        @keyframes robotShake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-3px);
            }

            75% {
                transform: translateX(3px);
            }
        }

        .robot-notification {
            animation: robotShake 0.5s ease-in-out;
        }

        body {
            overflow-x: hidden;
            padding-top: 92px;
            font-family: 'Outfit', sans-serif;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #e63946;
            border-radius: 10px;
        }

        #accueil {
            background-color: #ffffff;
            min-height: calc(100vh - 110px);
            display: flex;
            align-items: center;
        }

        #expertise {
            background-color: #ffffff;
            min-height: calc(100vh - 110px);
            display: flex;
            align-items: center;
        }

        #catalogue {
            background-color: #0a2e5a;
        }

        #partenaires-section {
            background-color: #ffffff !important;
        }

        #avis-section {
            background-color: #0a2e5a;
        }

        .avis-slider-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
        }

        .avis-slider-btn {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            background: transparent;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: transform 0.2s;
        }

        .avis-slider-btn:hover {
            transform: scale(1.1);
        }

        .avis-slider-btn svg {
            width: 36px;
            height: 36px;
        }

        .avis-slider-track-outer {
            overflow: hidden;
            flex: 1;
            max-width: 100%;
        }

        .avis-slider-track {
            display: flex;
            transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
            gap: 20px;
        }

        .avis-card-new {
            flex: 0 0 calc((100% - 40px) / 3);
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(109, 40, 217, 0.08), 0 1px 6px rgba(109, 40, 217, 0.04);
            padding: 1.6rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            border: 1.5px solid #e9e4ff;
            min-width: 0;
        }

        .avis-card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .avis-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            background: linear-gradient(135deg, #7c3aed, #a78bfa);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1rem;
        }

        .avis-author-info {
            flex: 1;
            min-width: 0;
        }

        .avis-author-name-new {
            font-weight: 700;
            color: #1f2937;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .avis-author-role-new {
            font-size: 0.78rem;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .avis-stars-new {
            color: #f59e0b;
            font-size: 0.9rem;
            display: flex;
            gap: 2px;
        }

        .avis-text {
            color: #4b5563;
            font-size: 0.88rem;
            line-height: 1.6;
            font-style: italic;
        }

        .avis-date {
            display: none;
        }

        .catalogue-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .catalogue-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.25);
        }

        .catalogue-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 3px solid #0a2e5a;
        }

        .catalogue-card-body {
            padding: 1.8rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .catalogue-card-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0a2e5a;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .catalogue-card-desc {
            color: #2d3a4b;
            font-size: 0.95rem;
            line-height: 1.5;
            flex: 1;
        }

        .catalogue-card-link {
            margin-top: 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #3b82f6;
            color: white;
            font-weight: 700;
            padding: 0.7rem 1.6rem;
            border-radius: 40px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.25s;
            align-self: flex-start;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .catalogue-card-link:hover {
            background: #2563eb;
            transform: scale(1.02);
            box-shadow: 0 6px 14px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .catalogue-card-link i {
            font-size: 0.8rem;
            transition: transform 0.2s;
        }

        .catalogue-card-link:hover i {
            transform: translateX(4px);
        }

        .section-badge {
            display: inline-block;
            padding: 0.35rem 1.1rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .badge-blue {
            background: #DBEAFE;
            color: #1d4ed8;
        }

        .badge-green {
            background: #D1FAE5;
            color: #065f46;
        }

        .badge-amber {
            background: #FEF3C7;
            color: #92400e;
        }

        .badge-purple {
            background: #EDE9FE;
            color: #5b21b6;
        }

        .badge-orange {
            background: #FEF3C7;
            color: #d97706;
        }

        .badge-teal {
            background: #CCFBF1;
            color: #0f766e;
        }

        .section-title-blue {
            color: #1e40af;
        }

        .section-title-green {
            color: #065f46;
        }

        .section-title-amber {
            color: #92400e;
        }

        .section-title-purple {
            color: #4c1d95;
        }

        .scroll-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border-radius: 50%;
            padding: 0.6rem 0.75rem;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
            opacity: 0;
            transition: opacity 0.2s;
            border: none;
            cursor: pointer;
        }

        .group:hover .scroll-arrow {
            opacity: 1;
        }

        .scroll-arrow.left {
            left: 0;
        }

        .scroll-arrow.right {
            right: 0;
        }

        /* === SECTION À PROPOS (styles existants) === */
        #apropos {
            background: #fff;
            padding: 0;
        }

        .apropos-hero-banner {
            position: relative;
            width: 100%;
            min-height: 340px;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .apropos-bg-img {
            position: absolute;
            inset: 0;
            background-image: url('{{ asset("images/apropos1.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .apropos-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg,
                    rgba(10, 46, 90, 0.95) 0%,
                    rgba(10, 46, 90, 0.85) 40%,
                    rgba(10, 46, 90, 0.5) 70%,
                    rgba(10, 46, 90, 0.1) 100%);
        }

        .apropos-mini-cards {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .mini-card {
            flex: 1;
            min-width: 90px;
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .mini-card:hover {
            background: #0a2e5a;
            color: white;
            transform: translateY(-3px);
        }

        .mini-card i {
            font-size: 18px;
            margin-bottom: 6px;
            color: #0a2e5a;
        }

        .mini-card:hover i {
            color: #fff;
        }

        .mini-card h4 {
            font-size: 12px;
            font-weight: 700;
        }

        .apropos-hero-text {
            position: relative;
            z-index: 2;
            padding: 72px 64px 88px;
            max-width: 580px;
        }

        .apropos-hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 5.5vw, 66px);
            font-weight: 900;
            color: #fff;
            line-height: 1.05;
            margin-bottom: 18px;
            letter-spacing: -0.01em;
        }

        .apropos-gold-bar {
            width: 56px;
            height: 3px;
            background: #C8922A;
            border-radius: 2px;
            margin-bottom: 20px;
        }

        .apropos-hero-sub {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.72);
            font-family: 'Outfit', sans-serif;
        }

        .apropos-main-card {
            background: #fff;
            margin: -36px 40px 0;
            border-radius: 24px;
            box-shadow: 0 8px 48px rgba(10, 46, 90, 0.13), 0 2px 12px rgba(10, 46, 90, 0.07);
            padding: 52px 56px;
            position: relative;
            z-index: 3;
        }

        .apropos-two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 56px;
            align-items: center;
        }

        .apropos-histoire-label {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }

        .apropos-histoire-icon {
            width: 52px;
            height: 52px;
            background: #0a2e5a;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .apropos-histoire-icon i {
            color: #fff;
            font-size: 20px;
        }

        .apropos-histoire-title {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 800;
            color: #0f2439;
        }

        .apropos-histoire-text {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .apropos-histoire-text p {
            font-size: 15px;
            line-height: 1.78;
            color: #4b5563;
            font-weight: 300;
        }

        .apropos-histoire-text strong {
            color: #0a2e5a;
            font-weight: 700;
        }

        .apropos-img-quote {
            position: relative;
        }

        .apropos-img-photo {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 16px;
            display: block;
        }

        .apropos-citation-block {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #0a2e5a 0%, #1a4580 100%);
            border-radius: 0 0 16px 16px;
            padding: 20px 24px 20px;
        }

        .apropos-quote-badge {
            width: 38px;
            height: 38px;
            background: #C8922A;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: -38px auto 14px;
            position: relative;
            z-index: 1;
        }

        .apropos-quote-badge i {
            color: #fff;
            font-size: 14px;
        }

        .apropos-citation-text {
            font-family: 'Playfair Display', serif;
            font-size: 13.5px;
            font-style: italic;
            font-weight: 600;
            color: #fff;
            text-align: center;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .apropos-citation-author {
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            color: #C8922A;
            letter-spacing: 0.1em;
            font-family: 'Outfit', sans-serif;
        }

        .apropos-sep {
            text-align: center;
            color: #d1d5db;
            font-size: 13px;
            letter-spacing: 0.2em;
            padding: 36px 0 12px;
        }

        .apropos-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            padding: 0 40px 64px;
        }

        .apropos-pilier {
            border-radius: 20px;
            padding: 36px 28px;
            text-align: center;
            border: 1px solid #f1f1f1;
            transition: box-shadow 0.3s;
            cursor: default;
        }

        .apropos-pilier:hover {
            box-shadow: 0 12px 36px rgba(10, 46, 90, 0.12);
        }

        .apropos-pilier.valeurs {
            background: #f8f9fa;
        }

        .apropos-pilier.vision {
            background: #fff;
        }

        .apropos-pilier.mission {
            background: #eef2ff;
            border-color: #e0e7ff;
        }

        .apropos-pilier-icon {
            width: 64px;
            height: 64px;
            background: rgba(10, 46, 90, 0.07);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .apropos-pilier-icon i {
            font-size: 24px;
            color: #0a2e5a;
        }

        .apropos-pilier h3 {
            font-family: 'Playfair Display', serif;
            font-size: 19px;
            font-weight: 800;
            color: #0f2439;
            margin-bottom: 12px;
        }

        .apropos-pilier p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.7;
            font-weight: 300;
        }

        /* Partenaires infobulle */
        .partenaire-item[title] {
            position: relative;
        }

        .partenaire-item[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: -32px;
            left: 50%;
            transform: translateX(-50%);
            background: #0a2e5a;
            color: white;
            font-size: 0.7rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            white-space: nowrap;
            z-index: 30;
            pointer-events: none;
            font-weight: normal;
            letter-spacing: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* Styles pour le slider des avis (responsive) */
        .avis-slider {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 2rem 0;
        }

        .avis-slider-viewport {
            overflow: hidden;
            flex: 1;
        }

        .avis-slider-track {
            display: flex;
            transition: transform 0.4s ease;
            gap: 1.5rem;
        }

        .avis-card {
            flex: 0 0 calc((100% - 3rem) / 3);
            background: white;
            border-radius: 1.5rem;
            padding: 1.8rem 1.2rem;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.2s;
        }

        .avis-card .avatar {
            width: 70px;
            height: 70px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1e3a8a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            overflow: hidden;
        }

        .avis-card .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avis-card .name {
            font-weight: 800;
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .avis-card .role {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.75rem;
        }

        .avis-card .stars {
            color: #f59e0b;
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }

        .avis-card .quote {
            font-size: 0.85rem;
            line-height: 1.6;
            color: #4b5563;
            font-style: italic;
        }

        .partenaire-item {
            flex-shrink: 0;
            width: 150px;
            height: 110px;
            background: white;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: help;
            margin-right: 2rem;
        }

        .partenaire-item img {
            max-width: 110px;
            max-height: 75px;
            object-fit: contain;
        }

        .partenaire-item span {
            font-size: 0.8rem;
            font-weight: 700;
            color: #f97316;
            text-align: center;
            padding: 0 0.5rem;
        }

        /* === STYLES POUR LE CARROUSEL BLOG === */
        .blog-slider {
            position: relative;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .blog-slider-viewport {
            overflow: hidden;
            flex: 1;
            border-radius: 2rem;
        }

        .blog-slider-track {
            display: flex;
            transition: transform 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            gap: 1.5rem;
        }

        .blog-card {
            flex: 0 0 calc((100% - 4.5rem) / 4);
            background: white;
            border-radius: 2rem;
            overflow: hidden;
            border: 2.5px solid #0f172a;
            transition: all 0.3s ease;
        }

        .blog-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.15);
        }

        .blog-slider-btn {
            width: 48px;
            height: 48px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            color: #0f172a;
        }

        .blog-slider-btn:hover {
            background: #f97316;
            color: white;
            transform: scale(1.05);
        }

        .blog-slider-btn:active {
            transform: scale(0.95);
        }

        @media (max-width: 768px) {
            .blog-card {
                flex: 0 0 calc(100% - 0rem);
            }
            .blog-slider-track {
                gap: 1rem;
            }
            .blog-slider-btn {
                width: 40px;
                height: 40px;
            }
        }

        /* === NOUVEAUX STYLES RESPONSIVES === */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }

            header {
                top: 28px;
            }

            .scroll-mt-header {
                scroll-margin-top: 80px;
            }

            #accueil,
            #expertise {
                min-height: auto;
                padding: 3rem 0;
            }

            .apropos-hero-text {
                padding: 48px 24px 64px;
                max-width: 100%;
                text-align: center;
            }

            .apropos-hero-title {
                font-size: clamp(32px, 8vw, 48px);
            }

            .apropos-main-card {
                margin: -24px 16px 0;
                padding: 32px 20px;
            }

            .apropos-two-col {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .apropos-cards-grid {
                grid-template-columns: 1fr;
                padding: 0 16px 48px;
            }

            .avis-slider-btn {
                width: 36px;
                height: 36px;
            }

            .avis-slider-btn svg {
                width: 28px;
                height: 28px;
            }

            .avis-card {
                flex: 0 0 calc(100% - 1rem);
                padding: 1.2rem;
            }

            .avis-card .avatar {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .catalogue-card img {
                height: 180px;
            }

            .catalogue-card-body {
                padding: 1.2rem;
            }

            .catalogue-card-title {
                font-size: 1.2rem;
            }

            .partenaire-item {
                width: 110px;
                height: 90px;
                margin-right: 1rem;
            }

            .partenaire-item img {
                max-width: 90px;
                max-height: 65px;
            }

            .chat-modal {
                width: calc(100% - 20px);
                right: 10px;
                left: 10px;
                bottom: 80px;
                border-radius: 16px;
            }

            .chat-body {
                max-height: 50vh;
            }

            .btn-primary,
            .btn-secondary {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 640px) {
            .grid-cols-3 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }

            .text-5xl {
                font-size: 2.5rem;
            }

            .text-6xl {
                font-size: 3rem;
            }

            .text-7xl {
                font-size: 3.5rem;
            }
        }
    </style>
</head>

<body class="bg-white">

    <!-- Bande bleue marine avec email et téléphone -->
    <div class="fixed top-0 left-0 w-full bg-[#0a2e5a] h-7 md:h-8 flex items-center z-60">
        <div class="container mx-auto px-3 md:px-6 flex justify-between items-center w-full">
            <div class="flex items-center gap-2">
                <i class="fas fa-envelope text-yellow-300 text-xs"></i>
                <a href="mailto:info@touthelp.com"
                    class="text-white text-[11px] md:text-xs hover:text-yellow-300 transition font-medium">info@touthelp.com</a>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-phone-alt text-green-300 text-xs"></i>
                <a href="tel:+261384839743"
                    class="text-white text-[11px] md:text-xs hover:text-green-300 transition font-medium">038 48 397 43</a>
            </div>
        </div>
    </div>

    <header class="fixed top-7 md:top-8 left-0 w-full bg-white shadow-md z-50 py-2 md:py-3 border-b-4 border-[#0a2e5a]">
        <div class="container mx-auto px-3 md:px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 md:space-x-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 md:h-12 lg:h-14 w-auto">
                <span class="text-lg md:text-2xl font-bold text-green-900"
                    style="font-family:'Playfair Display',serif;letter-spacing:0.05em;">TOUT HELP</span>
            </div>
            <!-- Menu desktop -->
            <nav class="hidden md:flex space-x-6 lg:space-x-10">
                <a href="#accueil" class="nav-link text-gray-700 text-sm lg:text-base">ACCUEIL</a>
                <a href="#apropos" class="nav-link text-gray-700 text-sm lg:text-base">À PROPOS</a>
                <a href="#expertise" class="nav-link text-gray-700 text-sm lg:text-base">EXPERTISE</a>
                <a href="#catalogue" class="nav-link text-gray-700 text-sm lg:text-base">CATALOGUE</a>
                <a href="#blog" class="nav-link text-gray-700 text-sm lg:text-base">BLOG</a>
                <a href="#contact" class="nav-link text-gray-700 text-sm lg:text-base">CONTACT</a>
            </nav>
            <!-- Bouton burger mobile -->
            <button id="mobileMenuBtn" class="md:hidden text-gray-800 text-2xl focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <!-- Menu mobile déroulant -->
        <nav id="mobileMenu" class="hidden md:hidden absolute top-full left-0 w-full bg-white shadow-lg flex flex-col p-4 space-y-3 border-t border-gray-100 z-50">
            <a href="#accueil" class="nav-link text-gray-700 text-sm">ACCUEIL</a>
            <a href="#apropos" class="nav-link text-gray-700 text-sm">À PROPOS</a>
            <a href="#expertise" class="nav-link text-gray-700 text-sm">EXPERTISE</a>
            <a href="#catalogue" class="nav-link text-gray-700 text-sm">CATALOGUE</a>
            <a href="#blog" class="nav-link text-gray-700 text-sm">BLOG</a>
            <a href="#contact" class="nav-link text-gray-700 text-sm">CONTACT</a>
        </nav>
    </header>

    <!-- SECTION ACCUEIL -->
    <section id="accueil" class="relative bg-white overflow-hidden scroll-mt-header py-12 md:py-0">
        <div class="container mx-auto px-4 md:px-6 flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-12">
            <div class="lg:w-1/2 z-10 text-center lg:text-left">
                <div
                    class="inline-flex items-center space-x-2 bg-gray-100 px-3 py-1 rounded-full text-[10px] md:text-xs font-bold text-gray-500 mb-2 tracking-widest uppercase mx-auto lg:mx-0">
                    <i class="fas fa-shield-alt text-blue-500"></i>
                    <span>Formation • Accompagnement • Audit</span>
                </div>
                <h1
                    class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black text-[#0f2439] leading-[1.1] mb-2">
                    <span style="color: #0a2e5a;">ENSEMBLE<br>FAISONS DE LA</span><br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-300">PERFORMANCE</span><br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-500 to-orange-400">UNE
                        CULTURE</span>
                </h1>
                <p
                    class="text-base sm:text-lg md:text-xl text-gray-700 max-w-xl mx-auto lg:mx-0 mb-4 border-l-4 border-orange-500 pl-4 md:pl-6 leading-relaxed">
                    Expert en formation pluridisciplinaire, accompagnement sur mesure et audit, au service de la
                    structuration et de l'amélioration des organisations.
                </p>
                <div class="flex flex-wrap justify-center lg:justify-start">
                    <a href="#apropos" class="btn-secondary">En savoir plus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="lg:w-1/2 relative mt-10 lg:mt-0 flex justify-center">
                <div class="relative z-10 w-full max-w-md md:max-w-xl lg:max-w-2xl">
                    <img src="{{ asset('images/accueil.png') }}" alt="Expertise Tout Help"
                        class="w-full h-auto object-contain">
                </div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-blue-50 rounded-full filter blur-3xl opacity-50 -z-10">
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION À PROPOS -->
    <section id="apropos" class="scroll-mt-header">
        <div class="apropos-hero-banner"
            style="position: relative; width: 100%; min-height: 450px; border-radius: 0; overflow: hidden;">
            <div class="apropos-bg-img"
                style="position: absolute; inset: 0; background-image: url('{{ asset('images/aproposken.jpg') }}'); background-size: cover; background-position: center 20%; background-repeat: no-repeat;">
            </div>
            <div class="apropos-overlay"
                style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(10,46,90,0.95) 0%, rgba(10,46,90,0.7) 30%, rgba(10,46,90,0.2) 70%, rgba(10,46,90,0) 100%);">
            </div>
            <div class="apropos-hero-text"
                style="position: relative; z-index: 2; margin-left: auto; margin-right: auto; text-align: center; max-width: 800px; padding: 100px 20px;">
                <h2 class="apropos-hero-title"
                    style="text-align: center; font-family: 'Playfair Display', serif; font-size: clamp(44px, 6vw, 78px); font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 20px; letter-spacing: -0.02em;">
                    À PROPOS<br>DE TOUT HELP
                </h2>
                <div class="apropos-gold-bar"
                    style="width: 70px; height: 4px; background: #C8922A; border-radius: 2px; margin-left: auto; margin-right: auto; margin-bottom: 24px;">
                </div>
                <p class="apropos-hero-sub"
                    style="text-align: center; font-size: 14px; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase; color: rgba(255,255,255,0.85);">
                    Votre partenaire de confiance à Madagascar
                </p>
            </div>
        </div>

        <div style="margin-top: -36px; padding-bottom: 64px; width: 100%;">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden"
                style="box-shadow: 0 8px 48px rgba(10,46,90,0.13), 0 2px 12px rgba(10,46,90,0.07); margin: 0;">
                <div class="flex flex-col lg:flex-row gap-6 p-6 md:p-12">
                    <div class="lg:w-1/2">
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-14 h-14 bg-[#0a2e5a] rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-white text-2xl"></i>
                            </div>
                            <span class="text-2xl md:text-3xl font-bold text-[#0f2439]"
                                style="font-family: 'Playfair Display', serif;">Notre histoire</span>
                        </div>
                        <div class="space-y-3 text-gray-600 leading-relaxed text-base md:text-lg">
                            <p>
                                Nous vivons dans un monde en constante évolution, où le succès repose sur la capacité à
                                apprendre, s'adapter et innover.
                                C'est pour répondre à ces défis que <strong class="text-[#0a2e5a]">TOUT-HELP</strong> a
                                été fondée.
                            </p>
                            <p>
                                Notre entreprise s'appuie sur l'expertise de <strong class="text-[#0a2e5a]">20
                                    professionnels</strong> cumulant plus de <strong class="text-[#0a2e5a]">10 ans
                                    d'expérience</strong> dans des secteurs variés.
                            </p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
                            <div style="background:#fff7ed; border:1px solid #fed7aa;" class="p-5 rounded-xl text-center">
                                <i class="fas fa-handshake text-2xl mb-2" style="color:#c2410c;"></i>
                                <h4 class="font-bold text-base" style="color:#9a3412;">Nos valeurs</h4>
                                <p class="text-sm mt-1 leading-relaxed" style="color:#78350f;">
                                    Promouvoir l'entraide et l'innovation dans tous les secteurs, au service de la
                                    réussite individuelle et collective.
                                </p>
                            </div>
                            <div style="background:#ecfdf5; border:1px solid #a7f3d0;" class="p-5 rounded-xl text-center">
                                <i class="fas fa-eye text-2xl mb-2" style="color:#065f46;"></i>
                                <h4 class="font-bold text-base" style="color:#065f46;">Notre vision</h4>
                                <p class="text-sm mt-1 leading-relaxed" style="color:#064e3b;">
                                    Faire de la progression de chaque participant le reflet de notre engagement et de
                                    notre impact durable.
                                </p>
                            </div>
                            <div style="background:#f5f3ff; border:1px solid #c4b5fd;" class="p-5 rounded-xl text-center">
                                <i class="fas fa-rocket text-2xl mb-2" style="color:#6d28d9;"></i>
                                <h4 class="font-bold text-base" style="color:#5b21b6;">Notre mission</h4>
                                <p class="text-sm mt-1 leading-relaxed" style="color:#4c1d95;">
                                    Concevoir des formations pratiques et immédiatement applicables pour permettre à nos
                                    participants de développer leurs compétences et d'apporter une réelle valeur ajoutée
                                    à leur entreprise.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="lg:w-1/2 relative">
                        <img src="{{ asset('images/apropos2.jpg') }}" alt="Notre histoire"
                            class="w-full h-80 object-cover rounded-2xl shadow-md" style="filter: brightness(0.85);">
                        <div
                            class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-[#0a2e5a] to-[#1a4580] rounded-b-2xl p-6">
                            <div
                                class="w-12 h-12 bg-amber-600 rounded-full flex items-center justify-center mx-auto mb-3 -mt-11">
                                <i class="fas fa-quote-right text-white text-base"></i>
                            </div>
                            <p class="text-white text-center text-base italic leading-relaxed font-medium">
                                "Accélérer le développement grâce au potentiel humain et à des solutions adaptées aux
                                défis actuels."
                            </p>
                            <p class="text-amber-400 text-center text-sm font-semibold mt-3 tracking-wide">— Notre
                                objectif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION EXPERTISE -->
    <section id="expertise" class="bg-white scroll-mt-header py-12 md:py-20">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-10 md:mb-16">
                <span class="section-badge badge-orange inline-block mb-3">✨ Notre savoir-faire</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-3 md:mb-4">
                    EXPERTISE <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-yellow-500 via-green-500 via-blue-500 to-purple-500">SUR-MESURE</span>
                </h2>
                <p class="text-gray-500 text-base md:text-lg max-w-2xl mx-auto">L'excellence au service de votre
                    ambition : des stratégies d'élite pour propulser votre croissance</p>
                <div
                    class="w-24 h-1 bg-gradient-to-r from-red-500 via-yellow-500 via-green-500 via-blue-500 to-purple-500 mx-auto mt-4 rounded-full">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Carte 1 -->
                <div
                    class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100 h-full relative flex flex-col group transition-all duration-300 hover:shadow-2xl">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 to-green-500"></div>
                    <div class="p-6 md:p-7 flex flex-col h-full">
                        <div class="text-center">
                            <div
                                class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-teal-50 flex items-center justify-center mx-auto mb-4 md:mb-5 transition-transform group-hover:scale-110">
                                <i class="fas fa-users text-2xl md:text-3xl text-teal-600"></i>
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800">FORMATIONS INTER-ENTREPRISES</h3>
                            <p class="text-xs md:text-sm text-teal-600 font-semibold uppercase mt-1 tracking-widest">
                                SYNERGIE & RÉSEAUX D'ÉLITE</p>
                        </div>
                        <div class="flex-grow mt-4 text-gray-600 text-sm md:text-base leading-relaxed text-center">
                            <p>Intégrez un écosystème d'apprentissage prestigieux où la diversité des regards enrichit
                                votre vision stratégique pour une montée en compétences collective au meilleur coût.</p>
                        </div>
                        <div class="mt-6 text-center"><a href="{{ route('expertise.inter') }}"
                                class="inline-flex items-center gap-2 text-teal-600 font-semibold text-sm hover:gap-4 transition-all duration-300">Découvrir
                                <i class="fas fa-arrow-right text-xs"></i></a></div>
                    </div>
                </div>
                <!-- Carte 2 -->
                <div
                    class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100 h-full relative flex flex-col group transition-all duration-300 hover:shadow-2xl">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 to-amber-500"></div>
                    <div class="p-6 md:p-7 flex flex-col h-full">
                        <div class="text-center">
                            <div
                                class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-orange-50 flex items-center justify-center mx-auto mb-4 md:mb-5 transition-transform group-hover:scale-110">
                                <i class="fas fa-building text-2xl md:text-3xl text-orange-600"></i>
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800">FORMATIONS INTRA-ENTREPRISE</h3>
                            <p class="text-xs md:text-sm text-orange-600 font-semibold uppercase mt-1 tracking-widest">
                                100% PERSONNALISÉ</p>
                        </div>
                        <div class="flex-grow mt-4 text-gray-600 text-sm md:text-base leading-relaxed text-center">
                            <p>Bénéficiez d'une ingénierie de formation exclusive sculptée pour l'ADN de votre
                                organisation garantissant une immersion totale dans vos enjeux et une confidentialité
                                absolue.</p>
                        </div>
                        <div class="mt-6 text-center"><a href="{{ route('expertise.intra') }}"
                                class="inline-flex items-center gap-2 text-orange-600 font-semibold text-sm hover:gap-4 transition-all duration-300">Découvrir
                                <i class="fas fa-arrow-right text-xs"></i></a></div>
                    </div>
                </div>
                <!-- Carte 3 -->
                <div
                    class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100 h-full relative flex flex-col group transition-all duration-300 hover:shadow-2xl">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>
                    <div class="p-6 md:p-7 flex flex-col h-full">
                        <div class="text-center">
                            <div
                                class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-purple-50 flex items-center justify-center mx-auto mb-4 md:mb-5 transition-transform group-hover:scale-110">
                                <i class="fas fa-clipboard-list text-2xl md:text-3xl text-purple-600"></i>
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800">ACCOMPAGNEMENT & AUDIT</h3>
                            <p class="text-xs md:text-sm text-purple-600 font-semibold uppercase mt-1 tracking-widest">
                                STRUCTURATION & PERFORMANCE</p>
                        </div>
                        <div class="flex-grow mt-4 text-gray-600 text-sm md:text-base leading-relaxed text-center">
                            <p>Optimisez vos structures grâce à des diagnostics de précision et un accompagnement vers
                                l'excellence opérationnelle assurant la pérennité de vos résultats et de vos
                                certifications.</p>
                        </div>
                        <div class="mt-6 text-center"><a href="{{ route('expertise.accompagnement') }}"
                                class="inline-flex items-center gap-2 text-purple-600 font-semibold text-sm hover:gap-4 transition-all duration-300">Découvrir
                                <i class="fas fa-arrow-right text-xs"></i></a></div>
                    </div>
                </div>
            </div>

            <div class="mt-12 md:mt-20 rounded-[2rem] shadow-sm py-8 px-4 border border-amber-100"
                style="background: linear-gradient(to right, #fdfbf7, #f5e6d3);">
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-amber-200">
                    <div class="text-center py-4 px-3">
                        <div class="flex justify-center mb-3">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm"><i
                                    class="fas fa-ruler-combined text-amber-700 text-xl"></i></div>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base md:text-lg">Approche sur mesure</h3>
                        <p class="text-gray-600 text-sm mt-1">L'ajustement parfait à votre réalité</p>
                    </div>
                    <div class="text-center py-4 px-3">
                        <div class="flex justify-center mb-3">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm"><i
                                    class="fas fa-chalkboard-user text-amber-700 text-xl"></i></div>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base md:text-lg">Savoir-faire Magistral</h3>
                        <p class="text-gray-600 text-sm mt-1">Des experts de haut rang à votre service</p>
                    </div>
                    <div class="text-center py-4 px-3">
                        <div class="flex justify-center mb-3">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm"><i
                                    class="fas fa-chart-line text-amber-700 text-xl"></i></div>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base md:text-lg">Impact Signature</h3>
                        <p class="text-gray-600 text-sm mt-1">Un impact concret et mesurable</p>
                    </div>
                    <div class="text-center py-4 px-3">
                        <div class="flex justify-center mb-3">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm"><i
                                    class="fas fa-handshake text-amber-700 text-xl"></i></div>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base md:text-lg">Alliance de Confiance</h3>
                        <p class="text-gray-800 text-sm mt-1">Un partenaire stratégique pérenne</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION CATALOGUE -->
    <section id="catalogue" class="py-12 md:py-24 lg:py-32 scroll-mt-header bg-[#0a2e5a]">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-10 md:mb-16">
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 text-white text-xs font-bold uppercase tracking-wider border border-white/30 backdrop-blur-sm"><i
                        class="fas fa-book-open"></i> Notre offre de formation</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-blue-50 mt-5 mb-4">Catalogues de
                    formation</h2>
                <p class="text-blue-100 text-base md:text-lg max-w-2xl mx-auto">Découvrez nos formations. Cliquez sur
                    "En savoir plus" pour accéder au programme détaillé.</p>
                <div class="w-20 h-1 bg-white/50 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @forelse($catalogues as $catalogue)
                    <div class="catalogue-card">
                        @if($catalogue->image)<img src="{{ asset('storage/' . e($catalogue->image)) }}"
                        alt="{{ e($catalogue->titre) }}" class="w-full h-56 object-cover">@else<div
                                class="w-full h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-gray-400"></i></div>@endif
                        <div class="catalogue-card-body">
                            <h3 class="catalogue-card-title">{{ e($catalogue->titre) }}</h3>
                            <p class="catalogue-card-desc line-clamp-3">{{ Str::limit(e($catalogue->description), 140) }}
                            </p>
                            <a href="{{ route('catalogue.show', $catalogue->id) }}" class="catalogue-card-link">
                                En savoir plus <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 text-blue-100 text-lg md:text-xl"><i
                            class="fas fa-folder-open text-4xl mb-3 opacity-70"></i><br>Aucun catalogue disponible pour le
                        moment.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION PARTENAIRES (défilement infini) -->
    <section id="partenaires-section" class="py-12 md:py-24 lg:py-32 scroll-mt-header bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-10 md:mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase mb-4"
                    style="background: #f97316; color: white; box-shadow: 0 2px 8px rgba(249,115,22,0.3);">🤝 Confiance
                    & Excellence</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-3 md:mb-4"><span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-orange-500 via-yellow-500 via-pink-500 to-purple-500">Ils
                        nous font confiance</span></h2>
                <p class="text-orange-800 text-sm md:text-base uppercase tracking-wide mt-2 font-medium">Nos partenaires
                    et clients</p>
                <div
                    class="w-24 h-1 bg-gradient-to-r from-red-500 via-orange-500 via-yellow-500 via-pink-500 to-purple-500 mx-auto mt-4 rounded-full">
                </div>
            </div>

            @if(isset($partenaires) && $partenaires->count())
                <div class="relative overflow-hidden">
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl p-6 border border-gray-200/50">
                        <div class="overflow-hidden w-full">
                            <div id="marqueeContainer" class="relative w-full">
                                <div id="marqueeTrack" class="flex" style="will-change: transform;">
                                    @for ($i = 0; $i < 3; $i++)
                                        @foreach($partenaires as $partenaire)
                                            <div class="partenaire-item" title="{{ e($partenaire->nom_entreprise) }}">
                                                @if($partenaire->logo)
                                                    <img src="{{ asset('storage/' . e($partenaire->logo)) }}"
                                                        alt="{{ e($partenaire->nom_entreprise) }}">
                                                @else
                                                    <span>{{ e($partenaire->nom_entreprise) }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12 md:py-20 bg-gray-50 rounded-2xl border border-gray-200">
                    <p class="text-orange-600 text-base md:text-xl">Aucun partenaire pour le moment.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- SECTION AVIS (slider horizontal) -->
    <section id="avis-section" class="py-12 md:py-24 lg:py-32 scroll-mt-header"
        style="background: linear-gradient(135deg, #4c1d95 0%, #2e1065 100%);">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-10 md:mb-14">
                <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase mb-4"
                    style="background: rgba(69, 218, 64, 0.73); color: white; backdrop-filter: blur(4px);">⭐
                    Témoignages</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-3">Ce qu'ils disent de
                    nous</h2>
                <p class="text-purple-200 text-sm md:text-base uppercase tracking-wide font-medium">Retours réels de nos
                    clients et partenaires</p>
                <div class="w-16 h-0.5 bg-purple-300 mx-auto mt-4 rounded-full"></div>
            </div>

            @if(isset($avis) && $avis->count())
                <div class="avis-slider">
                    <button class="avis-slider-btn prev" id="avisPrevBtn">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="white" fill="none" color="white">
                            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" />
                        </svg>
                    </button>
                    <div class="avis-slider-viewport">
                        <div class="avis-slider-track" id="avisTrack">
                            @foreach($avis as $a)
                                <div class="avis-card">
                                    <div class="avatar">
                                        @if($a->logo_entreprise)
                                            <img src="{{ asset('storage/' . e($a->logo_entreprise)) }}"
                                                alt="{{ e($a->entreprise_nom) }}">
                                        @else
                                            <span>{{ strtoupper(substr($a->entreprise_nom, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="name">{{ Str::limit($a->entreprise_nom, 30) }}</div>
                                    <div class="role">{{ $a->contact_fonction ?? 'Client' }}</div>
                                    <div class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $a->note) <i class="fas fa-star"></i> @else <i class="far fa-star"></i> @endif
                                        @endfor
                                    </div>
                                    <p class="quote">"{{ Str::limit($a->contenu, 180) }}"</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="avis-slider-btn next" id="avisNextBtn">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="white" fill="none" color="white">
                            <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" />
                        </svg>
                    </button>
                </div>
            @else
                <div class="text-center py-12 md:py-20 bg-white/10 rounded-2xl">
                    <p class="text-white/70 text-base md:text-xl">Aucun avis pour le moment.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- SECTION BLOG (CARROUSEL HORIZONTAL) -->
    <section id="blog" class="py-12 md:py-24 lg:py-32 scroll-mt-header" style="background: #f8fafc;">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-12 md:mb-20">
                <h2 class="text-4xl sm:text-5xl md:text-7xl font-black mb-4 tracking-tighter inline-block">
                    <span class="text-slate-900">BLOG & </span>
                    <span
                        style="background: linear-gradient(to right, #f97316, #ef4444, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        ACTUALITÉS
                    </span>
                </h2>
                <p class="text-slate-500 text-base md:text-xl font-medium mt-2">Conseils d'experts, études de cas et
                    actualités</p>
                <div class="w-20 h-1.5 bg-orange-500 mx-auto mt-6 rounded-full"></div>
            </div>

            @if(isset($articles) && $articles->count())
                <div class="blog-slider">
                    <button class="blog-slider-btn" id="blogPrevBtn">
                        <i class="fas fa-chevron-left text-xl"></i>
                    </button>
                    <div class="blog-slider-viewport">
                        <div class="blog-slider-track" id="blogTrack">
                            @foreach($articles as $article)
                                <article class="blog-card group">
                                    <div class="relative h-64 m-4 overflow-hidden rounded-[2.2rem] border-[1.5px] border-slate-900">
                                        @if($article->image_une)
                                            <img src="{{ asset('storage/' . e($article->image_une)) }}"
                                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                        @else
                                            <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                                <i class="fas fa-image text-4xl text-slate-200"></i>
                                            </div>
                                        @endif
                                        <div class="absolute top-4 left-0 right-0 flex justify-center">
                                            <span
                                                class="bg-[#f97316] text-white text-[10px] font-black px-5 py-2 rounded-full uppercase tracking-widest shadow-xl border border-white/20">
                                                Article Premium
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-6 pt-2 flex flex-col items-center">
                                        <div
                                            class="flex items-center gap-2 text-orange-600 text-xs font-black mb-5 bg-orange-50/50 px-4 py-2 rounded-xl border border-orange-100">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ $article->date_publication->format('d M Y') }}
                                        </div>
                                        <h3
                                            class="font-black text-slate-900 text-xl md:text-2xl mb-4 leading-tight min-h-[3.5rem] line-clamp-2 text-center">
                                            {{ $article->titre }}
                                        </h3>
                                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-6 font-medium px-2 text-center">
                                            {{ Str::limit($article->extrait ?? $article->contenu, 90) }}
                                        </p>
                                        <div class="w-full mt-auto">
                                            <a href="{{ route('blog.show', $article->slug) }}"
                                                class="inline-flex items-center justify-center gap-3 w-full py-4 bg-black text-white rounded-2xl font-black text-xs tracking-[0.2em] transition-all duration-300 hover:bg-orange-600 hover:scale-[1.02] shadow-xl">
                                                <span>LIRE L'ARTICLE</span>
                                                <i class="fas fa-arrow-right text-[10px]"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                    <button class="blog-slider-btn" id="blogNextBtn">
                        <i class="fas fa-chevron-right text-xl"></i>
                    </button>
                </div>
            @else
                <div class="text-center py-20">
                    <p class="text-slate-400 text-xl font-bold italic">Aucune actualité pour le moment...</p>
                </div>
            @endif
        </div>
    </section>

    <!-- FOOTER / CONTACT -->
    <footer id="contact" class="scroll-mt-header">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 2rem 2rem 0 0;"
            class="text-white pt-12 pb-8">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-10">
                    <div class="lg:w-1/2 hidden lg:flex justify-center">
                        <img src="{{ asset('images/dame.png') }}" alt="Contact"
                            class="max-h-[350px] w-auto drop-shadow-2xl">
                    </div>
                    <div class="lg:w-1/2 w-full">
                        <h3 class="text-2xl md:text-3xl font-black mb-6 text-center lg:text-left">Contactez-nous</h3>
                        <div class="w-full">
                            <form id="footerContactForm" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text" name="nom" id="footer_nom" placeholder="Nom complet *"
                                        class="w-full px-5 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/70 focus:bg-white/30 outline-none transition"
                                        required>
                                    <input type="email" name="email" id="footer_email" placeholder="Email *"
                                        class="w-full px-5 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/70 focus:bg-white/30 outline-none transition"
                                        required>
                                </div>
                                <div>
                                    <input type="tel" name="telephone" id="footer_telephone" placeholder="Téléphone"
                                        class="w-full px-5 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/70 focus:bg-white/30 outline-none transition">
                                </div>
                                <textarea name="message" rows="3" id="footer_message" placeholder="Votre message *"
                                    class="w-full px-5 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/70 focus:bg-white/30 outline-none transition"
                                    required></textarea>
                                <button type="submit" id="footerSubmitBtn"
                                    class="w-full bg-white text-indigo-700 font-bold py-3 rounded-xl hover:bg-indigo-50 transition shadow-lg transform hover:-translate-y-1">
                                    Envoyer
                                </button>
                            </form>
                            <div id="footerContactSuccess"
                                class="hidden mt-3 p-2 bg-green-800/30 text-green-200 text-sm text-center rounded-xl"></div>
                            <div id="footerContactError"
                                class="hidden mt-3 p-2 bg-red-800/30 text-red-200 text-sm text-center rounded-xl"></div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-700 pt-6 mt-8 text-center">
                    <p class="text-gray-500 text-xs">&copy; {{ date('Y') }} Tout Help. Tous droits réservés.</p>
                </div>
            </div>
        </div>

        <div style="background: #11131f;" class="py-12 border-t border-white/5">
            <div class="container mx-auto px-4">
                <div class="flex flex-col items-center">
                    <div class="flex flex-col md:flex-row items-center gap-6 md:gap-12 mb-8">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 w-auto rounded-lg">
                            <span class="text-white font-bold tracking-widest uppercase">Tout Help</span>
                        </div>
                        <nav class="flex gap-6 text-xs font-bold uppercase tracking-widest text-gray-400">
                            <a href="#accueil" class="hover:text-white transition">Accueil</a>
                            <a href="#expertise" class="hover:text-white transition">Expertise</a>
                            <a href="#catalogue" class="hover:text-white transition">Catalogue</a>
                        </nav>
                    </div>
                    <div
                        class="w-full max-w-2xl bg-white/5 border border-white/10 rounded-2xl p-6 mb-8 text-center backdrop-blur-sm">
                        <p class="text-indigo-400 text-[10px] uppercase font-bold tracking-[0.2em] mb-2">Partenaire
                            Digital</p>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            Ce fut un plaisir de collaborer avec <strong>Tout Help</strong> pour la création de leur
                            site.
                            <span class="text-white font-medium">Besoin d'un site web professionnel ?</span>
                            Contactez-moi directement :
                        </p>
                        <a href="mailto:rabearisaoninamanoa@gmail.com"
                            class="inline-block mt-3 px-6 py-2 bg-indigo-600/20 hover:bg-indigo-600 text-indigo-300 hover:text-white text-sm font-bold rounded-lg border border-indigo-500/30 transition-all">
                            rabearisaoninamanoa@gmail.com
                        </a>
                    </div>
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest">
                        &copy; {{ date('Y') }} Tout Help Madagascar &bull; Tous droits réservés
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- CHAT MODAL -->
    <div class="robot-icon" id="robotIcon" role="button" aria-label="Ouvrir le support">
        <i class="fas fa-robot"></i>
        <span id="robotBadge" class="robot-badge" style="display:none;" aria-live="polite">0</span>
    </div>
    <div class="chat-modal" id="chatModal" role="dialog" aria-modal="true" aria-label="Chat support">
        <div class="chat-header">
            <div class="chat-header-left">
                <div class="chat-header-avatar">🤖</div>
                <div>
                    <div class="chat-header-name">Support Tout Help</div>
                    <div class="chat-header-status"><span class="chat-status-dot"></span> En ligne</div>
                </div>
            </div>
            <button class="chat-close-btn" onclick="closeChatModal()" aria-label="Fermer le chat">✕</button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="chat-messages-area" id="chatMessagesArea"></div>
        </div>
        <div id="chatInputArea" class="chat-input-area" style="display:none;">
            <textarea id="chatTextarea" class="chat-textarea" rows="1" placeholder="Écrivez votre message..."
                maxlength="1000" aria-label="Votre message"></textarea>
            <button id="chatSendBtn" class="chat-send-btn" aria-label="Envoyer"><svg width="16" height="16"
                    viewBox="0 0 24 24" fill="white">
                    <path d="M22 2 11 13M22 2 15 22l-4-9-9-4 20-7z" />
                </svg></button>
        </div>
        <div id="chatInitForm" class="chat-init-form">
            <div style="text-align:center;margin-bottom:12px;">
                <p style="font-size:13px;color:#e63946;">Bonjour ! 👋 Pour commencer, présentez-vous :</p>
            </div>
            <input type="text" id="initNom" placeholder="Votre nom complet *" maxlength="150" autocomplete="name">
            <input type="email" id="initEmail" placeholder="Votre email *" maxlength="150" autocomplete="email">
            <input type="tel" id="initTel" placeholder="Votre téléphone (optionnel)" maxlength="30" autocomplete="tel">
            <textarea id="initMessage" rows="2" placeholder="Votre message *" style="resize:none;" maxlength="1000"></textarea>
            <button class="chat-init-btn" id="initSendBtn" onclick="submitInitForm()"><i
                    class="fas fa-paper-plane mr-2"></i> Démarrer la conversation</button>
        </div>
        <div id="changeIdentityBar" style="display:none;background:white;border-top:1px solid #ffe0e0;">
            <button class="change-identity-btn" onclick="resetChat()"><i class="fas fa-user-edit mr-1"></i> Nouvelle
                conversation</button>
        </div>
        <div class="chat-footer">Réponse dans les plus brefs délais · Tout Help</div>
    </div>

    @vite(['resources/js/app.js'])

    <script>
        (function () {
            "use strict";
            let currentEmail = '', currentNom = '', unreadCount = 0, audioCtx = null, pusherListenerSet = false, pollInterval = null, isLoading = false, isSending = false, lastMessageId = null, lastRefreshTime = 0, lastMessagesHash = '', hasNewMessage = false, notificationTimeout = null, pusherChannel = null, wsRetryCount = 0;
            const rateLimits = new Map(), MAX_MESSAGE_LENGTH = 1000, MAX_NAME_LENGTH = 150, MAX_EMAIL_LENGTH = 150, MAX_PHONE_LENGTH = 30;

            function escapeHtml(str) { if (str === null || str === undefined) return ''; const div = document.createElement('div'); div.textContent = String(str); return div.innerHTML; }
            function isValidEmail(email) { if (!email || typeof email !== 'string') return false; const trimmed = email.trim(); if (trimmed.length > MAX_EMAIL_LENGTH) return false; const emailRegex = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]{0,63}@[a-zA-Z0-9][a-zA-Z0-9.-]{0,252}\.[a-zA-Z]{2,}$/; return emailRegex.test(trimmed); }
            function isValidName(name) { if (!name || typeof name !== 'string') return false; const trimmed = name.trim(); if (trimmed.length > MAX_NAME_LENGTH) return false; if (trimmed.length < 2) return false; const nameRegex = /^[a-zA-ZÀ-ÿ\s'\-]{2,150}$/; return nameRegex.test(trimmed); }
            function isValidPhone(phone) { if (!phone) return true; const trimmed = phone.trim(); if (trimmed.length > MAX_PHONE_LENGTH) return false; const phoneRegex = /^[\d\s+\-().]{1,30}$/; return phoneRegex.test(trimmed); }
            function isValidMessage(msg) { if (!msg || typeof msg !== 'string') return false; const trimmed = msg.trim(); if (trimmed.length < 2) return false; if (trimmed.length > MAX_MESSAGE_LENGTH) return false; if (/<[^>]*>/.test(trimmed)) return false; if (/[\x00-\x08\x0B\x0C\x0E-\x1F]/.test(trimmed)) return false; return true; }
            function sanitize(str, max = 1000) { if (!str) return ''; let cleaned = String(str); cleaned = cleaned.replace(/<[^>]*>/g, ''); cleaned = cleaned.substring(0, max); return cleaned.trim(); }

            const RATE_LIMIT_DELAY = 5000, MAX_REQUESTS_PER_MINUTE = 12, requestTimestamps = [];
            function isRateLimited(email) { const now = Date.now(); while (requestTimestamps.length > 0 && requestTimestamps[0] < now - 60000) { requestTimestamps.shift(); } if (requestTimestamps.length >= MAX_REQUESTS_PER_MINUTE) { flashError('Trop de tentatives. Veuillez patienter une minute.'); return true; } const last = rateLimits.get(email) || 0; if (now - last < RATE_LIMIT_DELAY) { flashError('Merci de patienter quelques secondes avant de renvoyer.'); return true; } rateLimits.set(email, now); requestTimestamps.push(now); return false; }
            function checkRateLimit(email) { return !isRateLimited(email); }

            let audioEnabled = false, pendingSounds = [];
            function initAudio() { if (!audioCtx) { try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch (e) { } } }
            function enableAudio() { if (audioEnabled) return; initAudio(); if (audioCtx && audioCtx.state === 'suspended') { audioCtx.resume().then(() => { audioEnabled = true; pendingSounds.forEach(() => playNotifSound()); pendingSounds = []; }).catch(e => { }); } else if (audioCtx && audioCtx.state === 'running') { audioEnabled = true; } }
            function playNotifSound() { if (!audioEnabled) { pendingSounds.push(true); return; } try { initAudio(); if (!audioCtx || audioCtx.state !== 'running') return; const now = audioCtx.currentTime; const o1 = audioCtx.createOscillator(); const g1 = audioCtx.createGain(); o1.connect(g1); g1.connect(audioCtx.destination); o1.type = 'sine'; o1.frequency.value = 880; g1.gain.setValueAtTime(0.2, now); g1.gain.exponentialRampToValueAtTime(0.00001, now + 0.25); o1.start(now); o1.stop(now + 0.25); setTimeout(() => { if (audioCtx && audioCtx.state === 'running') { const o2 = audioCtx.createOscillator(); const g2 = audioCtx.createGain(); o2.connect(g2); g2.connect(audioCtx.destination); o2.type = 'sine'; o2.frequency.value = 660; g2.gain.setValueAtTime(0.15, audioCtx.currentTime); g2.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + 0.2); o2.start(); o2.stop(audioCtx.currentTime + 0.2); } }, 120); } catch (e) { } }
            document.addEventListener('click', enableAudio, { once: true });
            document.getElementById('robotIcon')?.addEventListener('click', enableAudio);

            function updateBadge() { const b = document.getElementById('robotBadge'); if (!b) return; if (unreadCount > 0) { b.textContent = unreadCount > 99 ? '99+' : unreadCount; b.style.display = 'flex'; b.style.animation = 'none'; b.offsetHeight; b.style.animation = 'badgePulse 0.6s ease-in-out'; } else { b.style.display = 'none'; } }
            function showRobotNotification() { const robot = document.getElementById('robotIcon'); if (!robot) return; robot.classList.add('robot-notification'); setTimeout(() => robot.classList.remove('robot-notification'), 1000); }
            function openChatModal() { const modal = document.getElementById('chatModal'); modal.classList.add('active'); unreadCount = 0; updateBadge(); if (currentEmail) { loadMessages(true); startPolling(); } scrollChatToBottom(); }
            function closeChatModal() { document.getElementById('chatModal').classList.remove('active'); stopPolling(); }
            document.getElementById('robotIcon').addEventListener('click', openChatModal);

            function scrollChatToBottom() { setTimeout(() => { const b = document.getElementById('chatBody'); if (b) b.scrollTop = b.scrollHeight; }, 100); }
            function startPolling() { stopPolling(); if (!currentEmail) return; pollInterval = setInterval(() => { if (currentEmail && document.getElementById('chatModal').classList.contains('active')) { loadMessages(false); } }, 6000); }
            function stopPolling() { if (pollInterval) { clearInterval(pollInterval); pollInterval = null; } }
            function generateMessagesHash(messages) { if (!messages || messages.length === 0) return ''; const lastMsg = messages[messages.length - 1]; return `${lastMsg?.id || ''}-${lastMsg?.updated_at || ''}-${messages.length}`; }

            function renderMessages(messages) { const area = document.getElementById('chatMessagesArea'); if (!messages || messages.length === 0) { area.innerHTML = '<div class="pending-tag">⏳ En attente de réponse...</div>'; return; } const frag = document.createDocumentFragment(); for (let i = 0; i < messages.length; i++) { const m = messages[i]; if (m.message && m.message.trim()) { const sentDiv = document.createElement('div'); sentDiv.className = 'bubble-sent'; const sentInner = document.createElement('div'); sentInner.className = 'bubble-sent-inner'; const sentTxt = document.createElement('div'); sentTxt.className = 'bubble-text'; sentTxt.textContent = escapeHtml(m.message); const sentTime = document.createElement('div'); sentTime.className = 'bubble-time'; sentTime.textContent = formatTime(m.created_at); sentInner.append(sentTxt, sentTime); sentDiv.appendChild(sentInner); frag.appendChild(sentDiv); } if (m.reponse_admin && m.reponse_admin.trim()) { const recvDiv = document.createElement('div'); recvDiv.className = 'bubble-received'; const av = document.createElement('div'); av.className = 'bubble-received-avatar'; av.textContent = 'TH'; const recvInner = document.createElement('div'); recvInner.className = 'bubble-received-inner'; const recvTxt = document.createElement('div'); recvTxt.className = 'bubble-text'; recvTxt.textContent = escapeHtml(m.reponse_admin); const recvTime = document.createElement('div'); recvTime.className = 'bubble-time-left'; recvTime.textContent = formatTime(m.updated_at); recvInner.append(recvTxt, recvTime); recvDiv.append(av, recvInner); frag.appendChild(recvDiv); } } area.innerHTML = ''; area.appendChild(frag); scrollChatToBottom(); }

            function formatTime(d) { if (!d) return ''; try { return new Date(d).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); } catch (e) { return ''; } }

            async function loadMessages(force = false) { if (!currentEmail) return; if (isLoading) return; const now = Date.now(); if (now - lastRefreshTime < 500 && !force) return; lastRefreshTime = now; isLoading = true; try { const encodedEmail = encodeURIComponent(currentEmail); const url = `/api/messages?email=${encodedEmail}&_=${now}`; const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, cache: 'no-store' }); if (!res.ok) throw new Error(`HTTP ${res.status}`); const msgs = await res.json(); const messages = Array.isArray(msgs) ? msgs : []; const currentHash = generateMessagesHash(messages); const isNewContent = currentHash !== lastMessagesHash; if (isNewContent || force) { lastMessagesHash = currentHash; if (messages.length > 0) lastMessageId = messages[messages.length - 1]?.id; const isChatOpen = document.getElementById('chatModal').classList.contains('active'); if (!isChatOpen && isNewContent && !force) { unreadCount++; updateBadge(); showRobotNotification(); playNotifSound(); } renderMessages(messages); if (isChatOpen) scrollChatToBottom(); } } catch (e) { console.warn('[Chat] loadMessages error:', e.message); } finally { isLoading = false; } }

            async function sendMessageAPI(nom, email, telephone, message) { if (!isValidName(nom)) return { success: false, message: 'Nom invalide (2-150 caractères, lettres uniquement).' }; if (!isValidEmail(email)) return { success: false, message: 'Email invalide.' }; if (!isValidPhone(telephone)) return { success: false, message: 'Téléphone invalide.' }; if (!isValidMessage(message)) return { success: false, message: 'Message invalide (2-1000 caractères, pas de code HTML).' }; if (!checkRateLimit(email)) return { success: false, message: '' }; const csrf = document.querySelector('meta[name="csrf-token"]')?.content; if (!csrf) return { success: false, message: 'Erreur de sécurité. Rechargez la page.' }; const cleanNom = sanitize(nom, MAX_NAME_LENGTH); const cleanEmail = email.trim().substring(0, MAX_EMAIL_LENGTH); const cleanTel = sanitize(telephone, MAX_PHONE_LENGTH); const cleanMsg = sanitize(message, MAX_MESSAGE_LENGTH); try { const res = await fetch('/contact/send', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, body: JSON.stringify({ nom: cleanNom, email: cleanEmail, telephone: cleanTel, message: cleanMsg }) }); if (!res.ok) throw new Error(`HTTP ${res.status}`); return await res.json(); } catch (e) { console.warn('[Chat] sendMessageAPI error:', e.message); return { success: false, message: 'Erreur réseau. Vérifiez votre connexion.' }; } }

            async function submitInitForm() { if (isSending) return; const nom = document.getElementById('initNom').value.trim(); const email = document.getElementById('initEmail').value.trim(); const tel = document.getElementById('initTel').value.trim(); const msg = document.getElementById('initMessage').value.trim(); if (!nom || !email || !msg) { flashError('Merci de remplir tous les champs obligatoires.'); return; } const btn = document.getElementById('initSendBtn'); isSending = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Envoi...'; btn.disabled = true; const result = await sendMessageAPI(nom, email, tel, msg); if (result.success) { currentEmail = email.trim().substring(0, MAX_EMAIL_LENGTH); currentNom = nom.trim().substring(0, MAX_NAME_LENGTH); switchToConversationMode(); await new Promise(r => setTimeout(r, 500)); lastMessagesHash = ''; await loadMessages(true); startPolling(); setupPusherListener(); } else if (result.message) { flashError(result.message); } btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation'; btn.disabled = false; isSending = false; }

            async function sendQuickMessage() { if (isSending) return; const ta = document.getElementById('chatTextarea'); const msg = ta.value.trim(); if (!msg || !currentEmail) return; const btn = document.getElementById('chatSendBtn'); isSending = true; btn.disabled = true; ta.value = ''; ta.style.height = 'auto'; const result = await sendMessageAPI(currentNom, currentEmail, '', msg); if (result.success) { lastMessagesHash = ''; await loadMessages(true); } else if (result.message) { flashError(result.message); ta.value = msg; } btn.disabled = false; isSending = false; }

            const footerForm = document.getElementById('footerContactForm');
            if (footerForm) {
                footerForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (isSending) return;
                    const nom = document.getElementById('footer_nom').value.trim();
                    const email = document.getElementById('footer_email').value.trim();
                    const tel = document.getElementById('footer_telephone').value.trim();
                    const msg = document.getElementById('footer_message').value.trim();
                    if (!nom || !email || !msg) { flashError('Merci de remplir tous les champs obligatoires.'); return; }
                    const btn = document.getElementById('footerSubmitBtn');
                    const originalText = btn.innerHTML;
                    isSending = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Envoi...';
                    btn.disabled = true;
                    const result = await sendMessageAPI(nom, email, tel, msg);
                    if (result.success) {
                        currentEmail = email.trim().substring(0, MAX_EMAIL_LENGTH);
                        currentNom = nom.trim().substring(0, MAX_NAME_LENGTH);
                        footerForm.reset();
                        switchToConversationMode();
                        openChatModal();
                        lastMessagesHash = '';
                        await loadMessages(true);
                        startPolling();
                        setupPusherListener();
                        const successDiv = document.getElementById('footerContactSuccess');
                        if (successDiv) { successDiv.textContent = 'Message envoyé avec succès ! La conversation a été ouverte.'; successDiv.classList.remove('hidden'); setTimeout(() => successDiv.classList.add('hidden'), 5000); }
                    } else if (result.message) {
                        const errorDiv = document.getElementById('footerContactError');
                        if (errorDiv) { errorDiv.textContent = result.message; errorDiv.classList.remove('hidden'); setTimeout(() => errorDiv.classList.add('hidden'), 5000); }
                        flashError(result.message);
                    }
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    isSending = false;
                });
            }

            function switchToConversationMode() { document.getElementById('chatInitForm').style.display = 'none'; document.getElementById('chatInputArea').style.display = 'flex'; document.getElementById('changeIdentityBar').style.display = 'block'; }

            function resetChat() { currentEmail = ''; currentNom = ''; lastMessageId = null; lastMessagesHash = ''; unreadCount = 0; updateBadge(); stopPolling(); pusherListenerSet = false; document.getElementById('chatInitForm').style.display = 'block'; document.getElementById('chatInputArea').style.display = 'none'; document.getElementById('changeIdentityBar').style.display = 'none'; const area = document.getElementById('chatMessagesArea'); if (area) area.innerHTML = '';['initNom', 'initEmail', 'initTel', 'initMessage'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; }); const btn = document.getElementById('initSendBtn'); if (btn) { btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Démarrer la conversation'; btn.disabled = false; } isSending = false; }

            function setupPusherListener() {
                if (pusherListenerSet) return;
                function initPusher() {
                    if (typeof Pusher === 'undefined') { wsRetryCount++; if (wsRetryCount < 30) setTimeout(initPusher, 500); return; }
                    const pusherKey = '{{ env("PUSHER_APP_KEY") }}';
                    const pusherCluster = '{{ env("PUSHER_APP_CLUSTER") }}';
                    if (!pusherKey || pusherKey === '') { return; }
                    try {
                        const pusher = new Pusher(pusherKey, { cluster: pusherCluster, encrypted: true });
                        if (pusherChannel) { try { pusherChannel.unbind_all(); } catch (e) { } }
                        pusherChannel = pusher.subscribe('new-messages');
                        pusherChannel.bind('App\\Events\\NewMessageReceived', (event) => {
                            if (currentEmail && currentEmail === event.email_client) {
                                lastMessagesHash = '';
                                loadMessages(true);
                                const modal = document.getElementById('chatModal');
                                if (modal && modal.classList.contains('active')) { playNotifSound(); } else { unreadCount++; updateBadge(); showRobotNotification(); playNotifSound(); }
                            }
                        });
                        pusherListenerSet = true;
                    } catch (e) { }
                }
                initPusher();
            }

            function flashError(msg) { if (!msg) return; let el = document.getElementById('chatFlashError'); if (!el) { el = document.createElement('div'); el.id = 'chatFlashError'; el.style.cssText = 'background:#fee2e2;color:#b91c1c;padding:8px 12px;border-radius:8px;font-size:12px;margin:0 0 8px;text-align:center;'; const form = document.getElementById('chatInitForm'); if (form) form.prepend(el); } el.textContent = escapeHtml(String(msg).substring(0, 200)); el.style.display = 'block'; setTimeout(() => { if (el) el.style.display = 'none'; }, 5000); }

            window.submitInitForm = submitInitForm;
            window.sendQuickMessage = sendQuickMessage;
            window.closeChatModal = closeChatModal;
            window.resetChat = resetChat;

            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('chatSendBtn')?.addEventListener('click', sendQuickMessage);
                document.getElementById('chatTextarea')?.addEventListener('keypress', (e) => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendQuickMessage(); } });
                document.getElementById('chatTextarea')?.addEventListener('input', function () { this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 100) + 'px'; });
            });
        })();

        /* Slider avis */
        (function () {
            function initSlider() {
                const track = document.getElementById('avisTrack');
                if (!track) return;
                const cards = Array.from(track.children);
                if (cards.length === 0) return;
                const prevBtn = document.getElementById('avisPrevBtn');
                const nextBtn = document.getElementById('avisNextBtn');
                if (!prevBtn || !nextBtn) return;
                let currentIndex = 0;
                let visibleCount = 3;
                let cardWidth = 0;
                let gap = 24;
                function updateLayout() {
                    const container = track.parentElement;
                    const containerWidth = container.clientWidth;
                    if (window.innerWidth < 768) { visibleCount = 1; gap = 16; } else { visibleCount = 3; gap = 24; }
                    cardWidth = (containerWidth - (gap * (visibleCount - 1))) / visibleCount;
                    for (let i = 0; i < cards.length; i++) { cards[i].style.flex = `0 0 ${cardWidth}px`; }
                }
                function slideToIndex() {
                    const offset = -currentIndex * (cardWidth + gap);
                    track.style.transform = `translateX(${offset}px)`;
                    const maxIndex = Math.max(0, cards.length - visibleCount);
                    prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
                    nextBtn.style.opacity = currentIndex >= maxIndex ? '0.4' : '1';
                }
                function next() { const maxIndex = Math.max(0, cards.length - visibleCount); if (currentIndex < maxIndex) { currentIndex++; slideToIndex(); } }
                function prev() { if (currentIndex > 0) { currentIndex--; slideToIndex(); } }
                prevBtn.addEventListener('click', prev);
                nextBtn.addEventListener('click', next);
                updateLayout();
                slideToIndex();
                window.addEventListener('resize', () => { updateLayout(); slideToIndex(); });
            }
            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initSlider); else initSlider();
        })();

        /* Marquee partenaires */
        (function () {
            const track = document.getElementById('marqueeTrack');
            if (!track) return;
            let speed = 1;
            let position = 0;
            let animationId = null;
            let isHovering = false;
            function startMarquee() {
                if (animationId) cancelAnimationFrame(animationId);
                function step() {
                    if (!isHovering) {
                        position -= speed;
                        const trackWidth = track.scrollWidth;
                        const containerWidth = track.parentElement.clientWidth;
                        if (Math.abs(position) >= trackWidth / 3) { position = 0; }
                        track.style.transform = `translateX(${position}px)`;
                    }
                    animationId = requestAnimationFrame(step);
                }
                step();
            }
            const container = document.querySelector('#partenaires-section .overflow-hidden');
            if (container) {
                container.addEventListener('mouseenter', () => { isHovering = true; });
                container.addEventListener('mouseleave', () => { isHovering = false; });
            }
            startMarquee();
        })();

        /* Carrousel Blog */
        (function () {
            function initBlogSlider() {
                const track = document.getElementById('blogTrack');
                if (!track) return;
                const cards = Array.from(track.children);
                if (cards.length === 0) return;
                const prevBtn = document.getElementById('blogPrevBtn');
                const nextBtn = document.getElementById('blogNextBtn');
                if (!prevBtn || !nextBtn) return;

                let currentIndex = 0;
                let visibleCount = 4;
                let cardWidth = 0;
                let gap = 24;

                function updateLayout() {
                    const container = track.parentElement;
                    const containerWidth = container.clientWidth;
                    if (window.innerWidth < 768) {
                        visibleCount = 1;
                        gap = 16;
                    } else if (window.innerWidth < 1024) {
                        visibleCount = 2;
                        gap = 20;
                    } else {
                        visibleCount = 4;
                        gap = 24;
                    }
                    cardWidth = (containerWidth - (gap * (visibleCount - 1))) / visibleCount;
                    for (let i = 0; i < cards.length; i++) {
                        cards[i].style.flex = `0 0 ${cardWidth}px`;
                    }
                }

                function slideToIndex() {
                    const offset = -currentIndex * (cardWidth + gap);
                    track.style.transform = `translateX(${offset}px)`;
                    const maxIndex = Math.max(0, cards.length - visibleCount);
                    prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
                    prevBtn.style.cursor = currentIndex === 0 ? 'not-allowed' : 'pointer';
                    nextBtn.style.opacity = currentIndex >= maxIndex ? '0.4' : '1';
                    nextBtn.style.cursor = currentIndex >= maxIndex ? 'not-allowed' : 'pointer';
                }

                function next() {
                    const maxIndex = Math.max(0, cards.length - visibleCount);
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        slideToIndex();
                    }
                }

                function prev() {
                    if (currentIndex > 0) {
                        currentIndex--;
                        slideToIndex();
                    }
                }

                prevBtn.addEventListener('click', prev);
                nextBtn.addEventListener('click', next);

                // Swipe sur mobile
                let touchStartX = 0;
                let touchEndX = 0;
                track.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                });
                track.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    if (touchEndX < touchStartX - 50) next();
                    if (touchEndX > touchStartX + 50) prev();
                });

                updateLayout();
                slideToIndex();

                window.addEventListener('resize', () => {
                    updateLayout();
                    slideToIndex();
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initBlogSlider);
            } else {
                initBlogSlider();
            }
        })();

        /* Menu burger mobile */
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobileMenuBtn');
            const menu = document.getElementById('mobileMenu');
            if (btn && menu) {
                btn.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                });
                menu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => menu.classList.add('hidden'));
                });
            }
        });
    </script>
</body>

</html>