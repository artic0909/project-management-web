<!DOCTYPE html>
<html lang="en">
<script>
    (function() {
        const theme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', theme);
    })();
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP - Project Management')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --font: 'Plus Jakarta Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 10px;
            --r-sm: 7px;
            --r-lg: 16px;
            --transition: all .18s cubic-bezier(.4, 0, .2, 1);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, .18), 0 1px 4px rgba(0, 0, 0, .1);
            --shadow-lg: 0 24px 64px rgba(0, 0, 0, .32), 0 4px 16px rgba(0, 0, 0, .16);
        }

        /* ── DARK THEME (default) ── */
        [data-theme="dark"] {
            --bg: #0c0e14;
            --bg2: #111318;
            --bg3: #181b23;
            --bg4: #1f2330;
            --b1: #252836;
            --b2: #2e3347;
            --b3: #3a4055;
            --t1: #eef0f8;
            --t2: #b0b8d1;
            --t3: #6b7594;
            --t4: #3e4560;
            --accent: #6366f1;
            --accent2: #818cf8;
            --accent-bg: rgba(99, 102, 241, .12);
            --accent-glow: 0 0 24px rgba(99, 102, 241, .25);
            --sidebar-w: 240px;
            --topbar-h: 58px;
        }

        /* ── LIGHT THEME ── */
        [data-theme="light"] {
            --bg: #f1f3f9;
            --bg2: #ffffff;
            --bg3: #f7f8fc;
            --bg4: #eef0f8;
            --b1: #e0e4f0;
            --b2: #d0d5e8;
            --b3: #bcc2d8;
            --t1: #0f1117;
            --t2: #374060;
            --t3: #7681a0;
            --t4: #b0bace;
            --accent: #5b5ff0;
            --accent2: #4347c9;
            --accent-bg: rgba(91, 95, 240, .1);
            --accent-glow: 0 0 24px rgba(91, 95, 240, .18);
            --sidebar-w: 240px;
            --topbar-h: 58px;
        }

        /* ─── RESET ─── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--t1);
            font-size: 14px;
            line-height: 1.5;
            overflow-x: hidden;
            transition: background .25s, color .25s;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            cursor: pointer;
            border: none;
            background: none;
            font-family: var(--font);
        }

        input,
        select,
        textarea {
            font-family: var(--font);
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--b2);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--b3);
        }

        ::selection {
            background: rgba(99, 102, 241, .3);
        }

        /* ─── LAYOUT ─── */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--bg2);
            border-right: 1px solid var(--b1);
            display: flex;
            flex-direction: column;
            z-index: 200;
            transition: width .22s cubic-bezier(.4, 0, .2, 1), transform .22s cubic-bezier(.4, 0, .2, 1);
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 64px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 199;
            backdrop-filter: blur(2px);
        }

        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            transition: margin-left .22s cubic-bezier(.4, 0, .2, 1);
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed~.main-wrap {
            margin-left: 64px;
        }

        /* ─── SIDEBAR HEADER ─── */
        .sidebar-header {
            height: var(--topbar-h);
            padding: 0 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--b1);
            flex-shrink: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
            background: var(--accent);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: var(--accent-glow);
        }

        .brand-text {
            overflow: hidden;
            white-space: nowrap;
        }

        .brand-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--t1);
            display: block;
            line-height: 1.2;
            letter-spacing: -.3px;
        }

        .brand-sub {
            font-size: 10px;
            color: var(--t3);
            font-weight: 500;
        }

        .sidebar-collapse-btn {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: var(--bg3);
            border: 1px solid var(--b1);
            color: var(--t3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .sidebar-collapse-btn:hover {
            background: var(--accent-bg);
            color: var(--accent);
            border-color: var(--accent);
        }

        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .brand-sub {
            display: none;
        }

        .sidebar.collapsed .sidebar-collapse-btn {
            margin: 0 auto;
        }

        /* ─── PANEL SWITCHER ─── */
        .panel-switcher {
            display: flex;
            gap: 4px;
            padding: 10px 10px 6px;
            border-bottom: 1px solid var(--b1);
            flex-shrink: 0;
        }

        .panel-btn {
            flex: 1;
            padding: 7px 4px;
            border-radius: var(--r-sm);
            background: transparent;
            color: var(--t3);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            font-size: 10px;
            font-weight: 600;
            transition: var(--transition);
            position: relative;
        }

        .panel-btn i {
            font-size: 15px;
        }

        .panel-btn span {
            white-space: nowrap;
            overflow: hidden;
        }

        .panel-btn:hover {
            background: var(--bg4);
            color: var(--t2);
        }

        .panel-btn.active {
            background: var(--accent-bg);
            color: var(--accent);
        }

        .sidebar.collapsed .panel-btn span {
            display: none;
        }

        /* ─── SIDEBAR NAV ─── */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px 8px;
        }

        .nav-panel {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .nav-panel.hidden {
            display: none;
        }

        .nav-section-label {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--t4);
            padding: 12px 8px 4px;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-section-label {
            display: none;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            border-radius: var(--r-sm);
            color: var(--t3);
            font-size: 13.5px;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            white-space: nowrap;
        }

        .nav-item i {
            font-size: 16px;
            flex-shrink: 0;
            width: 18px;
            text-align: center;
        }

        .nav-item:hover {
            background: var(--bg4);
            color: var(--t1);
        }

        .nav-item.active {
            background: var(--accent-bg);
            color: var(--accent);
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
            left: -8px;
        }

        .nav-badge {
            margin-left: auto;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 20px;
            background: var(--accent);
            color: #fff;
            flex-shrink: 0;
        }

        .nav-badge.pulse {
            animation: badgePulse 2s infinite;
        }

        @keyframes badgePulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .6;
            }
        }

        .nav-count {
            margin-left: auto;
            font-size: 11px;
            font-weight: 600;
            color: var(--t3);
            background: var(--bg4);
            padding: 1px 7px;
            border-radius: 20px;
            border: 1px solid var(--b1);
        }

        .nav-dot {
            margin-left: auto;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .nav-dot.green {
            background: #10b981;
            box-shadow: 0 0 6px rgba(16, 185, 129, .5);
        }

        .sidebar.collapsed .nav-item span,
        .sidebar.collapsed .nav-badge,
        .sidebar.collapsed .nav-count,
        .sidebar.collapsed .nav-dot {
            display: none;
        }

        .sidebar.collapsed .nav-item {
            padding: 10px;
            justify-content: center;
        }

        /* ─── SIDEBAR FOOTER ─── */
        .sidebar-footer {
            padding: 10px;
            border-top: 1px solid var(--b1);
            flex-shrink: 0;
        }

        .theme-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 6px 8px;
            color: var(--t3);
            font-size: 12px;
            font-weight: 500;
        }

        .theme-label {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sidebar.collapsed .theme-row {
            display: none;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px;
            border-radius: var(--r-sm);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .user-profile:hover {
            background: var(--bg4);
        }

        .user-info {
            overflow: hidden;
            flex: 1;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--t1);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 10.5px;
            color: var(--t3);
        }

        .user-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 6px rgba(16, 185, 129, .6);
            flex-shrink: 0;
        }

        .sidebar.collapsed .user-info,
        .sidebar.collapsed .user-status-dot {
            display: none;
        }

        .sidebar.collapsed .user-profile {
            justify-content: center;
        }

        /* ─── TOGGLE SWITCH ─── */
        .toggle-switch {
            position: relative;
            flex-shrink: 0;
        }

        .toggle-switch input {
            display: none;
        }

        .toggle-track {
            display: block;
            width: 36px;
            height: 20px;
            border-radius: 20px;
            background: var(--b2);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .toggle-thumb {
            position: absolute;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--t3);
            top: 3px;
            left: 3px;
            transition: var(--transition);
        }

        .toggle-switch input:checked+.toggle-track {
            background: var(--accent);
        }

        .toggle-switch input:checked+.toggle-track .toggle-thumb {
            left: 19px;
            background: #fff;
        }

        /* ─── USER AVATAR ─── */
        .user-ava {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .user-ava.sm {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            font-size: 10px;
        }

        /* ─── TOPBAR ─── */
        .topbar {
            height: var(--topbar-h);
            background: var(--bg2);
            border-bottom: 1px solid var(--b1);
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .topbar-center {
            flex: 1;
            max-width: 480px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: auto;
        }

        .mob-menu-btn {
            display: none;
            width: 32px;
            height: 32px;
            border-radius: var(--r-sm);
            background: var(--bg4);
            color: var(--t2);
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }

        .breadcrumb-panel {
            color: var(--t3);
            font-weight: 500;
        }

        .bc-sep {
            color: var(--t4);
            font-size: 10px;
        }

        .breadcrumb-page {
            color: var(--t1);
            font-weight: 600;
        }

        .global-search {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg3);
            border: 1px solid var(--b1);
            border-radius: var(--r);
            padding: 8px 14px;
            transition: var(--transition);
        }

        .global-search:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
            background: var(--bg2);
        }

        .global-search i {
            color: var(--t3);
            font-size: 13px;
            flex-shrink: 0;
        }

        .global-search input {
            background: none;
            border: none;
            outline: none;
            color: var(--t1);
            font-size: 13.5px;
            width: 100%;
        }

        .global-search input::placeholder {
            color: var(--t3);
        }

        .global-search kbd {
            font-family: var(--mono);
            font-size: 10px;
            color: var(--t4);
            background: var(--bg4);
            border: 1px solid var(--b2);
            border-radius: 4px;
            padding: 1px 5px;
            flex-shrink: 0;
        }

        .tb-btn {
            width: 34px;
            height: 34px;
            border-radius: var(--r-sm);
            background: var(--bg3);
            border: 1px solid var(--b1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--t2);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            font-size: 14px;
        }

        .tb-btn:hover {
            background: var(--accent-bg);
            border-color: var(--accent);
            color: var(--accent);
        }

        .notif-btn {
            position: relative;
        }

        .notif-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 16px;
            height: 16px;
            background: #ef4444;
            border-radius: 50%;
            font-size: 9px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg2);
        }

        .tb-divider {
            width: 1px;
            height: 24px;
            background: var(--b1);
            margin: 0 2px;
        }

        .tb-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px 4px 4px;
            border-radius: var(--r-sm);
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .tb-user:hover {
            background: var(--bg3);
            border-color: var(--b1);
        }

        .tb-user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--t1);
            white-space: nowrap;
        }

        /* ─── DROPDOWNS ─── */
        .notif-panel {
            position: absolute;
            top: calc(var(--topbar-h) + 6px);
            right: 60px;
            width: 360px;
            background: var(--bg2);
            border: 1px solid var(--b2);
            border-radius: var(--r-lg);
            box-shadow: var(--shadow-lg);
            z-index: 500;
            display: none;
            overflow: hidden;
        }

        .notif-panel.open {
            display: block;
            animation: dropIn .18s ease;
        }

        @keyframes dropIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notif-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px 12px;
            border-bottom: 1px solid var(--b1);
        }

        .notif-header span {
            font-weight: 700;
            font-size: 14px;
        }

        .btn-xs {
            font-size: 11px;
            color: var(--accent);
            font-weight: 600;
            background: var(--accent-bg);
            border: none;
            padding: 3px 9px;
            border-radius: 6px;
            cursor: pointer;
        }

        .notif-list {
            max-height: 320px;
            overflow-y: auto;
        }

        .notif-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--b1);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .notif-item:hover {
            background: var(--bg3);
        }

        .notif-item.unread {
            background: var(--accent-bg);
        }

        .notif-item.unread::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--accent);
        }

        .notif-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .notif-icon.blue {
            background: rgba(99, 102, 241, .15);
            color: #6366f1;
        }

        .notif-icon.green {
            background: rgba(16, 185, 129, .15);
            color: #10b981;
        }

        .notif-icon.orange {
            background: rgba(245, 158, 11, .15);
            color: #f59e0b;
        }

        .notif-icon.purple {
            background: rgba(139, 92, 246, .15);
            color: #8b5cf6;
        }

        .notif-icon.red {
            background: rgba(239, 68, 68, .15);
            color: #ef4444;
        }

        .notif-body {
            flex: 1;
            font-size: 12.5px;
            color: var(--t2);
            line-height: 1.4;
        }

        .notif-body strong {
            color: var(--t1);
        }

        .notif-time {
            font-size: 11px;
            color: var(--t3);
            margin-top: 2px;
        }

        .notif-footer {
            text-align: center;
            padding: 10px;
            border-top: 1px solid var(--b1);
            font-size: 12.5px;
            color: var(--accent);
            font-weight: 600;
        }

        .user-menu {
            position: absolute;
            top: calc(var(--topbar-h) + 6px);
            right: 16px;
            width: 200px;
            background: var(--bg2);
            border: 1px solid var(--b2);
            border-radius: var(--r);
            box-shadow: var(--shadow-lg);
            z-index: 500;
            padding: 6px;
            display: none;
        }

        .user-menu.open {
            display: block;
            animation: dropIn .18s ease;
        }

        .um-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 12px;
            border-radius: var(--r-sm);
            font-size: 13px;
            font-weight: 500;
            color: var(--t2);
            transition: var(--transition);
        }

        .um-item:hover {
            background: var(--bg4);
            color: var(--t1);
        }

        .um-item.danger:hover {
            background: rgba(239, 68, 68, .1);
            color: #ef4444;
        }

        .um-item i {
            width: 15px;
            font-size: 13px;
        }

        .um-divider {
            border: none;
            border-top: 1px solid var(--b1);
            margin: 4px 0;
        }

        /* ─── PAGE AREA ─── */
        .page-area {
            flex: 1;
            padding: 24px 24px 40px;
            overflow: auto;
        }

        .page {
            animation: pageIn .2s ease;
        }

        @keyframes pageIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page.hidden {
            display: none;
        }

        /* ─── PAGE HEADER ─── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--t1);
            letter-spacing: -.4px;
            line-height: 1.2;
        }

        .page-desc {
            font-size: 13px;
            color: var(--t3);
            margin-top: 3px;
            font-weight: 400;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* ─── BUTTONS ─── */
        .btn-primary-solid {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: var(--r-sm);
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-primary-solid:hover {
            background: var(--accent2);
            box-shadow: var(--accent-glow);
            transform: translateY(-1px);
        }

        .btn-primary-solid.sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-ghost {
            background: transparent;
            color: var(--t2);
            border: 1px solid var(--b2);
            padding: 8px 14px;
            border-radius: var(--r-sm);
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-ghost:hover {
            background: var(--bg4);
            border-color: var(--b3);
            color: var(--t1);
        }

        .btn-ghost.danger-ghost:hover {
            background: rgba(239, 68, 68, .1);
            border-color: #ef4444;
            color: #ef4444;
        }

        .btn-full-outline {
            width: 100%;
            margin-top: 14px;
            padding: 9px;
            border-radius: var(--r-sm);
            background: transparent;
            border: 1px solid var(--b2);
            color: var(--t2);
            font-size: 12.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: var(--transition);
        }

        .btn-full-outline:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--accent-bg);
        }

        /* ─── DATE RANGE PICKER ─── */
        .date-range-picker {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            background: var(--bg3);
            border: 1px solid var(--b1);
            border-radius: var(--r-sm);
            color: var(--t2);
            font-size: 13px;
            cursor: pointer;
        }

        .date-range-picker i {
            color: var(--t3);
            font-size: 13px;
        }

        .date-range-picker select {
            background: none;
            border: none;
            outline: none;
            color: var(--t2);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        /* ─── KPI CARDS ─── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background: var(--bg2);
            border: 1px solid var(--b1);
            border-radius: var(--r);
            padding: 16px;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .kpi-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--kpi-accent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s ease;
        }

        .kpi-card:hover {
            border-color: var(--kpi-accent);
            box-shadow: 0 0 0 1px var(--kpi-accent), 0 8px 24px rgba(0, 0, 0, .12);
            transform: translateY(-2px);
        }

        .kpi-card:hover::after {
            transform: scaleX(1);
        }

        .kpi-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .kpi-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .kpi-trend {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 7px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .kpi-trend.up {
            color: #10b981;
            background: rgba(16, 185, 129, .12);
        }

        .kpi-trend.down {
            color: #ef4444;
            background: rgba(239, 68, 68, .12);
        }

        .kpi-value {
            font-size: 22px;
            font-weight: 800;
            color: var(--t1);
            letter-spacing: -.5px;
            line-height: 1;
            margin-bottom: 4px;
        }

        .kpi-label {
            font-size: 12px;
            color: var(--t3);
            font-weight: 500;
            margin-bottom: 12px;
        }

        .kpi-spark {
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 28px;
        }

        .spark-bar {
            flex: 1;
            border-radius: 2px;
            background: rgba(99, 102, 241, .2);
            transition: var(--transition);
        }

        .spark-bar.active,
        .kpi-card:hover .spark-bar {
            background: var(--kpi-accent);
            opacity: .8;
        }

        .kpi-card:hover .spark-bar.active {
            opacity: 1;
        }

        /* ─── DASHBOARD GRID ─── */
        .dash-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 16px;
        }

        .span-4 {
            grid-column: span 4;
        }

        .span-6 {
            grid-column: span 6;
        }

        .span-8 {
            grid-column: span 8;
        }

        .span-12 {
            grid-column: span 12;
        }

        /* ─── CARD ─── */
        .dash-card {
            background: var(--bg2);
            border: 1px solid var(--b1);
            border-radius: var(--r-lg);
            overflow: hidden;
            transition: var(--transition);
        }

        .dash-card:hover {
            border-color: var(--b2);
            box-shadow: var(--shadow-sm);
        }

        .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px 0;
            gap: 12px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--t1);
            letter-spacing: -.2px;
        }

        .card-sub {
            font-size: 11.5px;
            color: var(--t3);
            margin-top: 2px;
        }

        .card-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .card-body {
            padding: 14px 18px 18px;
        }

        .icon-btn-sm {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: var(--bg3);
            border: 1px solid var(--b1);
            color: var(--t3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .icon-btn-sm:hover {
            background: var(--accent-bg);
            color: var(--accent);
            border-color: var(--accent);
        }

        /* ─── BAR CHART ─── */
        .chart-legend {
            display: flex;
            gap: 16px;
            margin-bottom: 14px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--t3);
            font-weight: 500;
        }

        .l-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .bar-chart-wrap {
            display: flex;
            gap: 10px;
        }

        .y-axis {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 10px;
            color: var(--t4);
            padding-bottom: 22px;
            text-align: right;
            min-width: 28px;
        }

        .bar-chart {
            display: flex;
            gap: 8px;
            align-items: flex-end;
            flex: 1;
            height: 160px;
            position: relative;
        }

        .bar-chart::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            border-top: 1px dashed var(--b1);
            top: 0%;
        }

        .bar-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex: 1;
            height: 100%;
            justify-content: flex-end;
        }

        .bar-stack {
            display: flex;
            gap: 2px;
            align-items: flex-end;
            width: 100%;
            justify-content: center;
            height: 100%;
        }

        .bar {
            width: 8px;
            border-radius: 4px 4px 0 0;
            transition: height .4s cubic-bezier(.4, 0, .2, 1), opacity .2s;
            position: relative;
            cursor: pointer;
        }

        .bar.rev {
            background: #6366f1;
        }

        .bar.tgt {
            background: #10b981;
            opacity: .6;
        }

        .bar.exp {
            background: #f59e0b;
            opacity: .7;
        }

        .bar:hover {
            opacity: 1;
            filter: brightness(1.15);
        }

        .bar:hover::after {
            content: attr(data-val);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--bg);
            color: var(--t1);
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 5px;
            white-space: nowrap;
            margin-bottom: 4px;
            border: 1px solid var(--b2);
        }

        .bar-label {
            font-size: 10px;
            color: var(--t3);
            white-space: nowrap;
        }

        .bar-group.active .bar-label {
            color: var(--accent);
            font-weight: 700;
        }

        /* Segment control */
        .seg-control {
            display: flex;
            background: var(--bg4);
            border-radius: 8px;
            padding: 2px;
            border: 1px solid var(--b1);
        }

        .seg-btn {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: var(--t3);
            background: transparent;
            transition: var(--transition);
        }

        .seg-btn.active,
        .seg-btn:hover {
            background: var(--bg2);
            color: var(--t1);
            box-shadow: var(--shadow-sm);
        }

        .seg-btn.active {
            color: var(--accent);
        }

        /* ─── DONUT CHART ─── */
        .donut-wrap {
            display: flex;
            justify-content: center;
            padding: 8px 0 14px;
        }

        .donut-svg {
            width: 140px;
            height: 140px;
            transform: rotate(-90deg);
        }

        .donut-svg text {
            transform: rotate(90deg);
            transform-origin: 60px 60px;
        }

        .donut-legend {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .dl-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .dl-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dl-label {
            flex: 1;
            color: var(--t2);
        }

        .dl-val {
            font-weight: 700;
            color: var(--t1);
        }

        /* ─── FILTER BAR ─── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .filter-select {
            background: var(--bg3);
            border: 1px solid var(--b1);
            color: var(--t2);
            border-radius: var(--r-sm);
            padding: 6px 10px;
            font-size: 12.5px;
            font-weight: 500;
            outline: none;
            cursor: pointer;
            transition: var(--transition);
            font-family: var(--font);
        }

        .filter-select:focus,
        .filter-select:hover {
            border-color: var(--accent);
            color: var(--t1);
        }

        .search-mini {
            display: flex;
            align-items: center;
            gap: 7px;
            background: var(--bg3);
            border: 1px solid var(--b1);
            border-radius: var(--r-sm);
            padding: 6px 10px;
            transition: var(--transition);
        }

        .search-mini:focus-within {
            border-color: var(--accent);
        }

        .search-mini i {
            color: var(--t3);
            font-size: 12px;
        }

        .search-mini input {
            background: none;
            border: none;
            outline: none;
            color: var(--t1);
            font-size: 12.5px;
            width: 120px;
        }

        /* ─── DATA TABLE ─── */
        .table-wrap {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .data-table thead th {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--t3);
            padding: 10px 14px;
            border-bottom: 1px solid var(--b1);
            background: var(--bg3);
            white-space: nowrap;
            text-align: left;
        }

        .data-table thead th:first-child {
            border-radius: 0;
        }

        .data-table tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--b1);
            color: var(--t2);
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table tbody tr {
            transition: var(--transition);
        }

        .data-table tbody tr:hover td {
            background: var(--bg3);
        }

        .cb-custom {
            width: 15px;
            height: 15px;
            accent-color: var(--accent);
            cursor: pointer;
            border-radius: 4px;
        }

        .proj-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .proj-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .proj-name {
            font-weight: 600;
            color: var(--t1);
            font-size: 13px;
            white-space: nowrap;
        }

        .proj-id {
            font-size: 11px;
            color: var(--t3);
            font-family: var(--mono);
        }

        .client-cell {
            font-weight: 500;
            white-space: nowrap;
        }

        .member-cell {
            display: flex;
            align-items: center;
            gap: 7px;
            white-space: nowrap;
        }

        .mini-ava {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .mini-ava.sm {
            width: 22px;
            height: 22px;
            border-radius: 5px;
            font-size: 8px;
        }

        .tag-list {
            display: flex;
            gap: 4px;
        }

        .tech-tag {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 5px;
            white-space: nowrap;
        }

        .tech-tag.react {
            background: rgba(97, 218, 251, .12);
            color: #61dafb;
        }

        .tech-tag.node {
            background: rgba(104, 160, 99, .12);
            color: #68a063;
        }

        .tech-tag.flutter {
            background: rgba(84, 173, 219, .12);
            color: #54addb;
        }

        .tech-tag.python {
            background: rgba(55, 118, 171, .12);
            color: #3776ab;
        }

        .tech-tag.vue {
            background: rgba(65, 184, 131, .12);
            color: #41b883;
        }

        .tech-tag.laravel {
            background: rgba(255, 45, 32, .12);
            color: #ff2d20;
        }

        .tech-tag.next {
            background: rgba(255, 255, 255, .08);
            color: var(--t2);
        }

        .tech-tag.mongo {
            background: rgba(71, 162, 72, .12);
            color: #47a248;
        }

        .progress-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 100px;
        }

        .prog-bar-wrap {
            flex: 1;
            height: 5px;
            background: var(--b1);
            border-radius: 3px;
            overflow: hidden;
        }

        .prog-fill {
            height: 100%;
            border-radius: 3px;
            transition: width .5s ease;
        }

        .prog-pct {
            font-size: 11px;
            font-weight: 700;
            color: var(--t2);
            white-space: nowrap;
        }

        .date-cell {
            font-size: 12px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 5px;
            white-space: nowrap;
        }

        .date-cell.ok {
            color: #10b981;
            background: rgba(16, 185, 129, .1);
        }

        .date-cell.warn {
            color: #f59e0b;
            background: rgba(245, 158, 11, .1);
        }

        .date-cell.danger {
            color: #ef4444;
            background: rgba(239, 68, 68, .1);
        }

        .money-cell {
            font-weight: 700;
            color: var(--t1);
            font-family: var(--mono);
            font-size: 13px;
        }

        .mono {
            font-family: var(--mono);
            font-size: 12px;
            color: var(--t3);
        }

        .row-actions {
            display: flex;
            gap: 4px;
        }

        .ra-btn {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: var(--bg4);
            border: 1px solid var(--b1);
            color: var(--t3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: var(--transition);
        }

        .ra-btn:hover {
            background: var(--accent-bg);
            color: var(--accent);
            border-color: var(--accent);
        }

        .ra-btn.danger:hover {
            background: rgba(239, 68, 68, .1);
            color: #ef4444;
            border-color: #ef4444;
        }

        /* ─── STATUS PILLS ─── */
        .status-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-pill::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }

        .status-pill.inprog::before {
            background: #6366f1;
        }

        .status-pill.inprog {
            background: rgba(99, 102, 241, .12);
            color: #818cf8;
        }

        .status-pill.review::before {
            background: #06b6d4;
        }

        .status-pill.review {
            background: rgba(6, 182, 212, .12);
            color: #06b6d4;
        }

        .status-pill.delayed::before {
            background: #ef4444;
        }

        .status-pill.delayed {
            background: rgba(239, 68, 68, .12);
            color: #ef4444;
        }

        .status-pill.planning::before {
            background: #f59e0b;
        }

        .status-pill.planning {
            background: rgba(245, 158, 11, .12);
            color: #f59e0b;
        }

        .status-pill.paid::before {
            background: #10b981;
        }

        .status-pill.paid {
            background: rgba(16, 185, 129, .12);
            color: #10b981;
        }

        .status-pill.pending::before {
            background: #f59e0b;
        }

        .status-pill.pending {
            background: rgba(245, 158, 11, .12);
            color: #f59e0b;
        }

        .status-pill.overdue::before {
            background: #ef4444;
        }

        .status-pill.overdue {
            background: rgba(239, 68, 68, .12);
            color: #ef4444;
        }

        .status-pill.completed::before {
            background: #10b981;
        }

        .status-pill.completed {
            background: rgba(16, 185, 129, .12);
            color: #10b981;
        }

        /* ─── TABLE FOOTER ─── */
        .table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-top: 1px solid var(--b1);
            background: var(--bg3);
            flex-wrap: wrap;
            gap: 8px;
        }

        .tf-info {
            font-size: 12px;
            color: var(--t3);
        }

        .tf-pagination {
            display: flex;
            gap: 3px;
            align-items: center;
        }

        .pg-btn {
            min-width: 28px;
            height: 28px;
            border-radius: 7px;
            border: 1px solid var(--b1);
            background: var(--bg2);
            color: var(--t2);
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            padding: 0 6px;
        }

        .pg-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--accent-bg);
        }

        .pg-btn.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .pg-ellipsis {
            color: var(--t3);
            font-size: 12px;
            padding: 0 2px;
        }

        .tf-per-page {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--t3);
        }

        .tf-per-page select {
            background: var(--bg2);
            border: 1px solid var(--b1);
            color: var(--t2);
            border-radius: 5px;
            padding: 3px 6px;
            font-size: 12px;
            outline: none;
        }

        /* ─── LIVE CHIP ─── */
        .live-chip {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10.5px;
            font-weight: 700;
            color: #10b981;
            background: rgba(16, 185, 129, .1);
            padding: 3px 9px;
            border-radius: 20px;
            border: 1px solid rgba(16, 185, 129, .2);
        }

        .pulse-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            animation: livePulse 1.5s infinite;
        }

        @keyframes livePulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(.7);
            }
        }

        /* ─── ATTENDANCE ─── */
        .att-summary {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .att-ring-wrap {
            flex-shrink: 0;
        }

        .att-ring {
            width: 90px;
            height: 90px;
            transform: rotate(-90deg);
        }

        .att-ring text {
            transform: rotate(90deg);
            transform-origin: 40px 40px;
        }

        .att-stats {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .att-stat {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12.5px;
        }

        .as-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .as-label {
            flex: 1;
            color: var(--t3);
        }

        .att-stat strong {
            color: var(--t1);
        }

        .att-dept-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 4px;
        }

        .dept-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
        }

        .dept-name {
            width: 72px;
            color: var(--t3);
            flex-shrink: 0;
            font-weight: 500;
        }

        .dept-bar-wrap {
            flex: 1;
            height: 5px;
            background: var(--b1);
            border-radius: 3px;
            overflow: hidden;
        }

        .dept-bar {
            height: 100%;
            border-radius: 3px;
        }

        .dept-pct {
            font-size: 11px;
            font-weight: 700;
            color: var(--t2);
            min-width: 30px;
            text-align: right;
        }

        /* ─── LEAD CELLS ─── */
        .lead-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ln {
            font-weight: 600;
            color: var(--t1);
            font-size: 13px;
        }

        .ls {
            font-size: 11px;
            color: var(--t3);
        }

        .src-tag {
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 5px;
        }

        .src-tag.website {
            background: rgba(99, 102, 241, .12);
            color: #818cf8;
        }

        .src-tag.referral {
            background: rgba(16, 185, 129, .12);
            color: #10b981;
        }

        .src-tag.linkedin {
            background: rgba(10, 102, 194, .12);
            color: #0a66c2;
        }

        .lead-stage {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .lead-stage.hot {
            background: rgba(239, 68, 68, .12);
            color: #ef4444;
        }

        .lead-stage.warm {
            background: rgba(245, 158, 11, .12);
            color: #f59e0b;
        }

        .lead-stage.cold {
            background: rgba(6, 182, 212, .12);
            color: #06b6d4;
        }

        /* ─── ACTIVITY FEED ─── */
        .activity-feed {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .af-item {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .af-body {
            flex: 1;
        }

        .af-text {
            font-size: 12.5px;
            color: var(--t2);
            line-height: 1.5;
        }

        .af-text strong {
            color: var(--t1);
            font-weight: 600;
        }

        .af-link {
            color: var(--accent);
            font-weight: 600;
        }

        .af-time {
            font-size: 11px;
            color: var(--t3);
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ─── DEV VELOCITY ─── */
        .dev-velocity {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .vel-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .vel-ava {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .vel-info {
            width: 110px;
            flex-shrink: 0;
        }

        .vel-name {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--t1);
        }

        .vel-role {
            font-size: 11px;
            color: var(--t3);
        }

        .vel-bars {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .vel-bar-wrap {
            flex: 1;
            height: 5px;
            background: var(--b1);
            border-radius: 3px;
            overflow: hidden;
        }

        .vel-bar {
            height: 100%;
            border-radius: 3px;
            transition: width .5s ease;
        }

        .vel-pct {
            font-size: 11px;
            color: var(--t3);
            white-space: nowrap;
        }

        /* ─── FUNNEL ─── */
        .funnel {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .funnel-stage {
            display: flex;
            justify-content: center;
        }

        .fs-bar {
            width: var(--w);
            background: var(--col);
            border-radius: 6px;
            padding: 7px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
            cursor: pointer;
            opacity: .85;
        }

        .fs-bar:hover {
            opacity: 1;
            transform: scaleX(1.01);
        }

        .fs-label {
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, .9);
        }

        .fs-val {
            font-size: 12px;
            font-weight: 800;
            color: #fff;
            font-family: var(--mono);
        }

        .funnel-rate {
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
            color: var(--t3);
        }

        /* ─── STUB BANNER ─── */
        .stub-banner {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px;
            background: var(--bg2);
            border: 1px solid var(--b1);
            border-radius: var(--r-lg);
            border-left: 3px solid var(--accent);
        }

        .stub-banner i {
            font-size: 22px;
            color: var(--accent);
            flex-shrink: 0;
        }

        .stub-banner div {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .stub-banner strong {
            font-size: 14px;
            color: var(--t1);
        }

        .stub-banner span {
            font-size: 13px;
            color: var(--t3);
        }

        .stub-banner.info {
            border-left-color: #6366f1;
        }

        .stub-banner.info i {
            color: #6366f1;
        }

        /* ─── SETTINGS ─── */
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-row {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 14px;
        }

        .form-lbl {
            font-size: 12px;
            font-weight: 600;
            color: var(--t3);
            letter-spacing: .03em;
        }

        .form-inp {
            background: var(--bg3);
            border: 1px solid var(--b1);
            color: var(--t1);
            border-radius: var(--r-sm);
            padding: 9px 12px;
            font-size: 13.5px;
            outline: none;
            transition: var(--transition);
            width: 100%;
            font-family: var(--font);
        }

        .form-inp:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
            background: var(--bg2);
        }

        .form-inp::placeholder {
            color: var(--t4);
        }

        select.form-inp {
            cursor: pointer;
        }

        textarea.form-inp {
            resize: vertical;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 16px;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid var(--b1);
        }

        .setting-toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--b1);
            gap: 12px;
        }

        .setting-toggle-row:last-child {
            border-bottom: none;
        }

        .stl-name {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--t1);
        }

        .stl-desc {
            font-size: 12px;
            color: var(--t3);
            margin-top: 1px;
        }

        /* ─── MODALS ─── */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-backdrop.open {
            display: flex;
            animation: modalIn .2s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-box {
            background: var(--bg2);
            border: 1px solid var(--b2);
            border-radius: var(--r-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            animation: boxIn .2s cubic-bezier(.34, 1.56, .64, 1);
        }

        @keyframes boxIn {
            from {
                opacity: 0;
                transform: scale(.95)translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1)translateY(0);
            }
        }

        .modal-box.sm-box {
            max-width: 380px;
        }

        .modal-box.lg-box {
            max-width: 780px;
        }

        .modal-hd {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px 14px;
            border-bottom: 1px solid var(--b1);
            font-size: 15px;
            font-weight: 700;
            color: var(--t1);
            flex-shrink: 0;
        }

        .modal-close {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: var(--bg4);
            border: 1px solid var(--b1);
            color: var(--t3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: var(--transition);
        }

        .modal-close:hover {
            background: rgba(239, 68, 68, .1);
            color: #ef4444;
            border-color: #ef4444;
        }

        .modal-bd {
            padding: 20px 22px;
            overflow-y: auto;
            flex: 1;
        }

        .modal-ft {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            padding: 14px 22px;
            border-top: 1px solid var(--b1);
            background: var(--bg3);
            flex-shrink: 0;
            border-radius: 0 0 var(--r-lg) var(--r-lg);
        }

        /* Quick add grid */
        .quick-add-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .qa-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 18px 12px;
            border-radius: var(--r);
            background: var(--bg3);
            border: 1px solid var(--b1);
            color: var(--t2);
            font-size: 12.5px;
            font-weight: 600;
            transition: var(--transition);
        }

        .qa-btn i {
            font-size: 22px;
            color: var(--t3);
            transition: var(--transition);
        }

        .qa-btn:hover {
            background: var(--accent-bg);
            border-color: var(--accent);
            color: var(--accent);
        }

        .qa-btn:hover i {
            color: var(--accent);
        }

        /* Detail modal */
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .detail-col {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }



        .ds-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--t3);
        }

        .detail-kpis {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .dk-item {
            background: var(--bg3);
            border: 1px solid var(--b1);
            border-radius: var(--r-sm);
            padding: 10px;
            text-align: center;
        }

        .dk-val {
            font-size: 15px;
            font-weight: 800;
            color: var(--t1);
        }

        .dk-lbl {
            font-size: 10.5px;
            color: var(--t3);
            margin-top: 2px;
        }

        .member-chips {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-top: 8px;
        }

        .mc-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--t2);
        }

        .mc-role {
            font-size: 11px;
            color: var(--t3);
            background: var(--bg4);
            padding: 1px 7px;
            border-radius: 5px;
            margin-left: auto;
        }

        .mini-timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin-top: 8px;
            padding-left: 14px;
            border-left: 2px solid var(--b2);
        }

        .mt-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 6px 0;
            position: relative;
        }

        .mt-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 4px;
            position: absolute;
            left: -19px;
        }

        .mt-item.green .mt-dot {
            background: #10b981;
        }

        .mt-item.blue .mt-dot {
            background: #6366f1;
        }

        .mt-item.orange .mt-dot {
            background: #f59e0b;
        }

        .mt-item.purple .mt-dot {
            background: #8b5cf6;
        }

        .mt-text {
            font-size: 12.5px;
            color: var(--t2);
        }

        .mt-time {
            font-size: 11px;
            color: var(--t3);
        }

        /* ─── TOASTS ─── */
        .toast-stack {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 16px;
            background: var(--bg2);
            border: 1px solid var(--b2);
            border-radius: var(--r);
            box-shadow: var(--shadow-lg);
            min-width: 300px;
            cursor: pointer;
            animation: toastIn .25s cubic-bezier(.34, 1.56, .64, 1);
        }

        .toast-item.leaving {
            animation: toastOut .2s ease forwards;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(40px)scale(.95);
            }

            to {
                opacity: 1;
                transform: translateX(0)scale(1);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(40px);
            }
        }

        .toast-ico {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .toast-body {
            flex: 1;
        }

        .toast-msg {
            font-size: 13px;
            font-weight: 600;
            color: var(--t1);
        }

        .toast-sub {
            font-size: 11px;
            color: var(--t3);
            margin-top: 1px;
        }

        .toast-x {
            color: var(--t3);
            font-size: 13px;
        }

        /* ─── TOOLTIP ─── */
        [data-tooltip] {
            position: relative;
        }

        [data-tooltip]::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--t1);
            color: var(--bg2);
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity .15s;
            z-index: 9000;
        }

        [data-tooltip]:hover::before {
            opacity: 1;
        }

        .sidebar.collapsed .nav-item[data-tooltip]::before {
            left: calc(100% + 10px);
            bottom: auto;
            top: 50%;
            transform: translateY(-50%);
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 1400px) {
            .kpi-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .span-8 {
                grid-column: span 12;
            }

            .span-4 {
                grid-column: span 6;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 240px !important;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
                box-shadow: var(--shadow-lg);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-wrap {
                margin-left: 0 !important;
            }

            .mob-menu-btn {
                display: flex;
            }

            .topbar-center {
                display: none;
            }

            .tb-user-name {
                display: none;
            }

            .span-4,
            .span-6,
            .span-8,
            .span-12 {
                grid-column: span 12;
            }

            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .settings-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .kpi-grid {
                grid-template-columns: 1fr 1fr;
            }

            .page-area {
                padding: 16px 14px 32px;
            }

            .header-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .quick-add-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .detail-kpis {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- OVERLAY for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>


    @include('admin.includes.sidebar')


    <div class="main-wrap" id="mainWrap">

        <!-- TOPBAR -->
        <header class="topbar" id="topbar">
            <div class="topbar-left">
                <button class="mob-menu-btn" onclick="openSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="page-breadcrumb">
                    <span class="breadcrumb-panel" id="activePanelLabel">Admin Panel</span>
                    <i class="bi bi-chevron-right bc-sep"></i>
                    <span class="breadcrumb-page" id="activePageLabel">Services</span>
                </div>
            </div>

            <div class="topbar-center">
                <div class="global-search">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search projects, leads, orders, team…" id="globalSearch">
                    <kbd>⌘K</kbd>
                </div>
            </div>

            <div class="topbar-right">
                <div class="tb-btn" onclick="showToast('info','Syncing data...','bi-arrow-clockwise')" data-tooltip="Sync">
                    <i class="bi bi-arrow-clockwise"></i>
                </div>

                <div class="tb-btn notif-btn" data-tooltip="Notifications" onclick="toggleNotifPanel()">
                    <i class="bi bi-bell-fill"></i>
                    <span class="notif-badge">7</span>
                </div>

                <!-- Notification dropdown -->
                <div class="notif-panel" id="notifPanel">
                    <div class="notif-header">
                        <span>Notifications</span>
                        <button class="btn-xs" onclick="markAllRead()">Mark all read</button>
                    </div>
                    <div class="notif-list">
                        <div class="notif-item unread">
                            <div class="notif-icon blue"><i class="bi bi-kanban-fill"></i></div>
                            <div class="notif-body"><strong>Project Orion v2</strong> deadline in 2 days<div class="notif-time">5 min ago</div>
                            </div>
                        </div>
                        <div class="notif-item unread">
                            <div class="notif-icon green"><i class="bi bi-currency-rupee"></i></div>
                            <div class="notif-body"><strong>₹2.4L order</strong> received from TechCorp<div class="notif-time">22 min ago</div>
                            </div>
                        </div>
                        <div class="notif-item unread">
                            <div class="notif-icon orange"><i class="bi bi-person-fill"></i></div>
                            <div class="notif-body"><strong>Priya Sharma</strong> marked attendance late<div class="notif-time">1 hr ago</div>
                            </div>
                        </div>
                        <div class="notif-item">
                            <div class="notif-icon purple"><i class="bi bi-code-slash"></i></div>
                            <div class="notif-body"><strong>Sprint 12</strong> review completed<div class="notif-time">3 hrs ago</div>
                            </div>
                        </div>
                        <div class="notif-item">
                            <div class="notif-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                            <div class="notif-body"><strong>API Gateway</strong> response time elevated<div class="notif-time">Yesterday</div>
                            </div>
                        </div>
                    </div>
                    <div class="notif-footer"><a href="#">View all notifications →</a></div>
                </div>


                <div class="tb-divider"></div>
                <div class="tb-user" onclick="toggleUserMenu()">
                    <div class="user-ava sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">RK</div>
                    <span class="tb-user-name">Rahul Kumar</span>
                    <i class="bi bi-chevron-down" style="font-size:10px;color:var(--t3)"></i>
                </div>
                <div class="user-menu" id="userMenu">
                    <a class="um-item" href="{{ route('admin.account-settings') }}"><i class="bi bi-person-fill"></i> My Profile</a>
                    <hr class="um-divider">
                    
                    <!-- Logout -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>

                    <a class="um-item danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </a>
                </div>
            </div>
        </header>

        @yield('content')

    </div>






    <!-- Toast container -->
    <div class="toast-stack" id="toastStack"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* ═══════════════════════════════════════════
   ORION ERP — APP LOGIC
═══════════════════════════════════════════ */

        /* ── DATE ── */
        function updateDate() {
            const el = document.getElementById('liveDate');
            if (!el) return;
            const now = new Date();
            el.textContent = now.toLocaleDateString('en-IN', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) +
                ' · ' + now.toLocaleTimeString('en-IN', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
        }
        updateDate();
        setInterval(updateDate, 30000);

        /* ── THEME ── */
        let isDark = document.documentElement.getAttribute('data-theme') === 'dark';

        function toggleTheme() {
            isDark = !isDark;
            const theme = isDark ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            const sw = document.getElementById('themeSwitch');
            if (sw) sw.checked = isDark;
        }

        /* ── SIDEBAR ── */
        let sidebarCollapsed = false;

        function toggleSidebar() {
            sidebarCollapsed = !sidebarCollapsed;
            document.getElementById('sidebar').classList.toggle('collapsed', sidebarCollapsed);
        }

        function openSidebar() {
            document.getElementById('sidebar').classList.add('mobile-open');
            document.getElementById('sidebarOverlay').classList.add('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('mobile-open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }

        /* ── PANEL SWITCHER ── */
        const panelPageMap = {
            admin: 'dashboard',
            sales: 'sales-dash',
            dev: 'dev-dash'
        };
        const panelLabels = {
            admin: 'Admin Panel',
            sales: 'Sales Panel',
            dev: 'Developer Panel'
        };

        function switchPanel(panel, btn) {
            // Toggle nav panels
            document.querySelectorAll('.nav-panel').forEach(p => p.classList.add('hidden'));
            document.getElementById('nav-' + panel).classList.remove('hidden');

            // Toggle panel buttons
            document.querySelectorAll('.panel-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Update breadcrumb
            document.getElementById('activePanelLabel').textContent = panelLabels[panel];

            // Navigate to default page for panel
            showPage(panelPageMap[panel]);
            updateNavActive(panelPageMap[panel]);

            showToast('info', 'Switched to ' + panelLabels[panel], 'bi-grid-1x2-fill');
        }

        /* ── NAVIGATION ── */
        const pageLabels = {
            dashboard: 'Dashboard',
            analytics: 'Analytics',
            projects: 'Projects',
            leads: 'Leads',
            sales: 'Sales & Orders',
            finance: 'Finance',
            team: 'Team',
            attendance: 'Attendance',
            payroll: 'Payroll',
            reports: 'Reports',
            settings: 'Settings',
            'sales-dash': 'My Dashboard',
            'add-lead': 'Add Lead',
            'add-order': 'New Order',
            targets: 'My Targets',
            'dev-dash': 'Dev Dashboard',
            'my-projects': 'My Projects',
            tasks: 'Tasks',
            timeline: 'Timeline',
            'git-log': 'Commit Log'
        };

        function navigate(e, pageId) {
            e && e.preventDefault();
            showPage(pageId);
            updateNavActive(pageId);
            document.getElementById('activePageLabel').textContent = pageLabels[pageId] || pageId;
            if (window.innerWidth < 992) closeSidebar();
        }

        // function showPage(pageId) {
        //     document.querySelectorAll('.page').forEach(p => p.classList.add('hidden'));
        //     const target = document.getElementById('page-' + pageId);
        //     if (target) target.classList.remove('hidden');
        // }

        function updateNavActive(pageId) {
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
                const onclick = item.getAttribute('onclick') || '';
                if (onclick.includes("'" + pageId + "'")) item.classList.add('active');
            });
        }

        /* ── SEGMENT CONTROL ── */
        document.addEventListener('click', e => {
            const btn = e.target.closest('.seg-btn');
            if (btn && btn.closest('.seg-control')) {
                btn.closest('.seg-control').querySelectorAll('.seg-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            }
        });

        /* ── MODALS ── */
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) {
                m.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) {
                m.classList.remove('open');
                document.body.style.overflow = '';
            }
        }
        // Close modal on backdrop click
        document.querySelectorAll('.modal-backdrop').forEach(el => {
            el.addEventListener('click', function(e) {
                if (e.target === this) closeModal(this.id);
            });
        });
        // Escape key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-backdrop.open').forEach(m => closeModal(m.id));
                closeNotifPanel();
                closeUserMenu();
            }
            // Cmd+K search focus
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                document.querySelector('.global-search input')?.focus();
            }
        });

        /* ── NOTIFICATIONS ── */
        function toggleNotifPanel() {
            const p = document.getElementById('notifPanel');
            p.classList.toggle('open');
            closeUserMenu();
        }

        function closeNotifPanel() {
            document.getElementById('notifPanel')?.classList.remove('open');
        }

        function markAllRead() {
            document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
            const badge = document.querySelector('.notif-badge');
            if (badge) badge.style.display = 'none';
            showToast('success', 'All notifications marked as read', 'bi-check-all');
        }

        /* ── USER MENU ── */
        function toggleUserMenu() {
            const m = document.getElementById('userMenu');
            m.classList.toggle('open');
            closeNotifPanel();
        }

        function closeUserMenu() {
            document.getElementById('userMenu')?.classList.remove('open');
        }

        // Close dropdowns on outside click
        document.addEventListener('click', e => {
            if (!e.target.closest('.notif-btn') && !e.target.closest('.notif-panel')) closeNotifPanel();
            if (!e.target.closest('.tb-user') && !e.target.closest('.user-menu')) closeUserMenu();
        });

        /* ── TOASTS ── */
        const toastConfig = {
            success: {
                bg: 'rgba(16,185,129,.12)',
                border: '#10b981',
                color: '#10b981'
            },
            info: {
                bg: 'rgba(99,102,241,.12)',
                border: '#6366f1',
                color: '#6366f1'
            },
            warning: {
                bg: 'rgba(245,158,11,.12)',
                border: '#f59e0b',
                color: '#f59e0b'
            },
            danger: {
                bg: 'rgba(239,68,68,.12)',
                border: '#ef4444',
                color: '#ef4444'
            },
        };

        function showToast(type, msg, icon) {
            const cfg = toastConfig[type] || toastConfig.info;
            const container = document.getElementById('toastStack');
            const toast = document.createElement('div');
            toast.className = 'toast-item';
            toast.style.borderLeft = `3px solid ${cfg.border}`;
            toast.innerHTML = `
    <div class="toast-ico" style="background:${cfg.bg}">
      <i class="bi ${icon}" style="color:${cfg.color}"></i>
    </div>
    <div class="toast-body">
      <div class="toast-msg">${msg}</div>
      <div class="toast-sub">Just now</div>
    </div>
    <i class="bi bi-x toast-x"></i>
  `;
            toast.onclick = () => removeToast(toast);
            container.appendChild(toast);
            // Limit to 5 toasts
            const all = container.children;
            if (all.length > 5) removeToast(all[0]);
            setTimeout(() => removeToast(toast), 4500);
        }

        function removeToast(el) {
            if (!el || el.classList.contains('leaving')) return;
            el.classList.add('leaving');
            setTimeout(() => el.remove(), 220);
        }

        /* ── BAR CHART INTERACTION ── */
        document.querySelectorAll('.bar').forEach(bar => {
            bar.addEventListener('mouseenter', function() {
                const val = this.dataset.val;
                if (val) this.title = val;
            });
        });

        /* ── TABLE CHECKBOX SELECT ALL ── */
        document.querySelectorAll('thead .cb-custom').forEach(masterCb => {
            masterCb.addEventListener('change', function() {
                const table = this.closest('table');
                table.querySelectorAll('tbody .cb-custom').forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        });

        /* ── FILTER SELECT INTERACTION ── */
        document.querySelectorAll('.filter-select').forEach(sel => {
            sel.addEventListener('change', function() {
                if (this.value !== this.options[0].value) {
                    showToast('info', `Filter: ${this.value}`, 'bi-funnel-fill');
                }
            });
        });

        /* ── MINI SEARCH ── */
        document.querySelectorAll('.search-mini input').forEach(inp => {
            let timer;
            inp.addEventListener('input', function() {
                clearTimeout(timer);
                if (this.value.length > 2) {
                    timer = setTimeout(() => {
                        // Visual feedback only
                    }, 400);
                }
            });
        });

        /* ── KEYBOARD SHORTCUTS HINT ── */
        document.querySelector('.global-search input')?.addEventListener('focus', function() {
            this.placeholder = 'Type to search…';
        });
        document.querySelector('.global-search input')?.addEventListener('blur', function() {
            this.placeholder = 'Search projects, leads, orders, team…';
        });

        /* ── ANIMATE PROGRESS BARS ON LOAD ── */
        function animateBars() {
            document.querySelectorAll('.prog-fill, .dept-bar, .vel-bar').forEach(bar => {
                const target = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = target;
                }, 200);
            });
        }
        setTimeout(animateBars, 300);

        /* ── INITIAL STATE ── */
        document.addEventListener('DOMContentLoaded', () => {
            showPage('dashboard');
            updateNavActive('dashboard');

            // Set initial theme switch state
            const sw = document.getElementById('themeSwitch');
            if (sw) sw.checked = (document.documentElement.getAttribute('data-theme') === 'dark');
        });
    </script>
</body>

</html>