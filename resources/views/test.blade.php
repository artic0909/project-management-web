<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orion ERP — Enterprise Management Platform</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
/* ═══════════════════════════════════════════
   ORION ERP — DESIGN SYSTEM
   Font: Plus Jakarta Sans + JetBrains Mono
═══════════════════════════════════════════ */

:root {
  --font: 'Plus Jakarta Sans', sans-serif;
  --mono: 'JetBrains Mono', monospace;
  --r: 10px;
  --r-sm: 7px;
  --r-lg: 16px;
  --transition: all .18s cubic-bezier(.4,0,.2,1);
  --shadow-sm: 0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.08);
  --shadow-md: 0 4px 16px rgba(0,0,0,.18),0 1px 4px rgba(0,0,0,.1);
  --shadow-lg: 0 24px 64px rgba(0,0,0,.32),0 4px 16px rgba(0,0,0,.16);
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
  --accent-bg: rgba(99,102,241,.12);
  --accent-glow: 0 0 24px rgba(99,102,241,.25);
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
  --accent-bg: rgba(91,95,240,.1);
  --accent-glow: 0 0 24px rgba(91,95,240,.18);
  --sidebar-w: 240px;
  --topbar-h: 58px;
}

/* ─── RESET ─── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: var(--font); background: var(--bg); color: var(--t1); font-size: 14px; line-height: 1.5; overflow-x: hidden; transition: background .25s, color .25s; }
a { text-decoration: none; color: inherit; }
button { cursor: pointer; border: none; background: none; font-family: var(--font); }
input, select, textarea { font-family: var(--font); }
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--b2); border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: var(--b3); }
::selection { background: rgba(99,102,241,.3); }

/* ─── LAYOUT ─── */
.sidebar {
  position: fixed; left: 0; top: 0; width: var(--sidebar-w); height: 100vh;
  background: var(--bg2); border-right: 1px solid var(--b1);
  display: flex; flex-direction: column; z-index: 200;
  transition: width .22s cubic-bezier(.4,0,.2,1), transform .22s cubic-bezier(.4,0,.2,1);
  overflow: hidden;
}
.sidebar.collapsed { width: 64px; }
.sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 199; backdrop-filter: blur(2px); }

.main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; transition: margin-left .22s cubic-bezier(.4,0,.2,1); display: flex; flex-direction: column; }
.sidebar.collapsed ~ .main-wrap { margin-left: 64px; }

/* ─── SIDEBAR HEADER ─── */
.sidebar-header {
  height: var(--topbar-h); padding: 0 14px; display: flex; align-items: center;
  justify-content: space-between; border-bottom: 1px solid var(--b1);
  flex-shrink: 0;
}
.brand { display: flex; align-items: center; gap: 10px; overflow: hidden; }
.brand-icon {
  width: 32px; height: 32px; flex-shrink: 0; background: var(--accent);
  border-radius: 9px; display: flex; align-items: center; justify-content: center;
  color: #fff; box-shadow: var(--accent-glow);
}
.brand-text { overflow: hidden; white-space: nowrap; }
.brand-name { font-size: 15px; font-weight: 800; color: var(--t1); display: block; line-height: 1.2; letter-spacing: -.3px; }
.brand-sub { font-size: 10px; color: var(--t3); font-weight: 500; }
.sidebar-collapse-btn {
  width: 28px; height: 28px; border-radius: 7px; background: var(--bg3);
  border: 1px solid var(--b1); color: var(--t3); display: flex;
  align-items: center; justify-content: center; font-size: 13px;
  flex-shrink: 0; transition: var(--transition);
}
.sidebar-collapse-btn:hover { background: var(--accent-bg); color: var(--accent); border-color: var(--accent); }
.sidebar.collapsed .brand-text,
.sidebar.collapsed .brand-sub { display: none; }
.sidebar.collapsed .sidebar-collapse-btn { margin: 0 auto; }

/* ─── PANEL SWITCHER ─── */
.panel-switcher {
  display: flex; gap: 4px; padding: 10px 10px 6px;
  border-bottom: 1px solid var(--b1); flex-shrink: 0;
}
.panel-btn {
  flex: 1; padding: 7px 4px; border-radius: var(--r-sm);
  background: transparent; color: var(--t3);
  display: flex; flex-direction: column; align-items: center; gap: 3px;
  font-size: 10px; font-weight: 600; transition: var(--transition);
  position: relative;
}
.panel-btn i { font-size: 15px; }
.panel-btn span { white-space: nowrap; overflow: hidden; }
.panel-btn:hover { background: var(--bg4); color: var(--t2); }
.panel-btn.active { background: var(--accent-bg); color: var(--accent); }
.sidebar.collapsed .panel-btn span { display: none; }

/* ─── SIDEBAR NAV ─── */
.sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px 8px; }
.nav-panel { display: flex; flex-direction: column; gap: 1px; }
.nav-panel.hidden { display: none; }
.nav-section-label {
  font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;
  color: var(--t4); padding: 12px 8px 4px; white-space: nowrap;
}
.sidebar.collapsed .nav-section-label { display: none; }
.nav-item {
  display: flex; align-items: center; gap: 9px; padding: 8px 10px;
  border-radius: var(--r-sm); color: var(--t3); font-size: 13.5px; font-weight: 500;
  transition: var(--transition); position: relative; white-space: nowrap;
}
.nav-item i { font-size: 16px; flex-shrink: 0; width: 18px; text-align: center; }
.nav-item:hover { background: var(--bg4); color: var(--t1); }
.nav-item.active { background: var(--accent-bg); color: var(--accent); font-weight: 600; }
.nav-item.active::before { content: ''; position: absolute; left: 0; top: 20%; bottom: 20%; width: 3px; background: var(--accent); border-radius: 0 3px 3px 0; left: -8px; }
.nav-badge {
  margin-left: auto; font-size: 9px; font-weight: 700; padding: 2px 6px;
  border-radius: 20px; background: var(--accent); color: #fff; flex-shrink: 0;
}
.nav-badge.pulse { animation: badgePulse 2s infinite; }
@keyframes badgePulse { 0%,100% { opacity:1; } 50% { opacity:.6; } }
.nav-count {
  margin-left: auto; font-size: 11px; font-weight: 600; color: var(--t3);
  background: var(--bg4); padding: 1px 7px; border-radius: 20px;
  border: 1px solid var(--b1);
}
.nav-dot { margin-left: auto; width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.nav-dot.green { background: #10b981; box-shadow: 0 0 6px rgba(16,185,129,.5); }
.sidebar.collapsed .nav-item span,
.sidebar.collapsed .nav-badge,
.sidebar.collapsed .nav-count,
.sidebar.collapsed .nav-dot { display: none; }
.sidebar.collapsed .nav-item { padding: 10px; justify-content: center; }

/* ─── SIDEBAR FOOTER ─── */
.sidebar-footer {
  padding: 10px; border-top: 1px solid var(--b1); flex-shrink: 0;
}
.theme-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 6px 6px 8px; color: var(--t3); font-size: 12px; font-weight: 500;
}
.theme-label { display: flex; align-items: center; gap: 6px; }
.sidebar.collapsed .theme-row { display: none; }
.user-profile {
  display: flex; align-items: center; gap: 9px; padding: 8px;
  border-radius: var(--r-sm); cursor: pointer; transition: var(--transition);
  position: relative;
}
.user-profile:hover { background: var(--bg4); }
.user-info { overflow: hidden; flex: 1; }
.user-name { font-size: 13px; font-weight: 600; color: var(--t1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-role { font-size: 10.5px; color: var(--t3); }
.user-status-dot {
  width: 8px; height: 8px; border-radius: 50%; background: #10b981;
  box-shadow: 0 0 6px rgba(16,185,129,.6); flex-shrink: 0;
}
.sidebar.collapsed .user-info,
.sidebar.collapsed .user-status-dot { display: none; }
.sidebar.collapsed .user-profile { justify-content: center; }

/* ─── TOGGLE SWITCH ─── */
.toggle-switch { position: relative; flex-shrink: 0; }
.toggle-switch input { display: none; }
.toggle-track {
  display: block; width: 36px; height: 20px; border-radius: 20px;
  background: var(--b2); cursor: pointer; transition: var(--transition);
  position: relative;
}
.toggle-thumb {
  position: absolute; width: 14px; height: 14px; border-radius: 50%;
  background: var(--t3); top: 3px; left: 3px; transition: var(--transition);
}
.toggle-switch input:checked + .toggle-track { background: var(--accent); }
.toggle-switch input:checked + .toggle-track .toggle-thumb { left: 19px; background: #fff; }

/* ─── USER AVATAR ─── */
.user-ava {
  width: 34px; height: 34px; border-radius: 9px; display: flex;
  align-items: center; justify-content: center; font-size: 12px;
  font-weight: 700; color: #fff; flex-shrink: 0;
}
.user-ava.sm { width: 28px; height: 28px; border-radius: 7px; font-size: 10px; }

/* ─── TOPBAR ─── */
.topbar {
  height: var(--topbar-h); background: var(--bg2); border-bottom: 1px solid var(--b1);
  display: flex; align-items: center; padding: 0 20px; gap: 12px;
  position: sticky; top: 0; z-index: 100;
}
.topbar-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
.topbar-center { flex: 1; max-width: 480px; }
.topbar-right { display: flex; align-items: center; gap: 6px; margin-left: auto; }

.mob-menu-btn { display: none; width: 32px; height: 32px; border-radius: var(--r-sm); background: var(--bg4); color: var(--t2); align-items: center; justify-content: center; font-size: 16px; }

.page-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; }
.breadcrumb-panel { color: var(--t3); font-weight: 500; }
.bc-sep { color: var(--t4); font-size: 10px; }
.breadcrumb-page { color: var(--t1); font-weight: 600; }

.global-search {
  display: flex; align-items: center; gap: 10px;
  background: var(--bg3); border: 1px solid var(--b1);
  border-radius: var(--r); padding: 8px 14px;
  transition: var(--transition);
}
.global-search:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,.12); background: var(--bg2); }
.global-search i { color: var(--t3); font-size: 13px; flex-shrink: 0; }
.global-search input { background: none; border: none; outline: none; color: var(--t1); font-size: 13.5px; width: 100%; }
.global-search input::placeholder { color: var(--t3); }
.global-search kbd { font-family: var(--mono); font-size: 10px; color: var(--t4); background: var(--bg4); border: 1px solid var(--b2); border-radius: 4px; padding: 1px 5px; flex-shrink: 0; }

.tb-btn {
  width: 34px; height: 34px; border-radius: var(--r-sm); background: var(--bg3);
  border: 1px solid var(--b1); display: flex; align-items: center; justify-content: center;
  color: var(--t2); cursor: pointer; transition: var(--transition); position: relative;
  font-size: 14px;
}
.tb-btn:hover { background: var(--accent-bg); border-color: var(--accent); color: var(--accent); }
.notif-btn { position: relative; }
.notif-badge {
  position: absolute; top: -3px; right: -3px; width: 16px; height: 16px;
  background: #ef4444; border-radius: 50%; font-size: 9px; font-weight: 700;
  color: #fff; display: flex; align-items: center; justify-content: center;
  border: 2px solid var(--bg2);
}
.tb-divider { width: 1px; height: 24px; background: var(--b1); margin: 0 2px; }
.tb-user {
  display: flex; align-items: center; gap: 8px; padding: 4px 10px 4px 4px;
  border-radius: var(--r-sm); cursor: pointer; transition: var(--transition);
  border: 1px solid transparent;
}
.tb-user:hover { background: var(--bg3); border-color: var(--b1); }
.tb-user-name { font-size: 13px; font-weight: 600; color: var(--t1); white-space: nowrap; }

/* ─── DROPDOWNS ─── */
.notif-panel {
  position: absolute; top: calc(var(--topbar-h) + 6px); right: 60px;
  width: 360px; background: var(--bg2); border: 1px solid var(--b2);
  border-radius: var(--r-lg); box-shadow: var(--shadow-lg); z-index: 500;
  display: none; overflow: hidden;
}
.notif-panel.open { display: block; animation: dropIn .18s ease; }
@keyframes dropIn { from { opacity:0;transform:translateY(-8px); } to { opacity:1;transform:translateY(0); } }
.notif-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px 12px; border-bottom: 1px solid var(--b1); }
.notif-header span { font-weight: 700; font-size: 14px; }
.btn-xs { font-size: 11px; color: var(--accent); font-weight: 600; background: var(--accent-bg); border: none; padding: 3px 9px; border-radius: 6px; cursor: pointer; }
.notif-list { max-height: 320px; overflow-y: auto; }
.notif-item {
  display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px;
  border-bottom: 1px solid var(--b1); cursor: pointer; transition: var(--transition);
  position: relative;
}
.notif-item:hover { background: var(--bg3); }
.notif-item.unread { background: var(--accent-bg); }
.notif-item.unread::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: var(--accent); }
.notif-icon {
  width: 34px; height: 34px; border-radius: 9px; display: flex;
  align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;
}
.notif-icon.blue { background: rgba(99,102,241,.15); color: #6366f1; }
.notif-icon.green { background: rgba(16,185,129,.15); color: #10b981; }
.notif-icon.orange { background: rgba(245,158,11,.15); color: #f59e0b; }
.notif-icon.purple { background: rgba(139,92,246,.15); color: #8b5cf6; }
.notif-icon.red { background: rgba(239,68,68,.15); color: #ef4444; }
.notif-body { flex: 1; font-size: 12.5px; color: var(--t2); line-height: 1.4; }
.notif-body strong { color: var(--t1); }
.notif-time { font-size: 11px; color: var(--t3); margin-top: 2px; }
.notif-footer { text-align: center; padding: 10px; border-top: 1px solid var(--b1); font-size: 12.5px; color: var(--accent); font-weight: 600; }

.user-menu {
  position: absolute; top: calc(var(--topbar-h) + 6px); right: 16px;
  width: 200px; background: var(--bg2); border: 1px solid var(--b2);
  border-radius: var(--r); box-shadow: var(--shadow-lg); z-index: 500;
  padding: 6px; display: none;
}
.user-menu.open { display: block; animation: dropIn .18s ease; }
.um-item { display: flex; align-items: center; gap: 9px; padding: 9px 12px; border-radius: var(--r-sm); font-size: 13px; font-weight: 500; color: var(--t2); transition: var(--transition); }
.um-item:hover { background: var(--bg4); color: var(--t1); }
.um-item.danger:hover { background: rgba(239,68,68,.1); color: #ef4444; }
.um-item i { width: 15px; font-size: 13px; }
.um-divider { border: none; border-top: 1px solid var(--b1); margin: 4px 0; }

/* ─── PAGE AREA ─── */
.page-area { flex: 1; padding: 24px 24px 40px; overflow: auto; }
.page { animation: pageIn .2s ease; }
@keyframes pageIn { from { opacity:0;transform:translateY(6px); } to { opacity:1;transform:translateY(0); } }
.page.hidden { display: none; }

/* ─── PAGE HEADER ─── */
.page-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  flex-wrap: wrap; gap: 14px; margin-bottom: 24px;
}
.page-title { font-size: 22px; font-weight: 800; color: var(--t1); letter-spacing: -.4px; line-height: 1.2; }
.page-desc { font-size: 13px; color: var(--t3); margin-top: 3px; font-weight: 400; }
.header-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

/* ─── BUTTONS ─── */
.btn-primary-solid {
  background: var(--accent); color: #fff; border: none;
  padding: 8px 16px; border-radius: var(--r-sm); font-size: 13px;
  font-weight: 600; display: flex; align-items: center; gap: 6px;
  transition: var(--transition); white-space: nowrap;
}
.btn-primary-solid:hover { background: var(--accent2); box-shadow: var(--accent-glow); transform: translateY(-1px); }
.btn-primary-solid.sm { padding: 6px 12px; font-size: 12px; }
.btn-ghost {
  background: transparent; color: var(--t2); border: 1px solid var(--b2);
  padding: 8px 14px; border-radius: var(--r-sm); font-size: 13px;
  font-weight: 500; display: flex; align-items: center; gap: 6px;
  transition: var(--transition); white-space: nowrap;
}
.btn-ghost:hover { background: var(--bg4); border-color: var(--b3); color: var(--t1); }
.btn-ghost.danger-ghost:hover { background: rgba(239,68,68,.1); border-color: #ef4444; color: #ef4444; }
.btn-full-outline {
  width: 100%; margin-top: 14px; padding: 9px; border-radius: var(--r-sm);
  background: transparent; border: 1px solid var(--b2); color: var(--t2);
  font-size: 12.5px; font-weight: 600; display: flex; align-items: center;
  justify-content: center; gap: 6px; transition: var(--transition);
}
.btn-full-outline:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-bg); }

/* ─── DATE RANGE PICKER ─── */
.date-range-picker {
  display: flex; align-items: center; gap: 8px; padding: 7px 12px;
  background: var(--bg3); border: 1px solid var(--b1); border-radius: var(--r-sm);
  color: var(--t2); font-size: 13px; cursor: pointer;
}
.date-range-picker i { color: var(--t3); font-size: 13px; }
.date-range-picker select { background: none; border: none; outline: none; color: var(--t2); font-size: 13px; font-weight: 500; cursor: pointer; }

/* ─── KPI CARDS ─── */
.kpi-grid {
  display: grid; grid-template-columns: repeat(6, 1fr); gap: 14px; margin-bottom: 20px;
}
.kpi-card {
  background: var(--bg2); border: 1px solid var(--b1); border-radius: var(--r);
  padding: 16px; transition: var(--transition); cursor: pointer;
  position: relative; overflow: hidden;
}
.kpi-card::after {
  content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
  background: var(--kpi-accent); transform: scaleX(0); transform-origin: left;
  transition: transform .3s ease;
}
.kpi-card:hover { border-color: var(--kpi-accent); box-shadow: 0 0 0 1px var(--kpi-accent), 0 8px 24px rgba(0,0,0,.12); transform: translateY(-2px); }
.kpi-card:hover::after { transform: scaleX(1); }
.kpi-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
.kpi-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 17px; }
.kpi-trend { font-size: 11px; font-weight: 700; padding: 3px 7px; border-radius: 20px; display: flex; align-items: center; gap: 2px; }
.kpi-trend.up { color: #10b981; background: rgba(16,185,129,.12); }
.kpi-trend.down { color: #ef4444; background: rgba(239,68,68,.12); }
.kpi-value { font-size: 22px; font-weight: 800; color: var(--t1); letter-spacing: -.5px; line-height: 1; margin-bottom: 4px; }
.kpi-label { font-size: 12px; color: var(--t3); font-weight: 500; margin-bottom: 12px; }
.kpi-spark { display: flex; align-items: flex-end; gap: 2px; height: 28px; }
.spark-bar {
  flex: 1; border-radius: 2px; background: rgba(99,102,241,.2);
  transition: var(--transition);
}
.spark-bar.active,
.kpi-card:hover .spark-bar { background: var(--kpi-accent); opacity: .8; }
.kpi-card:hover .spark-bar.active { opacity: 1; }

/* ─── DASHBOARD GRID ─── */
.dash-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 16px; }
.span-4 { grid-column: span 4; }
.span-6 { grid-column: span 6; }
.span-8 { grid-column: span 8; }
.span-12 { grid-column: span 12; }

/* ─── CARD ─── */
.dash-card {
  background: var(--bg2); border: 1px solid var(--b1); border-radius: var(--r-lg);
  overflow: hidden; transition: var(--transition);
}
.dash-card:hover { border-color: var(--b2); box-shadow: var(--shadow-sm); }
.card-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 18px 0; gap: 12px;
}
.card-title { font-size: 14px; font-weight: 700; color: var(--t1); letter-spacing: -.2px; }
.card-sub { font-size: 11.5px; color: var(--t3); margin-top: 2px; }
.card-actions { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.card-body { padding: 14px 18px 18px; }
.icon-btn-sm {
  width: 28px; height: 28px; border-radius: 7px; background: var(--bg3);
  border: 1px solid var(--b1); color: var(--t3); display: flex;
  align-items: center; justify-content: center; font-size: 13px;
  transition: var(--transition); flex-shrink: 0;
}
.icon-btn-sm:hover { background: var(--accent-bg); color: var(--accent); border-color: var(--accent); }

/* ─── BAR CHART ─── */
.chart-legend { display: flex; gap: 16px; margin-bottom: 14px; }
.legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--t3); font-weight: 500; }
.l-dot { width: 8px; height: 8px; border-radius: 50%; }
.bar-chart-wrap { display: flex; gap: 10px; }
.y-axis { display: flex; flex-direction: column; justify-content: space-between; font-size: 10px; color: var(--t4); padding-bottom: 22px; text-align: right; min-width: 28px; }
.bar-chart { display: flex; gap: 8px; align-items: flex-end; flex: 1; height: 160px; position: relative; }
.bar-chart::before {
  content: ''; position: absolute; left: 0; right: 0;
  border-top: 1px dashed var(--b1);
  top: 0%;
}
.bar-group { display: flex; flex-direction: column; align-items: center; gap: 4px; flex: 1; height: 100%; justify-content: flex-end; }
.bar-stack { display: flex; gap: 2px; align-items: flex-end; width: 100%; justify-content: center; height: 100%; }
.bar { width: 8px; border-radius: 4px 4px 0 0; transition: height .4s cubic-bezier(.4,0,.2,1), opacity .2s; position: relative; cursor: pointer; }
.bar.rev { background: #6366f1; }
.bar.tgt { background: #10b981; opacity: .6; }
.bar.exp { background: #f59e0b; opacity: .7; }
.bar:hover { opacity: 1; filter: brightness(1.15); }
.bar:hover::after { content: attr(data-val); position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); background: var(--bg); color: var(--t1); font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 5px; white-space: nowrap; margin-bottom: 4px; border: 1px solid var(--b2); }
.bar-label { font-size: 10px; color: var(--t3); white-space: nowrap; }
.bar-group.active .bar-label { color: var(--accent); font-weight: 700; }

/* Segment control */
.seg-control { display: flex; background: var(--bg4); border-radius: 8px; padding: 2px; border: 1px solid var(--b1); }
.seg-btn { padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; color: var(--t3); background: transparent; transition: var(--transition); }
.seg-btn.active, .seg-btn:hover { background: var(--bg2); color: var(--t1); box-shadow: var(--shadow-sm); }
.seg-btn.active { color: var(--accent); }

/* ─── DONUT CHART ─── */
.donut-wrap { display: flex; justify-content: center; padding: 8px 0 14px; }
.donut-svg { width: 140px; height: 140px; transform: rotate(-90deg); }
.donut-svg text { transform: rotate(90deg); transform-origin: 60px 60px; }
.donut-legend { display: flex; flex-direction: column; gap: 8px; }
.dl-item { display: flex; align-items: center; gap: 8px; font-size: 13px; }
.dl-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.dl-label { flex: 1; color: var(--t2); }
.dl-val { font-weight: 700; color: var(--t1); }

/* ─── FILTER BAR ─── */
.filter-bar { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.filter-select {
  background: var(--bg3); border: 1px solid var(--b1); color: var(--t2);
  border-radius: var(--r-sm); padding: 6px 10px; font-size: 12.5px;
  font-weight: 500; outline: none; cursor: pointer; transition: var(--transition);
  font-family: var(--font);
}
.filter-select:focus, .filter-select:hover { border-color: var(--accent); color: var(--t1); }
.search-mini {
  display: flex; align-items: center; gap: 7px; background: var(--bg3);
  border: 1px solid var(--b1); border-radius: var(--r-sm); padding: 6px 10px;
  transition: var(--transition);
}
.search-mini:focus-within { border-color: var(--accent); }
.search-mini i { color: var(--t3); font-size: 12px; }
.search-mini input { background: none; border: none; outline: none; color: var(--t1); font-size: 12.5px; width: 120px; }

/* ─── DATA TABLE ─── */
.table-wrap { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.data-table thead th {
  font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
  color: var(--t3); padding: 10px 14px; border-bottom: 1px solid var(--b1);
  background: var(--bg3); white-space: nowrap; text-align: left;
}
.data-table thead th:first-child { border-radius: 0; }
.data-table tbody td { padding: 11px 14px; border-bottom: 1px solid var(--b1); color: var(--t2); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr { transition: var(--transition); }
.data-table tbody tr:hover td { background: var(--bg3); }
.cb-custom { width: 15px; height: 15px; accent-color: var(--accent); cursor: pointer; border-radius: 4px; }

.proj-cell { display: flex; align-items: center; gap: 10px; }
.proj-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 800; flex-shrink: 0; }
.proj-name { font-weight: 600; color: var(--t1); font-size: 13px; white-space: nowrap; }
.proj-id { font-size: 11px; color: var(--t3); font-family: var(--mono); }
.client-cell { font-weight: 500; white-space: nowrap; }
.member-cell { display: flex; align-items: center; gap: 7px; white-space: nowrap; }
.mini-ava { width: 26px; height: 26px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; color: #fff; flex-shrink: 0; }
.mini-ava.sm { width: 22px; height: 22px; border-radius: 5px; font-size: 8px; }

.tag-list { display: flex; gap: 4px; }
.tech-tag { font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 5px; white-space: nowrap; }
.tech-tag.react { background: rgba(97,218,251,.12); color: #61dafb; }
.tech-tag.node { background: rgba(104,160,99,.12); color: #68a063; }
.tech-tag.flutter { background: rgba(84,173,219,.12); color: #54addb; }
.tech-tag.python { background: rgba(55,118,171,.12); color: #3776ab; }
.tech-tag.vue { background: rgba(65,184,131,.12); color: #41b883; }
.tech-tag.laravel { background: rgba(255,45,32,.12); color: #ff2d20; }
.tech-tag.next { background: rgba(255,255,255,.08); color: var(--t2); }
.tech-tag.mongo { background: rgba(71,162,72,.12); color: #47a248; }

.progress-cell { display: flex; align-items: center; gap: 8px; min-width: 100px; }
.prog-bar-wrap { flex: 1; height: 5px; background: var(--b1); border-radius: 3px; overflow: hidden; }
.prog-fill { height: 100%; border-radius: 3px; transition: width .5s ease; }
.prog-pct { font-size: 11px; font-weight: 700; color: var(--t2); white-space: nowrap; }

.date-cell { font-size: 12px; font-weight: 600; padding: 3px 8px; border-radius: 5px; white-space: nowrap; }
.date-cell.ok { color: #10b981; background: rgba(16,185,129,.1); }
.date-cell.warn { color: #f59e0b; background: rgba(245,158,11,.1); }
.date-cell.danger { color: #ef4444; background: rgba(239,68,68,.1); }

.money-cell { font-weight: 700; color: var(--t1); font-family: var(--mono); font-size: 13px; }
.mono { font-family: var(--mono); font-size: 12px; color: var(--t3); }

.row-actions { display: flex; gap: 4px; }
.ra-btn {
  width: 28px; height: 28px; border-radius: 7px; background: var(--bg4);
  border: 1px solid var(--b1); color: var(--t3); display: flex;
  align-items: center; justify-content: center; font-size: 12px;
  transition: var(--transition);
}
.ra-btn:hover { background: var(--accent-bg); color: var(--accent); border-color: var(--accent); }
.ra-btn.danger:hover { background: rgba(239,68,68,.1); color: #ef4444; border-color: #ef4444; }

/* ─── STATUS PILLS ─── */
.status-pill {
  font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 20px;
  white-space: nowrap; display: inline-flex; align-items: center; gap: 4px;
}
.status-pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; }
.status-pill.inprog::before { background: #6366f1; }
.status-pill.inprog { background: rgba(99,102,241,.12); color: #818cf8; }
.status-pill.review::before { background: #06b6d4; }
.status-pill.review { background: rgba(6,182,212,.12); color: #06b6d4; }
.status-pill.delayed::before { background: #ef4444; }
.status-pill.delayed { background: rgba(239,68,68,.12); color: #ef4444; }
.status-pill.planning::before { background: #f59e0b; }
.status-pill.planning { background: rgba(245,158,11,.12); color: #f59e0b; }
.status-pill.paid::before { background: #10b981; }
.status-pill.paid { background: rgba(16,185,129,.12); color: #10b981; }
.status-pill.pending::before { background: #f59e0b; }
.status-pill.pending { background: rgba(245,158,11,.12); color: #f59e0b; }
.status-pill.overdue::before { background: #ef4444; }
.status-pill.overdue { background: rgba(239,68,68,.12); color: #ef4444; }
.status-pill.completed::before { background: #10b981; }
.status-pill.completed { background: rgba(16,185,129,.12); color: #10b981; }

/* ─── TABLE FOOTER ─── */
.table-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding: 10px 14px; border-top: 1px solid var(--b1);
  background: var(--bg3); flex-wrap: wrap; gap: 8px;
}
.tf-info { font-size: 12px; color: var(--t3); }
.tf-pagination { display: flex; gap: 3px; align-items: center; }
.pg-btn {
  min-width: 28px; height: 28px; border-radius: 7px; border: 1px solid var(--b1);
  background: var(--bg2); color: var(--t2); font-size: 12px; font-weight: 600;
  display: flex; align-items: center; justify-content: center;
  transition: var(--transition); padding: 0 6px;
}
.pg-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-bg); }
.pg-btn.active { background: var(--accent); border-color: var(--accent); color: #fff; }
.pg-ellipsis { color: var(--t3); font-size: 12px; padding: 0 2px; }
.tf-per-page { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--t3); }
.tf-per-page select { background: var(--bg2); border: 1px solid var(--b1); color: var(--t2); border-radius: 5px; padding: 3px 6px; font-size: 12px; outline: none; }

/* ─── LIVE CHIP ─── */
.live-chip {
  display: flex; align-items: center; gap: 5px; font-size: 10.5px; font-weight: 700;
  color: #10b981; background: rgba(16,185,129,.1); padding: 3px 9px;
  border-radius: 20px; border: 1px solid rgba(16,185,129,.2);
}
.pulse-dot {
  width: 6px; height: 6px; border-radius: 50%; background: #10b981;
  animation: livePulse 1.5s infinite;
}
@keyframes livePulse { 0%,100% { opacity:1;transform:scale(1); } 50% { opacity:.5;transform:scale(.7); } }

/* ─── ATTENDANCE ─── */
.att-summary { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
.att-ring-wrap { flex-shrink: 0; }
.att-ring { width: 90px; height: 90px; transform: rotate(-90deg); }
.att-ring text { transform: rotate(90deg); transform-origin: 40px 40px; }
.att-stats { display: flex; flex-direction: column; gap: 6px; }
.att-stat { display: flex; align-items: center; gap: 7px; font-size: 12.5px; }
.as-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.as-label { flex: 1; color: var(--t3); }
.att-stat strong { color: var(--t1); }
.att-dept-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 4px; }
.dept-row { display: flex; align-items: center; gap: 8px; font-size: 12px; }
.dept-name { width: 72px; color: var(--t3); flex-shrink: 0; font-weight: 500; }
.dept-bar-wrap { flex: 1; height: 5px; background: var(--b1); border-radius: 3px; overflow: hidden; }
.dept-bar { height: 100%; border-radius: 3px; }
.dept-pct { font-size: 11px; font-weight: 700; color: var(--t2); min-width: 30px; text-align: right; }

/* ─── LEAD CELLS ─── */
.lead-cell { display: flex; align-items: center; gap: 8px; }
.ln { font-weight: 600; color: var(--t1); font-size: 13px; }
.ls { font-size: 11px; color: var(--t3); }
.src-tag { font-size: 10.5px; font-weight: 700; padding: 2px 7px; border-radius: 5px; }
.src-tag.website { background: rgba(99,102,241,.12); color: #818cf8; }
.src-tag.referral { background: rgba(16,185,129,.12); color: #10b981; }
.src-tag.linkedin { background: rgba(10,102,194,.12); color: #0a66c2; }
.lead-stage { font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 20px; }
.lead-stage.hot { background: rgba(239,68,68,.12); color: #ef4444; }
.lead-stage.warm { background: rgba(245,158,11,.12); color: #f59e0b; }
.lead-stage.cold { background: rgba(6,182,212,.12); color: #06b6d4; }

/* ─── ACTIVITY FEED ─── */
.activity-feed { display: flex; flex-direction: column; gap: 14px; }
.af-item { display: flex; gap: 10px; align-items: flex-start; }
.af-body { flex: 1; }
.af-text { font-size: 12.5px; color: var(--t2); line-height: 1.5; }
.af-text strong { color: var(--t1); font-weight: 600; }
.af-link { color: var(--accent); font-weight: 600; }
.af-time { font-size: 11px; color: var(--t3); margin-top: 2px; display: flex; align-items: center; gap: 4px; }

/* ─── DEV VELOCITY ─── */
.dev-velocity { display: flex; flex-direction: column; gap: 12px; }
.vel-item { display: flex; align-items: center; gap: 10px; }
.vel-ava { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0; }
.vel-info { width: 110px; flex-shrink: 0; }
.vel-name { font-size: 12.5px; font-weight: 600; color: var(--t1); }
.vel-role { font-size: 11px; color: var(--t3); }
.vel-bars { display: flex; align-items: center; gap: 8px; flex: 1; }
.vel-bar-wrap { flex: 1; height: 5px; background: var(--b1); border-radius: 3px; overflow: hidden; }
.vel-bar { height: 100%; border-radius: 3px; transition: width .5s ease; }
.vel-pct { font-size: 11px; color: var(--t3); white-space: nowrap; }

/* ─── FUNNEL ─── */
.funnel { display: flex; flex-direction: column; gap: 6px; }
.funnel-stage { display: flex; justify-content: center; }
.fs-bar {
  width: var(--w); background: var(--col); border-radius: 6px;
  padding: 7px 14px; display: flex; justify-content: space-between;
  align-items: center; transition: var(--transition); cursor: pointer;
  opacity: .85;
}
.fs-bar:hover { opacity: 1; transform: scaleX(1.01); }
.fs-label { font-size: 12px; font-weight: 600; color: rgba(255,255,255,.9); }
.fs-val { font-size: 12px; font-weight: 800; color: #fff; font-family: var(--mono); }
.funnel-rate { text-align: center; margin-top: 12px; font-size: 13px; color: var(--t3); }

/* ─── STUB BANNER ─── */
.stub-banner {
  display: flex; align-items: center; gap: 14px; padding: 20px;
  background: var(--bg2); border: 1px solid var(--b1); border-radius: var(--r-lg);
  border-left: 3px solid var(--accent);
}
.stub-banner i { font-size: 22px; color: var(--accent); flex-shrink: 0; }
.stub-banner div { display: flex; flex-direction: column; gap: 3px; }
.stub-banner strong { font-size: 14px; color: var(--t1); }
.stub-banner span { font-size: 13px; color: var(--t3); }
.stub-banner.info { border-left-color: #6366f1; }
.stub-banner.info i { color: #6366f1; }

/* ─── SETTINGS ─── */
.settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-row { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
.form-lbl { font-size: 12px; font-weight: 600; color: var(--t3); letter-spacing: .03em; }
.form-inp {
  background: var(--bg3); border: 1px solid var(--b1); color: var(--t1);
  border-radius: var(--r-sm); padding: 9px 12px; font-size: 13.5px;
  outline: none; transition: var(--transition); width: 100%;
  font-family: var(--font);
}
.form-inp:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,.1); background: var(--bg2); }
.form-inp::placeholder { color: var(--t4); }
select.form-inp { cursor: pointer; }
textarea.form-inp { resize: vertical; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px; }
.form-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--b1); }
.setting-toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--b1); gap: 12px; }
.setting-toggle-row:last-child { border-bottom: none; }
.stl-name { font-size: 13.5px; font-weight: 600; color: var(--t1); }
.stl-desc { font-size: 12px; color: var(--t3); margin-top: 1px; }

/* ─── MODALS ─── */
.modal-backdrop {
  position: fixed; inset: 0; background: rgba(0,0,0,.6); backdrop-filter: blur(4px);
  z-index: 1000; display: none; align-items: center; justify-content: center; padding: 20px;
}
.modal-backdrop.open { display: flex; animation: modalIn .2s ease; }
@keyframes modalIn { from { opacity:0; } to { opacity:1; } }
.modal-box {
  background: var(--bg2); border: 1px solid var(--b2); border-radius: var(--r-lg);
  box-shadow: var(--shadow-lg); width: 100%; max-width: 560px;
  max-height: 90vh; display: flex; flex-direction: column;
  animation: boxIn .2s cubic-bezier(.34,1.56,.64,1);
}
@keyframes boxIn { from { opacity:0;transform:scale(.95)translateY(10px); } to { opacity:1;transform:scale(1)translateY(0); } }
.modal-box.sm-box { max-width: 380px; }
.modal-box.lg-box { max-width: 780px; }
.modal-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 22px 14px; border-bottom: 1px solid var(--b1);
  font-size: 15px; font-weight: 700; color: var(--t1); flex-shrink: 0;
}
.modal-close {
  width: 28px; height: 28px; border-radius: 7px; background: var(--bg4);
  border: 1px solid var(--b1); color: var(--t3); display: flex;
  align-items: center; justify-content: center; font-size: 12px;
  transition: var(--transition);
}
.modal-close:hover { background: rgba(239,68,68,.1); color: #ef4444; border-color: #ef4444; }
.modal-bd { padding: 20px 22px; overflow-y: auto; flex: 1; }
.modal-ft {
  display: flex; gap: 8px; justify-content: flex-end; padding: 14px 22px;
  border-top: 1px solid var(--b1); background: var(--bg3); flex-shrink: 0;
  border-radius: 0 0 var(--r-lg) var(--r-lg);
}

/* Quick add grid */
.quick-add-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.qa-btn {
  display: flex; flex-direction: column; align-items: center; gap: 8px;
  padding: 18px 12px; border-radius: var(--r); background: var(--bg3);
  border: 1px solid var(--b1); color: var(--t2); font-size: 12.5px; font-weight: 600;
  transition: var(--transition);
}
.qa-btn i { font-size: 22px; color: var(--t3); transition: var(--transition); }
.qa-btn:hover { background: var(--accent-bg); border-color: var(--accent); color: var(--accent); }
.qa-btn:hover i { color: var(--accent); }

/* Detail modal */
.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.detail-col { display: flex; flex-direction: column; gap: 16px; }
.detail-section { }
.ds-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--t3); }
.detail-kpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
.dk-item { background: var(--bg3); border: 1px solid var(--b1); border-radius: var(--r-sm); padding: 10px; text-align: center; }
.dk-val { font-size: 15px; font-weight: 800; color: var(--t1); }
.dk-lbl { font-size: 10.5px; color: var(--t3); margin-top: 2px; }
.member-chips { display: flex; flex-direction: column; gap: 6px; margin-top: 8px; }
.mc-item { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--t2); }
.mc-role { font-size: 11px; color: var(--t3); background: var(--bg4); padding: 1px 7px; border-radius: 5px; margin-left: auto; }
.mini-timeline { display: flex; flex-direction: column; gap: 0; margin-top: 8px; padding-left: 14px; border-left: 2px solid var(--b2); }
.mt-item { display: flex; align-items: flex-start; gap: 8px; padding: 6px 0; position: relative; }
.mt-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; position: absolute; left: -19px; }
.mt-item.green .mt-dot { background: #10b981; }
.mt-item.blue .mt-dot { background: #6366f1; }
.mt-item.orange .mt-dot { background: #f59e0b; }
.mt-item.purple .mt-dot { background: #8b5cf6; }
.mt-text { font-size: 12.5px; color: var(--t2); }
.mt-time { font-size: 11px; color: var(--t3); }

/* ─── TOASTS ─── */
.toast-stack {
  position: fixed; bottom: 24px; right: 24px; z-index: 9999;
  display: flex; flex-direction: column; gap: 8px;
}
.toast-item {
  display: flex; align-items: center; gap: 12px; padding: 13px 16px;
  background: var(--bg2); border: 1px solid var(--b2); border-radius: var(--r);
  box-shadow: var(--shadow-lg); min-width: 300px; cursor: pointer;
  animation: toastIn .25s cubic-bezier(.34,1.56,.64,1);
}
.toast-item.leaving { animation: toastOut .2s ease forwards; }
@keyframes toastIn { from { opacity:0;transform:translateX(40px)scale(.95); } to { opacity:1;transform:translateX(0)scale(1); } }
@keyframes toastOut { from { opacity:1;transform:translateX(0); } to { opacity:0;transform:translateX(40px); } }
.toast-ico { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.toast-body { flex: 1; }
.toast-msg { font-size: 13px; font-weight: 600; color: var(--t1); }
.toast-sub { font-size: 11px; color: var(--t3); margin-top: 1px; }
.toast-x { color: var(--t3); font-size: 13px; }

/* ─── TOOLTIP ─── */
[data-tooltip] { position: relative; }
[data-tooltip]::before {
  content: attr(data-tooltip); position: absolute; bottom: calc(100% + 8px);
  left: 50%; transform: translateX(-50%); background: var(--t1); color: var(--bg2);
  font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px;
  white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity .15s;
  z-index: 9000;
}
[data-tooltip]:hover::before { opacity: 1; }
.sidebar.collapsed .nav-item[data-tooltip]::before { left: calc(100% + 10px); bottom: auto; top: 50%; transform: translateY(-50%); }

/* ─── RESPONSIVE ─── */
@media (max-width: 1400px) {
  .kpi-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 1200px) {
  .span-8 { grid-column: span 12; }
  .span-4 { grid-column: span 6; }
}
@media (max-width: 992px) {
  .sidebar { transform: translateX(-100%); width: 240px !important; }
  .sidebar.mobile-open { transform: translateX(0); box-shadow: var(--shadow-lg); }
  .sidebar-overlay.show { display: block; }
  .main-wrap { margin-left: 0 !important; }
  .mob-menu-btn { display: flex; }
  .topbar-center { display: none; }
  .tb-user-name { display: none; }
  .span-4, .span-6, .span-8, .span-12 { grid-column: span 12; }
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
  .detail-grid { grid-template-columns: 1fr; }
  .settings-grid { grid-template-columns: 1fr; }
}
@media (max-width: 576px) {
  .kpi-grid { grid-template-columns: 1fr 1fr; }
  .page-area { padding: 16px 14px 32px; }
  .header-actions { flex-direction: column; align-items: flex-start; }
  .quick-add-grid { grid-template-columns: repeat(2, 1fr); }
  .detail-kpis { grid-template-columns: repeat(2,1fr); }
  .form-grid { grid-template-columns: 1fr; }
}

</style>
</head>
<body>

<!-- OVERLAY for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ═══════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="brand">
      <div class="brand-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div class="brand-text">
        <span class="brand-name">Orion</span>
        <span class="brand-sub">ERP Platform</span>
      </div>
    </div>
    <button class="sidebar-collapse-btn" onclick="toggleSidebar()" id="sidebarToggle">
      <i class="bi bi-layout-sidebar-reverse"></i>
    </button>
  </div>

  <!-- Panel Switcher -->
  <div class="panel-switcher">
    <button class="panel-btn active" onclick="switchPanel('admin',this)" data-tooltip="Admin Panel">
      <i class="bi bi-shield-fill"></i><span>Admin</span>
    </button>
    <button class="panel-btn" onclick="switchPanel('sales',this)" data-tooltip="Sales Panel">
      <i class="bi bi-graph-up-arrow"></i><span>Sales</span>
    </button>
    <button class="panel-btn" onclick="switchPanel('dev',this)" data-tooltip="Dev Panel">
      <i class="bi bi-code-slash"></i><span>Dev</span>
    </button>
  </div>

  <nav class="sidebar-nav" id="sidebarNav">
    <!-- ADMIN NAV -->
    <div class="nav-panel" id="nav-admin">
      <div class="nav-section-label">Overview</div>
      <a class="nav-item active" href="#" onclick="navigate(event,'dashboard')">
        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
        <span class="nav-badge pulse">Live</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'analytics')">
        <i class="bi bi-activity"></i><span>Analytics</span>
      </a>

      <div class="nav-section-label">Business</div>
      <a class="nav-item" href="#" onclick="navigate(event,'projects')">
        <i class="bi bi-kanban-fill"></i><span>Projects</span>
        <span class="nav-count">24</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'leads')">
        <i class="bi bi-person-lines-fill"></i><span>Leads</span>
        <span class="nav-count">147</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'sales')">
        <i class="bi bi-bag-check-fill"></i><span>Sales & Orders</span>
        <span class="nav-count">38</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'finance')">
        <i class="bi bi-currency-rupee"></i><span>Finance</span>
      </a>

      <div class="nav-section-label">People</div>
      <a class="nav-item" href="#" onclick="navigate(event,'team')">
        <i class="bi bi-people-fill"></i><span>Team</span>
        <span class="nav-count">52</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'attendance')">
        <i class="bi bi-clock-history"></i><span>Attendance</span>
        <div class="nav-dot green"></div>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'payroll')">
        <i class="bi bi-wallet2"></i><span>Payroll</span>
      </a>

      <div class="nav-section-label">System</div>
      <a class="nav-item" href="#" onclick="navigate(event,'reports')">
        <i class="bi bi-file-earmark-bar-graph-fill"></i><span>Reports</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'settings')">
        <i class="bi bi-gear-fill"></i><span>Settings</span>
      </a>
    </div>

    <!-- SALES NAV -->
    <div class="nav-panel hidden" id="nav-sales">
      <div class="nav-section-label">Sales Desk</div>
      <a class="nav-item active" href="#" onclick="navigate(event,'sales-dash')">
        <i class="bi bi-speedometer2"></i><span>My Dashboard</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'leads')">
        <i class="bi bi-person-lines-fill"></i><span>My Leads</span>
        <span class="nav-count">28</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'add-lead')">
        <i class="bi bi-person-plus-fill"></i><span>Add Lead</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'sales')">
        <i class="bi bi-bag-check-fill"></i><span>Orders</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'add-order')">
        <i class="bi bi-plus-circle-fill"></i><span>New Order</span>
      </a>
      <div class="nav-section-label">Personal</div>
      <a class="nav-item" href="#" onclick="navigate(event,'attendance')">
        <i class="bi bi-clock-history"></i><span>My Attendance</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'targets')">
        <i class="bi bi-bullseye"></i><span>My Targets</span>
      </a>
    </div>

    <!-- DEV NAV -->
    <div class="nav-panel hidden" id="nav-dev">
      <div class="nav-section-label">Developer Desk</div>
      <a class="nav-item active" href="#" onclick="navigate(event,'dev-dash')">
        <i class="bi bi-terminal-fill"></i><span>My Dashboard</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'my-projects')">
        <i class="bi bi-kanban-fill"></i><span>My Projects</span>
        <span class="nav-count">6</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'tasks')">
        <i class="bi bi-check2-square"></i><span>Tasks</span>
        <span class="nav-count">14</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'timeline')">
        <i class="bi bi-calendar3"></i><span>Timeline</span>
      </a>
      <div class="nav-section-label">Personal</div>
      <a class="nav-item" href="#" onclick="navigate(event,'attendance')">
        <i class="bi bi-clock-history"></i><span>My Attendance</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'git-log')">
        <i class="bi bi-git"></i><span>Commit Log</span>
      </a>
    </div>
  </nav>

  <div class="sidebar-footer">
    <div class="theme-row">
      <span class="theme-label"><i class="bi bi-moon-stars-fill"></i> Dark Mode</span>
      <label class="toggle-switch">
        <input type="checkbox" id="themeSwitch" onchange="toggleTheme()" checked>
        <span class="toggle-track"><span class="toggle-thumb"></span></span>
      </label>
    </div>
    <div class="user-profile">
      <div class="user-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">RK</div>
      <div class="user-info">
        <div class="user-name">Rahul Kumar</div>
        <div class="user-role">Super Admin</div>
      </div>
      <div class="user-status-dot"></div>
    </div>
  </div>
</aside>

<!-- ═══════════════════════════════════════════
     MAIN WRAPPER
════════════════════════════════════════════ -->
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
        <span class="breadcrumb-page" id="activePageLabel">Dashboard</span>
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
            <div class="notif-body"><strong>Project Orion v2</strong> deadline in 2 days<div class="notif-time">5 min ago</div></div>
          </div>
          <div class="notif-item unread">
            <div class="notif-icon green"><i class="bi bi-currency-rupee"></i></div>
            <div class="notif-body"><strong>₹2.4L order</strong> received from TechCorp<div class="notif-time">22 min ago</div></div>
          </div>
          <div class="notif-item unread">
            <div class="notif-icon orange"><i class="bi bi-person-fill"></i></div>
            <div class="notif-body"><strong>Priya Sharma</strong> marked attendance late<div class="notif-time">1 hr ago</div></div>
          </div>
          <div class="notif-item">
            <div class="notif-icon purple"><i class="bi bi-code-slash"></i></div>
            <div class="notif-body"><strong>Sprint 12</strong> review completed<div class="notif-time">3 hrs ago</div></div>
          </div>
          <div class="notif-item">
            <div class="notif-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="notif-body"><strong>API Gateway</strong> response time elevated<div class="notif-time">Yesterday</div></div>
          </div>
        </div>
        <div class="notif-footer"><a href="#">View all notifications →</a></div>
      </div>

      <div class="tb-btn" data-tooltip="Messages">
        <i class="bi bi-chat-dots-fill"></i>
      </div>
      <div class="tb-divider"></div>
      <div class="tb-user" onclick="toggleUserMenu()">
        <div class="user-ava sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">RK</div>
        <span class="tb-user-name">Rahul Kumar</span>
        <i class="bi bi-chevron-down" style="font-size:10px;color:var(--t3)"></i>
      </div>
      <div class="user-menu" id="userMenu">
        <a class="um-item" href="#"><i class="bi bi-person-fill"></i> My Profile</a>
        <a class="um-item" href="#" onclick="navigate(event,'settings')"><i class="bi bi-gear-fill"></i> Settings</a>
        <a class="um-item" href="#"><i class="bi bi-shield-check"></i> Security</a>
        <hr class="um-divider">
        <a class="um-item danger" href="#"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
      </div>
    </div>
  </header>

  <!-- ═══ PAGE CONTENT AREA ═══ -->
  <main class="page-area" id="pageArea">

    <!-- ══════════════════════════════════
         ADMIN DASHBOARD PAGE
    ══════════════════════════════════ -->
    <div class="page" id="page-dashboard">

      <!-- Page Header -->
      <div class="page-header">
        <div>
          <h1 class="page-title">Command Center</h1>
          <p class="page-desc">Live overview · <span id="liveDate"></span></p>
        </div>
        <div class="header-actions">
          <button class="btn-ghost"><i class="bi bi-sliders2"></i> Filters</button>
          <div class="date-range-picker">
            <i class="bi bi-calendar3"></i>
            <select onchange="showToast('info','Date range updated','bi-calendar3')">
              <option>This Month</option>
              <option>Last 7 Days</option>
              <option>Last Quarter</option>
              <option>This Year</option>
            </select>
          </div>
          <button class="btn-ghost"><i class="bi bi-download"></i> Export</button>
          <button class="btn-primary-solid" onclick="openModal('quickAddModal')">
            <i class="bi bi-plus-lg"></i> Quick Add
          </button>
        </div>
      </div>

      <!-- KPI STRIP -->
      <div class="kpi-grid">
        <div class="kpi-card" style="--kpi-accent:#6366f1">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-currency-rupee"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 18.4%</span>
          </div>
          <div class="kpi-value">₹48.6L</div>
          <div class="kpi-label">Total Revenue</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:40%"></div><div class="spark-bar" style="height:60%"></div><div class="spark-bar" style="height:45%"></div><div class="spark-bar" style="height:75%"></div><div class="spark-bar" style="height:55%"></div><div class="spark-bar" style="height:90%"></div><div class="spark-bar active" style="height:100%"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#10b981">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-bag-check-fill"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 12.1%</span>
          </div>
          <div class="kpi-value">238</div>
          <div class="kpi-label">Orders Closed</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:30%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:50%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:65%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:45%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:80%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:60%;--kpi-accent:#10b981"></div><div class="spark-bar active" style="height:90%;--kpi-accent:#10b981"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#f59e0b">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-person-lines-fill"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 9.8%</span>
          </div>
          <div class="kpi-value">1,247</div>
          <div class="kpi-label">Total Leads</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:55%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:70%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:50%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:85%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:60%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:75%;--kpi-accent:#f59e0b"></div><div class="spark-bar active" style="height:95%;--kpi-accent:#f59e0b"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#8b5cf6">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6"><i class="bi bi-kanban-fill"></i></div>
            <span class="kpi-trend down"><i class="bi bi-arrow-down-right"></i> 2.3%</span>
          </div>
          <div class="kpi-value">24</div>
          <div class="kpi-label">Active Projects</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:80%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:65%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:90%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:70%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:55%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:85%;--kpi-accent:#8b5cf6"></div><div class="spark-bar active" style="height:75%;--kpi-accent:#8b5cf6"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#ef4444">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(239,68,68,.15);color:#ef4444"><i class="bi bi-people-fill"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 4.2%</span>
          </div>
          <div class="kpi-value">52</div>
          <div class="kpi-label">Team Members</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:50%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:55%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:60%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:60%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:65%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:70%;--kpi-accent:#ef4444"></div><div class="spark-bar active" style="height:75%;--kpi-accent:#ef4444"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#06b6d4">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i class="bi bi-clock-fill"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 6.7%</span>
          </div>
          <div class="kpi-value">94.2%</div>
          <div class="kpi-label">Attendance Rate</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:88%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:91%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:89%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:93%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:90%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:95%;--kpi-accent:#06b6d4"></div><div class="spark-bar active" style="height:94%;--kpi-accent:#06b6d4"></div>
          </div>
        </div>
      </div>

      <!-- MAIN GRID -->
      <div class="dash-grid">

        <!-- Revenue Chart -->
        <div class="dash-card span-8">
          <div class="card-head">
            <div>
              <div class="card-title">Revenue vs Target</div>
              <div class="card-sub">Monthly comparison · FY 2024–25</div>
            </div>
            <div class="card-actions">
              <div class="seg-control">
                <button class="seg-btn active">Monthly</button>
                <button class="seg-btn">Quarterly</button>
                <button class="seg-btn">Yearly</button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-legend">
              <span class="legend-item"><span class="l-dot" style="background:#6366f1"></span>Revenue</span>
              <span class="legend-item"><span class="l-dot" style="background:#10b981"></span>Target</span>
              <span class="legend-item"><span class="l-dot" style="background:#f59e0b"></span>Expenses</span>
            </div>
            <div class="bar-chart-wrap">
              <div class="y-axis">
                <span>50L</span><span>40L</span><span>30L</span><span>20L</span><span>10L</span><span>0</span>
              </div>
              <div class="bar-chart">
                <div class="bar-group" data-month="Apr">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:62%" data-val="₹31L"></div>
                    <div class="bar tgt" style="height:70%" data-val="₹35L"></div>
                    <div class="bar exp" style="height:38%" data-val="₹19L"></div>
                  </div>
                  <div class="bar-label">Apr</div>
                </div>
                <div class="bar-group" data-month="May">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:75%" data-val="₹37.5L"></div>
                    <div class="bar tgt" style="height:70%" data-val="₹35L"></div>
                    <div class="bar exp" style="height:42%" data-val="₹21L"></div>
                  </div>
                  <div class="bar-label">May</div>
                </div>
                <div class="bar-group" data-month="Jun">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:58%" data-val="₹29L"></div>
                    <div class="bar tgt" style="height:72%" data-val="₹36L"></div>
                    <div class="bar exp" style="height:35%" data-val="₹17.5L"></div>
                  </div>
                  <div class="bar-label">Jun</div>
                </div>
                <div class="bar-group" data-month="Jul">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:80%" data-val="₹40L"></div>
                    <div class="bar tgt" style="height:74%" data-val="₹37L"></div>
                    <div class="bar exp" style="height:45%" data-val="₹22.5L"></div>
                  </div>
                  <div class="bar-label">Jul</div>
                </div>
                <div class="bar-group" data-month="Aug">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:88%" data-val="₹44L"></div>
                    <div class="bar tgt" style="height:76%" data-val="₹38L"></div>
                    <div class="bar exp" style="height:50%" data-val="₹25L"></div>
                  </div>
                  <div class="bar-label">Aug</div>
                </div>
                <div class="bar-group" data-month="Sep">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:72%" data-val="₹36L"></div>
                    <div class="bar tgt" style="height:78%" data-val="₹39L"></div>
                    <div class="bar exp" style="height:40%" data-val="₹20L"></div>
                  </div>
                  <div class="bar-label">Sep</div>
                </div>
                <div class="bar-group" data-month="Oct">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:95%" data-val="₹47.5L"></div>
                    <div class="bar tgt" style="height:80%" data-val="₹40L"></div>
                    <div class="bar exp" style="height:52%" data-val="₹26L"></div>
                  </div>
                  <div class="bar-label">Oct</div>
                </div>
                <div class="bar-group active" data-month="Nov">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:97%;background:linear-gradient(180deg,#818cf8,#6366f1)" data-val="₹48.6L"></div>
                    <div class="bar tgt" style="height:82%" data-val="₹41L"></div>
                    <div class="bar exp" style="height:48%" data-val="₹24L"></div>
                  </div>
                  <div class="bar-label">Nov ●</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Pipeline Donut -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Project Pipeline</div>
            <button class="icon-btn-sm"><i class="bi bi-three-dots"></i></button>
          </div>
          <div class="card-body">
            <div class="donut-wrap">
              <svg viewBox="0 0 120 120" class="donut-svg">
                <circle cx="60" cy="60" r="50" fill="none" stroke="var(--b2)" stroke-width="18"/>
                <circle cx="60" cy="60" r="50" fill="none" stroke="#6366f1" stroke-width="18" stroke-dasharray="94.2 220.8" stroke-dashoffset="0" stroke-linecap="round"/>
                <circle cx="60" cy="60" r="50" fill="none" stroke="#10b981" stroke-width="18" stroke-dasharray="66 248.9" stroke-dashoffset="-94.2" stroke-linecap="round"/>
                <circle cx="60" cy="60" r="50" fill="none" stroke="#f59e0b" stroke-width="18" stroke-dasharray="44 271" stroke-dashoffset="-160.2" stroke-linecap="round"/>
                <circle cx="60" cy="60" r="50" fill="none" stroke="#ef4444" stroke-width="18" stroke-dasharray="30 285" stroke-dashoffset="-204.2" stroke-linecap="round"/>
                <text x="60" y="56" text-anchor="middle" fill="var(--t1)" font-size="14" font-weight="700" font-family="Plus Jakarta Sans">24</text>
                <text x="60" y="68" text-anchor="middle" fill="var(--t3)" font-size="6" font-family="Plus Jakarta Sans">Projects</text>
              </svg>
            </div>
            <div class="donut-legend">
              <div class="dl-item"><span class="dl-dot" style="background:#6366f1"></span><span class="dl-label">In Progress</span><span class="dl-val">10</span></div>
              <div class="dl-item"><span class="dl-dot" style="background:#10b981"></span><span class="dl-label">Completed</span><span class="dl-val">7</span></div>
              <div class="dl-item"><span class="dl-dot" style="background:#f59e0b"></span><span class="dl-label">Review</span><span class="dl-val">5</span></div>
              <div class="dl-item"><span class="dl-dot" style="background:#ef4444"></span><span class="dl-label">Delayed</span><span class="dl-val">2</span></div>
            </div>
          </div>
        </div>

        <!-- Active Projects Table -->
        <div class="dash-card span-8">
          <div class="card-head">
            <div>
              <div class="card-title">Active Projects</div>
              <div class="card-sub">Showing 5 of 24 active</div>
            </div>
            <div class="card-actions">
              <div class="filter-bar">
                <select class="filter-select"><option>All Status</option><option>In Progress</option><option>Review</option><option>Delayed</option></select>
                <select class="filter-select"><option>All Teams</option><option>Frontend</option><option>Backend</option><option>Mobile</option></select>
                <div class="search-mini"><i class="bi bi-search"></i><input type="text" placeholder="Search…"></div>
              </div>
              <button class="btn-primary-solid sm" onclick="openModal('addProjectModal')"><i class="bi bi-plus-lg"></i> New</button>
            </div>
          </div>
          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th><input type="checkbox" class="cb-custom"></th>
                  <th>Project</th>
                  <th>Client</th>
                  <th>Team Lead</th>
                  <th>Stack</th>
                  <th>Progress</th>
                  <th>Deadline</th>
                  <th>Status</th>
                  <th>Budget</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(99,102,241,.15);color:#6366f1">OR</div><div><div class="proj-name">Orion E-Commerce</div><div class="proj-id">#PRJ-001</div></div></div></td>
                  <td><div class="client-cell">TechCorp Pvt Ltd</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">AK</div>Arjun Kumar</div></td>
                  <td><div class="tag-list"><span class="tech-tag react">React</span><span class="tech-tag node">Node</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:78%;background:#6366f1"></div></div><span class="prog-pct">78%</span></div></td>
                  <td><span class="date-cell warn">Dec 15</span></td>
                  <td><span class="status-pill inprog">In Progress</span></td>
                  <td><span class="money-cell">₹8.5L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(16,185,129,.15);color:#10b981">FM</div><div><div class="proj-name">FinanceMe App</div><div class="proj-id">#PRJ-002</div></div></div></td>
                  <td><div class="client-cell">Nexus Finance</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4)">PS</div>Priya Sharma</div></td>
                  <td><div class="tag-list"><span class="tech-tag flutter">Flutter</span><span class="tech-tag python">Python</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:94%;background:#10b981"></div></div><span class="prog-pct">94%</span></div></td>
                  <td><span class="date-cell ok">Nov 30</span></td>
                  <td><span class="status-pill review">In Review</span></td>
                  <td><span class="money-cell">₹12.2L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(239,68,68,.15);color:#ef4444">HR</div><div><div class="proj-name">HR Portal</div><div class="proj-id">#PRJ-003</div></div></div></td>
                  <td><div class="client-cell">InternalOps</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">RV</div>Ravi Verma</div></td>
                  <td><div class="tag-list"><span class="tech-tag vue">Vue</span><span class="tech-tag laravel">Laravel</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:42%;background:#ef4444"></div></div><span class="prog-pct">42%</span></div></td>
                  <td><span class="date-cell danger">Nov 20</span></td>
                  <td><span class="status-pill delayed">Delayed</span></td>
                  <td><span class="money-cell">₹4.8L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(6,182,212,.15);color:#06b6d4">SC</div><div><div class="proj-name">SmartCart PWA</div><div class="proj-id">#PRJ-004</div></div></div></td>
                  <td><div class="client-cell">RetailMax India</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#06b6d4,#6366f1)">NK</div>Neha Kapoor</div></td>
                  <td><div class="tag-list"><span class="tech-tag next">Next.js</span><span class="tech-tag mongo">MongoDB</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:61%;background:#06b6d4"></div></div><span class="prog-pct">61%</span></div></td>
                  <td><span class="date-cell ok">Jan 10</span></td>
                  <td><span class="status-pill inprog">In Progress</span></td>
                  <td><span class="money-cell">₹6.9L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(245,158,11,.15);color:#f59e0b">AI</div><div><div class="proj-name">AI Analytics Suite</div><div class="proj-id">#PRJ-005</div></div></div></td>
                  <td><div class="client-cell">DataFirst Corp</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">SM</div>Sunita Mehta</div></td>
                  <td><div class="tag-list"><span class="tech-tag python">Python</span><span class="tech-tag react">React</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:28%;background:#f59e0b"></div></div><span class="prog-pct">28%</span></div></td>
                  <td><span class="date-cell ok">Feb 28</span></td>
                  <td><span class="status-pill planning">Planning</span></td>
                  <td><span class="money-cell">₹18.0L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="table-footer">
            <span class="tf-info">Showing 5 of 24 projects</span>
            <div class="tf-pagination">
              <button class="pg-btn"><i class="bi bi-chevron-double-left"></i></button>
              <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
              <button class="pg-btn active">1</button>
              <button class="pg-btn">2</button>
              <button class="pg-btn">3</button>
              <span class="pg-ellipsis">…</span>
              <button class="pg-btn">5</button>
              <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
              <button class="pg-btn"><i class="bi bi-chevron-double-right"></i></button>
            </div>
            <div class="tf-per-page">
              <span>Rows:</span>
              <select><option>5</option><option>10</option><option>25</option></select>
            </div>
          </div>
        </div>

        <!-- Attendance Today + Sales Pipeline -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Today's Attendance</div>
            <span class="live-chip"><span class="pulse-dot"></span>Live</span>
          </div>
          <div class="card-body">
            <div class="att-summary">
              <div class="att-ring-wrap">
                <svg viewBox="0 0 80 80" class="att-ring">
                  <circle cx="40" cy="40" r="32" fill="none" stroke="var(--b2)" stroke-width="8"/>
                  <circle cx="40" cy="40" r="32" fill="none" stroke="#10b981" stroke-width="8" stroke-dasharray="176 25" stroke-dashoffset="50" stroke-linecap="round"/>
                  <text x="40" y="37" text-anchor="middle" fill="var(--t1)" font-size="12" font-weight="700" font-family="Plus Jakarta Sans">47</text>
                  <text x="40" y="48" text-anchor="middle" fill="var(--t3)" font-size="5.5" font-family="Plus Jakarta Sans">of 52</text>
                </svg>
              </div>
              <div class="att-stats">
                <div class="att-stat"><span class="as-dot" style="background:#10b981"></span><span class="as-label">Present</span><strong>47</strong></div>
                <div class="att-stat"><span class="as-dot" style="background:#ef4444"></span><span class="as-label">Absent</span><strong>3</strong></div>
                <div class="att-stat"><span class="as-dot" style="background:#f59e0b"></span><span class="as-label">Late</span><strong>2</strong></div>
                <div class="att-stat"><span class="as-dot" style="background:#8b5cf6"></span><span class="as-label">WFH</span><strong>5</strong></div>
              </div>
            </div>
            <div class="att-dept-list">
              <div class="dept-row"><span class="dept-name">Sales Team</span><div class="dept-bar-wrap"><div class="dept-bar" style="width:88%;background:#6366f1"></div></div><span class="dept-pct">88%</span></div>
              <div class="dept-row"><span class="dept-name">Dev Team</span><div class="dept-bar-wrap"><div class="dept-bar" style="width:95%;background:#10b981"></div></div><span class="dept-pct">95%</span></div>
              <div class="dept-row"><span class="dept-name">Design</span><div class="dept-bar-wrap"><div class="dept-bar" style="width:100%;background:#f59e0b"></div></div><span class="dept-pct">100%</span></div>
              <div class="dept-row"><span class="dept-name">HR/Ops</span><div class="dept-bar-wrap"><div class="dept-bar" style="width:75%;background:#06b6d4"></div></div><span class="dept-pct">75%</span></div>
            </div>
            <button class="btn-full-outline" onclick="navigate(event,'attendance')">View Full Report <i class="bi bi-arrow-right"></i></button>
          </div>
        </div>

        <!-- Recent Leads -->
        <div class="dash-card span-6">
          <div class="card-head">
            <div>
              <div class="card-title">Lead Pipeline</div>
              <div class="card-sub">147 total · 38 hot leads</div>
            </div>
            <div class="card-actions">
              <select class="filter-select"><option>All Sources</option><option>Website</option><option>Referral</option><option>LinkedIn</option></select>
              <button class="btn-primary-solid sm" onclick="openModal('addLeadModal')"><i class="bi bi-plus-lg"></i> Lead</button>
            </div>
          </div>
          <div class="table-wrap">
            <table class="data-table">
              <thead><tr><th>Lead</th><th>Source</th><th>Value</th><th>Stage</th><th>Owner</th><th>Action</th></tr></thead>
              <tbody>
                <tr>
                  <td><div class="lead-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">TC</div><div><div class="ln">TechCorp Solutions</div><div class="ls">techcorp@example.com</div></div></div></td>
                  <td><span class="src-tag website">Website</span></td>
                  <td><strong style="color:#10b981">₹18L</strong></td>
                  <td><span class="lead-stage hot">Hot 🔥</span></td>
                  <td><div class="mini-ava sm" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">RK</div></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-telephone-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="lead-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4)">NF</div><div><div class="ln">Nexus Finance</div><div class="ls">info@nexusfinance.in</div></div></div></td>
                  <td><span class="src-tag referral">Referral</span></td>
                  <td><strong style="color:#10b981">₹24L</strong></td>
                  <td><span class="lead-stage warm">Warm</span></td>
                  <td><div class="mini-ava sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">PS</div></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-telephone-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="lead-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">RM</div><div><div class="ln">RetailMax India</div><div class="ls">sales@retailmax.com</div></div></div></td>
                  <td><span class="src-tag linkedin">LinkedIn</span></td>
                  <td><strong style="color:#10b981">₹9.5L</strong></td>
                  <td><span class="lead-stage cold">Cold</span></td>
                  <td><div class="mini-ava sm" style="background:linear-gradient(135deg,#06b6d4,#6366f1)">NK</div></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-telephone-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="lead-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">DF</div><div><div class="ln">DataFirst Corp</div><div class="ls">cto@datafirst.io</div></div></div></td>
                  <td><span class="src-tag website">Website</span></td>
                  <td><strong style="color:#10b981">₹32L</strong></td>
                  <td><span class="lead-stage hot">Hot 🔥</span></td>
                  <td><div class="mini-ava sm" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">SM</div></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-telephone-fill"></i></button></div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Sales Orders + Team Velocity -->
        <div class="dash-card span-6">
          <div class="card-head">
            <div>
              <div class="card-title">Recent Orders</div>
              <div class="card-sub">₹48.6L this month</div>
            </div>
            <div class="card-actions">
              <select class="filter-select"><option>All Status</option><option>Paid</option><option>Pending</option><option>Overdue</option></select>
              <button class="btn-primary-solid sm" onclick="openModal('addOrderModal')"><i class="bi bi-plus-lg"></i> Order</button>
            </div>
          </div>
          <div class="table-wrap">
            <table class="data-table">
              <thead><tr><th>Order ID</th><th>Client</th><th>Amount</th><th>Date</th><th>Status</th><th></th></tr></thead>
              <tbody>
                <tr>
                  <td><span class="mono">#ORD-2847</span></td>
                  <td>TechCorp Pvt Ltd</td>
                  <td><strong>₹8.5L</strong></td>
                  <td style="color:var(--t3)">Nov 18</td>
                  <td><span class="status-pill paid">Paid</span></td>
                  <td><button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button></td>
                </tr>
                <tr>
                  <td><span class="mono">#ORD-2846</span></td>
                  <td>Nexus Finance</td>
                  <td><strong>₹12.2L</strong></td>
                  <td style="color:var(--t3)">Nov 16</td>
                  <td><span class="status-pill pending">Pending</span></td>
                  <td><button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button></td>
                </tr>
                <tr>
                  <td><span class="mono">#ORD-2845</span></td>
                  <td>RetailMax India</td>
                  <td><strong>₹6.9L</strong></td>
                  <td style="color:var(--t3)">Nov 14</td>
                  <td><span class="status-pill paid">Paid</span></td>
                  <td><button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button></td>
                </tr>
                <tr>
                  <td><span class="mono">#ORD-2844</span></td>
                  <td>DataFirst Corp</td>
                  <td><strong>₹18.0L</strong></td>
                  <td style="color:var(--t3)">Nov 12</td>
                  <td><span class="status-pill overdue">Overdue</span></td>
                  <td><button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Team Activity Feed -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Team Activity</div>
            <span class="live-chip"><span class="pulse-dot"></span>Live</span>
          </div>
          <div class="card-body">
            <div class="activity-feed">
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);flex-shrink:0">AK</div>
                <div class="af-body">
                  <div class="af-text"><strong>Arjun Kumar</strong> pushed 3 commits to <span class="af-link">orion-ecom</span></div>
                  <div class="af-time"><i class="bi bi-git"></i> 8 min ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4);flex-shrink:0">PS</div>
                <div class="af-body">
                  <div class="af-text"><strong>Priya Sharma</strong> closed lead <span class="af-link">Nexus Finance</span> — ₹24L 🎉</div>
                  <div class="af-time"><i class="bi bi-trophy-fill"></i> 22 min ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444);flex-shrink:0">RV</div>
                <div class="af-body">
                  <div class="af-text"><strong>Ravi Verma</strong> updated status of <span class="af-link">HR Portal</span> task</div>
                  <div class="af-time"><i class="bi bi-kanban"></i> 1 hr ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#06b6d4,#6366f1);flex-shrink:0">NK</div>
                <div class="af-body">
                  <div class="af-text"><strong>Neha Kapoor</strong> marked attendance via <span class="af-link">Sales Panel</span></div>
                  <div class="af-time"><i class="bi bi-clock"></i> 2 hrs ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899);flex-shrink:0">SM</div>
                <div class="af-body">
                  <div class="af-text"><strong>Sunita Mehta</strong> created proposal for <span class="af-link">DataFirst Corp</span></div>
                  <div class="af-time"><i class="bi bi-file-earmark-text"></i> 3 hrs ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#ef4444,#f59e0b);flex-shrink:0">MG</div>
                <div class="af-body">
                  <div class="af-text"><strong>Mohit Gupta</strong> completed code review for <span class="af-link">FinanceMe</span></div>
                  <div class="af-time"><i class="bi bi-check2-circle"></i> 4 hrs ago</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Developer Velocity -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Developer Velocity</div>
            <div class="card-sub">Sprint 12 · Nov 1–15</div>
          </div>
          <div class="card-body">
            <div class="dev-velocity">
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">AK</div>
                <div class="vel-info"><div class="vel-name">Arjun Kumar</div><div class="vel-role">Frontend</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:88%;background:#6366f1"></div></div>
                  <span class="vel-pct">22 tasks</span>
                </div>
              </div>
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4)">MG</div>
                <div class="vel-info"><div class="vel-name">Mohit Gupta</div><div class="vel-role">Backend</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:76%;background:#10b981"></div></div>
                  <span class="vel-pct">19 tasks</span>
                </div>
              </div>
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">RV</div>
                <div class="vel-info"><div class="vel-name">Ravi Verma</div><div class="vel-role">Full Stack</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:64%;background:#f59e0b"></div></div>
                  <span class="vel-pct">16 tasks</span>
                </div>
              </div>
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#06b6d4,#6366f1)">SA</div>
                <div class="vel-info"><div class="vel-name">Sneha Agarwal</div><div class="vel-role">Mobile</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:52%;background:#06b6d4"></div></div>
                  <span class="vel-pct">13 tasks</span>
                </div>
              </div>
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">KP</div>
                <div class="vel-info"><div class="vel-name">Kiran Patel</div><div class="vel-role">DevOps</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:44%;background:#8b5cf6"></div></div>
                  <span class="vel-pct">11 tasks</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales Funnel -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Sales Funnel</div>
            <div class="card-sub">Conversion by stage</div>
          </div>
          <div class="card-body">
            <div class="funnel">
              <div class="funnel-stage" style="--w:100%;--col:#6366f1"><div class="fs-bar"><span class="fs-label">Awareness</span><span class="fs-val">1,247</span></div></div>
              <div class="funnel-stage" style="--w:68%;--col:#8b5cf6"><div class="fs-bar"><span class="fs-label">Interest</span><span class="fs-val">848</span></div></div>
              <div class="funnel-stage" style="--w:48%;--col:#a78bfa"><div class="fs-bar"><span class="fs-label">Proposal</span><span class="fs-val">598</span></div></div>
              <div class="funnel-stage" style="--w:30%;--col:#f59e0b"><div class="fs-bar"><span class="fs-label">Negotiation</span><span class="fs-val">374</span></div></div>
              <div class="funnel-stage" style="--w:19%;--col:#10b981"><div class="fs-bar"><span class="fs-label">Closed Won</span><span class="fs-val">238</span></div></div>
            </div>
            <div class="funnel-rate">Overall Conversion: <strong style="color:#10b981">19.1%</strong></div>
          </div>
        </div>

      </div><!-- end dash-grid -->
    </div><!-- end dashboard page -->

    <!-- ══════════════════════════════════
         STUB PAGES
    ══════════════════════════════════ -->
    <div class="page hidden" id="page-analytics">
      <div class="page-header"><h1 class="page-title">Analytics</h1><p class="page-desc">Deep-dive performance metrics</p></div>
      <div class="stub-banner"><i class="bi bi-activity"></i><div><strong>Analytics Module</strong><span>Detailed charts and cohort analysis — navigate to Dashboard for live overview.</span></div></div>
    </div>
    <div class="page hidden" id="page-projects">
      <div class="page-header"><h1 class="page-title">All Projects</h1><div class="header-actions"><div class="filter-bar"><select class="filter-select"><option>All Status</option><option>In Progress</option><option>Completed</option><option>Delayed</option><option>Planning</option></select><select class="filter-select"><option>All Teams</option><option>Frontend</option><option>Backend</option><option>Mobile</option><option>DevOps</option></select><select class="filter-select"><option>All Clients</option><option>TechCorp</option><option>Nexus Finance</option><option>RetailMax</option></select><div class="search-mini"><i class="bi bi-search"></i><input type="text" placeholder="Search projects…"></div></div><button class="btn-primary-solid" onclick="openModal('addProjectModal')"><i class="bi bi-plus-lg"></i> New Project</button></div></div>
      <div class="stub-banner info"><i class="bi bi-kanban-fill"></i><div><strong>Project Board</strong><span>Full Kanban board and Gantt view coming soon. Use the Dashboard for quick project overview.</span></div></div>
    </div>
    <div class="page hidden" id="page-leads">
      <div class="page-header"><h1 class="page-title">Lead Management</h1><div class="header-actions"><div class="filter-bar"><select class="filter-select"><option>All Stages</option><option>Hot</option><option>Warm</option><option>Cold</option></select><select class="filter-select"><option>All Sources</option><option>Website</option><option>Referral</option><option>LinkedIn</option></select><select class="filter-select"><option>All Owners</option><option>Rahul Kumar</option><option>Priya Sharma</option></select></div><button class="btn-primary-solid" onclick="openModal('addLeadModal')"><i class="bi bi-plus-lg"></i> Add Lead</button></div></div>
      <div class="stub-banner info"><i class="bi bi-person-lines-fill"></i><div><strong>Lead CRM</strong><span>Full CRM pipeline view. See recent leads on the Dashboard.</span></div></div>
    </div>
    <div class="page hidden" id="page-sales">
      <div class="page-header"><h1 class="page-title">Sales & Orders</h1><div class="header-actions"><div class="filter-bar"><select class="filter-select"><option>All Status</option><option>Paid</option><option>Pending</option><option>Overdue</option></select><select class="filter-select"><option>This Month</option><option>Last Month</option><option>This Quarter</option></select></div><button class="btn-primary-solid" onclick="openModal('addOrderModal')"><i class="bi bi-plus-lg"></i> New Order</button></div></div>
      <div class="stub-banner info"><i class="bi bi-bag-check-fill"></i><div><strong>Order Management</strong><span>Full order list, invoices and payment tracking.</span></div></div>
    </div>
    <div class="page hidden" id="page-attendance">
      <div class="page-header"><h1 class="page-title">Attendance Management</h1><div class="header-actions"><div class="filter-bar"><select class="filter-select"><option>All Departments</option><option>Sales</option><option>Development</option><option>Design</option><option>HR/Ops</option></select><select class="filter-select"><option>Today</option><option>This Week</option><option>This Month</option></select><div class="search-mini"><i class="bi bi-search"></i><input type="text" placeholder="Search member…"></div></div></div></div>
      <div class="stub-banner info"><i class="bi bi-clock-history"></i><div><strong>Attendance Tracker</strong><span>Biometric sync, geo-location check-in, leave management. Today's summary on Dashboard.</span></div></div>
    </div>
    <div class="page hidden" id="page-team">
      <div class="page-header"><h1 class="page-title">Team Directory</h1><div class="header-actions"><div class="filter-bar"><select class="filter-select"><option>All Roles</option><option>Developer</option><option>Sales</option><option>Designer</option><option>Manager</option></select><select class="filter-select"><option>All Departments</option><option>Tech</option><option>Sales</option><option>HR</option></select></div><button class="btn-primary-solid" onclick="openModal('addMemberModal')"><i class="bi bi-person-plus-fill"></i> Add Member</button></div></div>
      <div class="stub-banner info"><i class="bi bi-people-fill"></i><div><strong>52 Team Members</strong><span>Profiles, roles, performance and leave balances.</span></div></div>
    </div>
    <div class="page hidden" id="page-finance"><div class="page-header"><h1 class="page-title">Finance</h1></div><div class="stub-banner info"><i class="bi bi-currency-rupee"></i><div><strong>Finance Module</strong><span>P&L, invoices, expenses and tax reports.</span></div></div></div>
    <div class="page hidden" id="page-payroll"><div class="page-header"><h1 class="page-title">Payroll</h1></div><div class="stub-banner info"><i class="bi bi-wallet2"></i><div><strong>Payroll Module</strong><span>Salary processing, deductions and payslip generation.</span></div></div></div>
    <div class="page hidden" id="page-reports"><div class="page-header"><h1 class="page-title">Reports</h1></div><div class="stub-banner info"><i class="bi bi-file-earmark-bar-graph-fill"></i><div><strong>Reports Module</strong><span>Custom report builder with export to PDF, Excel and CSV.</span></div></div></div>
    <div class="page hidden" id="page-settings">
      <div class="page-header"><h1 class="page-title">Settings</h1><p class="page-desc">Configure platform preferences</p></div>
      <div class="settings-grid">
        <div class="dash-card">
          <div class="card-head"><div class="card-title">Company Profile</div></div>
          <div class="card-body">
            <div class="form-row"><label class="form-lbl">Company Name</label><input type="text" class="form-inp" value="Orion Technologies Pvt Ltd"></div>
            <div class="form-row"><label class="form-lbl">Email Domain</label><input type="text" class="form-inp" value="oriontech.in"></div>
            <div class="form-row"><label class="form-lbl">GST Number</label><input type="text" class="form-inp" value="27AABCO1234F1Z5"></div>
            <div class="form-row"><label class="form-lbl">Timezone</label><select class="form-inp"><option>IST (UTC+5:30)</option><option>UTC</option></select></div>
            <button class="btn-primary-solid" onclick="showToast('success','Settings saved!','bi-check-circle-fill')">Save Changes</button>
          </div>
        </div>
        <div class="dash-card">
          <div class="card-head"><div class="card-title">Notifications</div></div>
          <div class="card-body">
            <div class="setting-toggle-row"><div><div class="stl-name">New Lead Alert</div><div class="stl-desc">Notify admin when new lead added</div></div><label class="toggle-switch"><input type="checkbox" checked><span class="toggle-track"><span class="toggle-thumb"></span></span></label></div>
            <div class="setting-toggle-row"><div><div class="stl-name">Project Deadline</div><div class="stl-desc">Alert 48hrs before deadline</div></div><label class="toggle-switch"><input type="checkbox" checked><span class="toggle-track"><span class="toggle-thumb"></span></span></label></div>
            <div class="setting-toggle-row"><div><div class="stl-name">Attendance Alerts</div><div class="stl-desc">Late check-in notifications</div></div><label class="toggle-switch"><input type="checkbox"><span class="toggle-track"><span class="toggle-thumb"></span></span></label></div>
            <div class="setting-toggle-row"><div><div class="stl-name">Payment Received</div><div class="stl-desc">Instant payment notifications</div></div><label class="toggle-switch"><input type="checkbox" checked><span class="toggle-track"><span class="toggle-thumb"></span></span></label></div>
          </div>
        </div>
      </div>
    </div>

    <!-- SALES PANEL PAGES -->
    <div class="page hidden" id="page-sales-dash">
      <div class="page-header"><h1 class="page-title">My Sales Dashboard</h1><p class="page-desc">Personal performance · Rahul Kumar · Sales Executive</p></div>
      <div class="kpi-grid" style="grid-template-columns:repeat(4,1fr)">
        <div class="kpi-card" style="--kpi-accent:#6366f1"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-trophy-fill"></i></div><span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 24%</span></div><div class="kpi-value">₹18.4L</div><div class="kpi-label">My Revenue</div></div>
        <div class="kpi-card" style="--kpi-accent:#10b981"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-person-lines-fill"></i></div><span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 12%</span></div><div class="kpi-value">28</div><div class="kpi-label">My Leads</div></div>
        <div class="kpi-card" style="--kpi-accent:#f59e0b"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-bag-fill"></i></div><span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 8%</span></div><div class="kpi-value">12</div><div class="kpi-label">Orders Closed</div></div>
        <div class="kpi-card" style="--kpi-accent:#06b6d4"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i class="bi bi-bullseye"></i></div><span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 5%</span></div><div class="kpi-value">73%</div><div class="kpi-label">Target Achieved</div></div>
      </div>
      <div class="stub-banner info" style="margin-top:16px"><i class="bi bi-speedometer2"></i><div><strong>Sales Dashboard</strong><span>My full pipeline, call logs, follow-ups and commission tracker.</span></div></div>
    </div>
    <div class="page hidden" id="page-add-lead">
      <div class="page-header"><h1 class="page-title">Add New Lead</h1></div>
      <div class="dash-card" style="max-width:700px">
        <div class="card-head"><div class="card-title">Lead Information</div></div>
        <div class="card-body">
          <div class="form-grid">
            <div class="form-row"><label class="form-lbl">Company Name *</label><input type="text" class="form-inp" placeholder="e.g. TechCorp Solutions"></div>
            <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
            <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" placeholder="contact@company.com"></div>
            <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX"></div>
            <div class="form-row"><label class="form-lbl">Lead Source</label><select class="form-inp"><option>Website</option><option>Referral</option><option>LinkedIn</option><option>Cold Call</option><option>Event</option></select></div>
            <div class="form-row"><label class="form-lbl">Estimated Value</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
            <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Notes</label><textarea class="form-inp" rows="3" placeholder="Initial discussion notes…"></textarea></div>
          </div>
          <div class="form-actions"><button class="btn-ghost">Cancel</button><button class="btn-primary-solid" onclick="showToast('success','Lead added successfully!','bi-person-check-fill')"><i class="bi bi-plus-lg"></i> Add Lead</button></div>
        </div>
      </div>
    </div>
    <div class="page hidden" id="page-add-order">
      <div class="page-header"><h1 class="page-title">New Order</h1></div>
      <div class="dash-card" style="max-width:700px">
        <div class="card-head"><div class="card-title">Order Details</div></div>
        <div class="card-body">
          <div class="form-grid">
            <div class="form-row"><label class="form-lbl">Client Name *</label><input type="text" class="form-inp" placeholder="Select or type client"></div>
            <div class="form-row"><label class="form-lbl">Project/Service *</label><input type="text" class="form-inp" placeholder="What are we delivering?"></div>
            <div class="form-row"><label class="form-lbl">Order Value *</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
            <div class="form-row"><label class="form-lbl">Payment Terms</label><select class="form-inp"><option>Full Advance</option><option>50-50</option><option>Milestone</option><option>Net 30</option></select></div>
            <div class="form-row"><label class="form-lbl">Delivery Date</label><input type="date" class="form-inp"></div>
            <div class="form-row"><label class="form-lbl">Priority</label><select class="form-inp"><option>Normal</option><option>High</option><option>Urgent</option></select></div>
            <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Scope of Work</label><textarea class="form-inp" rows="3" placeholder="Describe deliverables…"></textarea></div>
          </div>
          <div class="form-actions"><button class="btn-ghost">Cancel</button><button class="btn-primary-solid" onclick="showToast('success','Order created!','bi-bag-check-fill')"><i class="bi bi-plus-lg"></i> Create Order</button></div>
        </div>
      </div>
    </div>
    <div class="page hidden" id="page-targets"><div class="page-header"><h1 class="page-title">My Targets</h1></div><div class="stub-banner info"><i class="bi bi-bullseye"></i><div><strong>Target Tracker</strong><span>Monthly and quarterly targets with achievement metrics.</span></div></div></div>

    <!-- DEV PANEL PAGES -->
    <div class="page hidden" id="page-dev-dash">
      <div class="page-header"><h1 class="page-title">Developer Dashboard</h1><p class="page-desc">My workspace · Arjun Kumar · Frontend Dev</p></div>
      <div class="kpi-grid" style="grid-template-columns:repeat(4,1fr)">
        <div class="kpi-card" style="--kpi-accent:#6366f1"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-kanban-fill"></i></div></div><div class="kpi-value">6</div><div class="kpi-label">Active Projects</div></div>
        <div class="kpi-card" style="--kpi-accent:#10b981"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-check2-all"></i></div></div><div class="kpi-value">14</div><div class="kpi-label">Open Tasks</div></div>
        <div class="kpi-card" style="--kpi-accent:#f59e0b"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-git"></i></div></div><div class="kpi-value">47</div><div class="kpi-label">Commits (Month)</div></div>
        <div class="kpi-card" style="--kpi-accent:#06b6d4"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i class="bi bi-clock-fill"></i></div></div><div class="kpi-value">168h</div><div class="kpi-label">Hours Logged</div></div>
      </div>
      <div class="stub-banner info" style="margin-top:16px"><i class="bi bi-terminal-fill"></i><div><strong>Developer Workspace</strong><span>Task board, code review queue, time tracking and project assignments.</span></div></div>
    </div>
    <div class="page hidden" id="page-my-projects"><div class="page-header"><h1 class="page-title">My Projects</h1></div><div class="stub-banner info"><i class="bi bi-kanban-fill"></i><div><strong>My Project Board</strong><span>Assigned projects with status update capability.</span></div></div></div>
    <div class="page hidden" id="page-tasks"><div class="page-header"><h1 class="page-title">My Tasks</h1></div><div class="stub-banner info"><i class="bi bi-check2-square"></i><div><strong>Task Manager</strong><span>Sprint tasks, priority queue and time logging.</span></div></div></div>
    <div class="page hidden" id="page-timeline"><div class="page-header"><h1 class="page-title">Project Timeline</h1></div><div class="stub-banner info"><i class="bi bi-calendar3"></i><div><strong>Gantt Timeline</strong><span>Visual timeline for sprint planning and deadlines.</span></div></div></div>
    <div class="page hidden" id="page-git-log"><div class="page-header"><h1 class="page-title">Commit Log</h1></div><div class="stub-banner info"><i class="bi bi-git"></i><div><strong>Git Activity</strong><span>Recent commits, pull requests and code review history.</span></div></div></div>

  </main>
</div>

<!-- ═══════════════════════════════════════════
     MODALS
════════════════════════════════════════════ -->

<!-- Quick Add -->
<div class="modal-backdrop" id="quickAddModal">
  <div class="modal-box sm-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Quick Add</span><button class="modal-close" onclick="closeModal('quickAddModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="quick-add-grid">
        <button class="qa-btn" onclick="closeModal('quickAddModal');openModal('addProjectModal')"><i class="bi bi-kanban-fill"></i><span>Project</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');openModal('addLeadModal')"><i class="bi bi-person-plus-fill"></i><span>Lead</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');openModal('addOrderModal')"><i class="bi bi-bag-plus-fill"></i><span>Order</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');openModal('addMemberModal')"><i class="bi bi-people-fill"></i><span>Member</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');showToast('info','Task creation coming soon','bi-check2-square')"><i class="bi bi-check2-square"></i><span>Task</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');showToast('info','Invoice creation coming soon','bi-receipt')"><i class="bi bi-receipt"></i><span>Invoice</span></button>
      </div>
    </div>
  </div>
</div>

<!-- Add Project Modal -->
<div class="modal-backdrop" id="addProjectModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>New Project</span><button class="modal-close" onclick="closeModal('addProjectModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Project Name *</label><input type="text" class="form-inp" placeholder="e.g. E-Commerce Platform"></div>
        <div class="form-row"><label class="form-lbl">Client *</label><input type="text" class="form-inp" placeholder="Client company name"></div>
        <div class="form-row"><label class="form-lbl">Team Lead</label><select class="form-inp"><option>Arjun Kumar</option><option>Priya Sharma</option><option>Ravi Verma</option><option>Neha Kapoor</option></select></div>
        <div class="form-row"><label class="form-lbl">Tech Stack</label><input type="text" class="form-inp" placeholder="React, Node, MongoDB…"></div>
        <div class="form-row"><label class="form-lbl">Start Date</label><input type="date" class="form-inp"></div>
        <div class="form-row"><label class="form-lbl">Deadline</label><input type="date" class="form-inp"></div>
        <div class="form-row"><label class="form-lbl">Budget (₹)</label><input type="text" class="form-inp" placeholder="e.g. 8,50,000"></div>
        <div class="form-row"><label class="form-lbl">Priority</label><select class="form-inp"><option>Normal</option><option>High</option><option>Critical</option></select></div>
        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Description</label><textarea class="form-inp" rows="3" placeholder="Project scope and objectives…"></textarea></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('addProjectModal')">Cancel</button>
      <button class="btn-primary-solid" onclick="closeModal('addProjectModal');showToast('success','Project created!','bi-kanban-fill')"><i class="bi bi-plus-lg"></i> Create Project</button>
    </div>
  </div>
</div>

<!-- Add Lead Modal -->
<div class="modal-backdrop" id="addLeadModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Add New Lead</span><button class="modal-close" onclick="closeModal('addLeadModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Company *</label><input type="text" class="form-inp" placeholder="Company name"></div>
        <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
        <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
        <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX"></div>
        <div class="form-row"><label class="form-lbl">Source</label><select class="form-inp"><option>Website</option><option>Referral</option><option>LinkedIn</option><option>Cold Call</option></select></div>
        <div class="form-row"><label class="form-lbl">Stage</label><select class="form-inp"><option>Cold</option><option>Warm</option><option>Hot</option></select></div>
        <div class="form-row"><label class="form-lbl">Est. Value (₹)</label><input type="text" class="form-inp" placeholder="Expected deal value"></div>
        <div class="form-row"><label class="form-lbl">Assign To</label><select class="form-inp"><option>Rahul Kumar</option><option>Priya Sharma</option><option>Neha Kapoor</option></select></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('addLeadModal')">Cancel</button>
      <button class="btn-primary-solid" onclick="closeModal('addLeadModal');showToast('success','Lead added!','bi-person-check-fill')"><i class="bi bi-plus-lg"></i> Add Lead</button>
    </div>
  </div>
</div>

<!-- Add Order Modal -->
<div class="modal-backdrop" id="addOrderModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Create New Order</span><button class="modal-close" onclick="closeModal('addOrderModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Client *</label><input type="text" class="form-inp" placeholder="Client name"></div>
        <div class="form-row"><label class="form-lbl">Order Value *</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
        <div class="form-row"><label class="form-lbl">Service/Product</label><input type="text" class="form-inp" placeholder="What are we delivering?"></div>
        <div class="form-row"><label class="form-lbl">Payment Terms</label><select class="form-inp"><option>Full Advance</option><option>50-50</option><option>Milestone</option><option>Net 30</option></select></div>
        <div class="form-row"><label class="form-lbl">Delivery Date</label><input type="date" class="form-inp"></div>
        <div class="form-row"><label class="form-lbl">Linked Project</label><select class="form-inp"><option>None</option><option>Orion E-Commerce</option><option>FinanceMe App</option><option>SmartCart PWA</option></select></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('addOrderModal')">Cancel</button>
      <button class="btn-primary-solid" onclick="closeModal('addOrderModal');showToast('success','Order created!','bi-bag-check-fill')"><i class="bi bi-plus-lg"></i> Create Order</button>
    </div>
  </div>
</div>

<!-- Add Member Modal -->
<div class="modal-backdrop" id="addMemberModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Add Team Member</span><button class="modal-close" onclick="closeModal('addMemberModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Full Name *</label><input type="text" class="form-inp" placeholder="Employee full name"></div>
        <div class="form-row"><label class="form-lbl">Email *</label><input type="email" class="form-inp" placeholder="name@oriontech.in"></div>
        <div class="form-row"><label class="form-lbl">Department</label><select class="form-inp"><option>Development</option><option>Sales</option><option>Design</option><option>HR/Ops</option></select></div>
        <div class="form-row"><label class="form-lbl">Role/Designation</label><select class="form-inp"><option>Developer</option><option>Sales Executive</option><option>Designer</option><option>Manager</option><option>Admin</option></select></div>
        <div class="form-row"><label class="form-lbl">Panel Access</label><select class="form-inp"><option>Sales Panel</option><option>Developer Panel</option><option>Admin Panel</option></select></div>
        <div class="form-row"><label class="form-lbl">Joining Date</label><input type="date" class="form-inp"></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('addMemberModal')">Cancel</button>
      <button class="btn-primary-solid" onclick="closeModal('addMemberModal');showToast('success','Member added! Invite sent.','bi-person-check-fill')"><i class="bi bi-person-plus-fill"></i> Add Member</button>
    </div>
  </div>
</div>

<!-- Project Detail Modal -->
<div class="modal-backdrop" id="projectDetailModal">
  <div class="modal-box lg-box" onclick="event.stopPropagation()">
    <div class="modal-hd">
      <div style="display:flex;align-items:center;gap:12px">
        <div class="proj-icon" style="background:rgba(99,102,241,.15);color:#6366f1;width:36px;height:36px;font-size:13px">OR</div>
        <div><span style="font-size:16px;font-weight:700">Orion E-Commerce</span><div style="font-size:12px;color:var(--t3)">#PRJ-001 · TechCorp Pvt Ltd</div></div>
      </div>
      <button class="modal-close" onclick="closeModal('projectDetailModal')"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="modal-bd">
      <div class="detail-grid">
        <div class="detail-col">
          <div class="detail-section">
            <div class="ds-label">Progress</div>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px">
              <div class="prog-bar-wrap" style="flex:1;height:8px"><div class="prog-fill" style="width:78%;background:#6366f1;height:8px;border-radius:4px"></div></div>
              <strong style="color:#6366f1">78%</strong>
            </div>
          </div>
          <div class="detail-kpis">
            <div class="dk-item"><div class="dk-val">₹8.5L</div><div class="dk-lbl">Budget</div></div>
            <div class="dk-item"><div class="dk-val" style="color:#10b981">₹6.2L</div><div class="dk-lbl">Spent</div></div>
            <div class="dk-item"><div class="dk-val" style="color:#f59e0b">Dec 15</div><div class="dk-lbl">Deadline</div></div>
            <div class="dk-item"><div class="dk-val">8</div><div class="dk-lbl">Devs</div></div>
          </div>
          <div class="detail-section">
            <div class="ds-label">Team Members</div>
            <div class="member-chips">
              <div class="mc-item"><div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">AK</div>Arjun Kumar <span class="mc-role">Lead</span></div>
              <div class="mc-item"><div class="mini-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4)">MG</div>Mohit Gupta <span class="mc-role">Backend</span></div>
              <div class="mc-item"><div class="mini-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">SA</div>Sneha Agarwal <span class="mc-role">Mobile</span></div>
              <div class="mc-item"><div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">KP</div>Kiran Patel <span class="mc-role">DevOps</span></div>
            </div>
          </div>
        </div>
        <div class="detail-col">
          <div class="detail-section">
            <div class="ds-label">Recent Activity</div>
            <div class="mini-timeline">
              <div class="mt-item green"><div class="mt-dot"></div><div><div class="mt-text">Payment milestone 2 received</div><div class="mt-time">Nov 15</div></div></div>
              <div class="mt-item blue"><div class="mt-dot"></div><div><div class="mt-text">Sprint 4 review completed</div><div class="mt-time">Nov 12</div></div></div>
              <div class="mt-item orange"><div class="mt-dot"></div><div><div class="mt-text">Design handover — checkout flow</div><div class="mt-time">Nov 8</div></div></div>
              <div class="mt-item purple"><div class="mt-dot"></div><div><div class="mt-text">Backend API v2 deployed</div><div class="mt-time">Nov 5</div></div></div>
            </div>
          </div>
          <div class="detail-section">
            <div class="ds-label">Status Update</div>
            <select class="form-inp" style="margin-top:8px"><option>In Progress</option><option>Review</option><option>Completed</option><option>On Hold</option></select>
            <textarea class="form-inp" rows="2" style="margin-top:8px" placeholder="Add a note…"></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('projectDetailModal')">Close</button>
      <button class="btn-ghost danger-ghost"><i class="bi bi-trash-fill"></i> Archive</button>
      <button class="btn-primary-solid" onclick="closeModal('projectDetailModal');showToast('success','Project updated!','bi-kanban-fill')"><i class="bi bi-check-lg"></i> Save Changes</button>
    </div>
  </div>
</div>

<!-- Lead Detail -->
<div class="modal-backdrop" id="leadDetailModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Lead Detail — TechCorp Solutions</span><button class="modal-close" onclick="closeModal('leadDetailModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="detail-kpis" style="margin-bottom:20px">
        <div class="dk-item"><div class="dk-val">₹18L</div><div class="dk-lbl">Est. Value</div></div>
        <div class="dk-item"><div class="dk-val" style="color:#ef4444">Hot 🔥</div><div class="dk-lbl">Stage</div></div>
        <div class="dk-item"><div class="dk-val">Website</div><div class="dk-lbl">Source</div></div>
        <div class="dk-item"><div class="dk-val">14 days</div><div class="dk-lbl">Age</div></div>
      </div>
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Contact</label><input class="form-inp" value="Vikram Bhatia" readonly></div>
        <div class="form-row"><label class="form-lbl">Email</label><input class="form-inp" value="vikram@techcorp.com" readonly></div>
        <div class="form-row"><label class="form-lbl">Phone</label><input class="form-inp" value="+91 98765 43210" readonly></div>
        <div class="form-row"><label class="form-lbl">Move to Stage</label><select class="form-inp"><option>Hot</option><option>Proposal Sent</option><option>Negotiation</option><option>Closed Won</option><option>Closed Lost</option></select></div>
        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Follow-up Note</label><textarea class="form-inp" rows="2" placeholder="Add follow-up note…"></textarea></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('leadDetailModal')">Close</button>
      <button class="btn-primary-solid" onclick="closeModal('leadDetailModal');showToast('success','Lead updated!','bi-person-check-fill')">Update Lead</button>
    </div>
  </div>
</div>

<!-- Order Detail -->
<div class="modal-backdrop" id="orderDetailModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Order #ORD-2847</span><button class="modal-close" onclick="closeModal('orderDetailModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="detail-kpis" style="margin-bottom:20px">
        <div class="dk-item"><div class="dk-val">₹8.5L</div><div class="dk-lbl">Order Value</div></div>
        <div class="dk-item"><div class="dk-val" style="color:#10b981">Paid</div><div class="dk-lbl">Status</div></div>
        <div class="dk-item"><div class="dk-val">Nov 18</div><div class="dk-lbl">Date</div></div>
        <div class="dk-item"><div class="dk-val">Dec 15</div><div class="dk-lbl">Delivery</div></div>
      </div>
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Client</label><input class="form-inp" value="TechCorp Pvt Ltd" readonly></div>
        <div class="form-row"><label class="form-lbl">Payment Terms</label><input class="form-inp" value="Full Advance" readonly></div>
        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Linked Project</label><input class="form-inp" value="Orion E-Commerce #PRJ-001" readonly></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('orderDetailModal')">Close</button>
      <button class="btn-ghost" onclick="showToast('info','Invoice downloading…','bi-download')"><i class="bi bi-download"></i> Invoice</button>
      <button class="btn-primary-solid" onclick="closeModal('orderDetailModal');showToast('success','Order updated!','bi-bag-check-fill')">Update</button>
    </div>
  </div>
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
  el.textContent = now.toLocaleDateString('en-IN', { weekday:'long', year:'numeric', month:'long', day:'numeric' }) +
    ' · ' + now.toLocaleTimeString('en-IN', { hour:'2-digit', minute:'2-digit' });
}
updateDate();
setInterval(updateDate, 30000);

/* ── THEME ── */
let isDark = true;
function toggleTheme() {
  isDark = !isDark;
  document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
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
  dashboard: 'Dashboard', analytics: 'Analytics', projects: 'Projects',
  leads: 'Leads', sales: 'Sales & Orders', finance: 'Finance',
  team: 'Team', attendance: 'Attendance', payroll: 'Payroll',
  reports: 'Reports', settings: 'Settings',
  'sales-dash': 'My Dashboard', 'add-lead': 'Add Lead', 'add-order': 'New Order',
  targets: 'My Targets',
  'dev-dash': 'Dev Dashboard', 'my-projects': 'My Projects', tasks: 'Tasks',
  timeline: 'Timeline', 'git-log': 'Commit Log'
};

function navigate(e, pageId) {
  e && e.preventDefault();
  showPage(pageId);
  updateNavActive(pageId);
  document.getElementById('activePageLabel').textContent = pageLabels[pageId] || pageId;
  if (window.innerWidth < 992) closeSidebar();
}

function showPage(pageId) {
  document.querySelectorAll('.page').forEach(p => p.classList.add('hidden'));
  const target = document.getElementById('page-' + pageId);
  if (target) target.classList.remove('hidden');
}

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
  if (m) { m.classList.add('open'); document.body.style.overflow = 'hidden'; }
}
function closeModal(id) {
  const m = document.getElementById(id);
  if (m) { m.classList.remove('open'); document.body.style.overflow = ''; }
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
  success: { bg: 'rgba(16,185,129,.12)', border: '#10b981', color: '#10b981' },
  info:    { bg: 'rgba(99,102,241,.12)', border: '#6366f1', color: '#6366f1' },
  warning: { bg: 'rgba(245,158,11,.12)', border: '#f59e0b', color: '#f59e0b' },
  danger:  { bg: 'rgba(239,68,68,.12)',  border: '#ef4444', color: '#ef4444' },
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
    table.querySelectorAll('tbody .cb-custom').forEach(cb => { cb.checked = this.checked; });
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
    setTimeout(() => { bar.style.width = target; }, 200);
  });
}
setTimeout(animateBars, 300);

/* ── INITIAL STATE ── */
document.addEventListener('DOMContentLoaded', () => {
  showPage('dashboard');
  updateNavActive('dashboard');
});

</script>
</body>
</html>