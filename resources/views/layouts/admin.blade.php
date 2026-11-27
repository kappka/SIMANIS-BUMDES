<!DOCTYPE html>
<html lang="id" data-radius="soft">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Simanis BUMDes</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --p-bg: #f7f7f7;
            --p-surface: #ebebeb;
            --p-ink: #0d2a1c;
            --p-muted: #5f6b64;
            --p-accent: #f6e016;
            --p-accent-ink: #0d2a1c;
            --p-action: #0d2a1c;
            --p-action-ink: #f7f7f7;
            --p-inv-bg: #0d2a1c;
            --p-inv-surface: #1c3629;
            --p-inv-ink: #f7f7f7;
            --p-inv-muted: #97a39c;
            --p-inv-accent: #f6e016;
            --p-sidebar-w: 236px;
            --p-sidebar-collapsed-w: 68px;
            --font: "Figtree", system-ui, sans-serif;
            --radius: 8px;
            --radius-sm: 4px;
            --radius-lg: 14px;
            --transition: 220ms cubic-bezier(.4,0,.2,1);
        }

        body {
            font-family: var(--font);
            background: var(--p-bg);
            color: var(--p-ink);
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
        }

        [x-cloak] { display: none !important; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
            width: var(--p-sidebar-w);
            height: 100dvh;
            background: var(--p-inv-bg);
            border-right: 1px solid rgba(255,255,255,.06);
            display: flex;
            flex-direction: column;
            transition: width var(--transition), transform var(--transition);
            overflow: hidden;
        }
        .sidebar.collapsed {
            width: var(--p-sidebar-collapsed-w);
        }
        .sidebar-brand {
            padding: 16px 16px 14px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            gap: 11px;
            min-height: 64px;
            flex-shrink: 0;
        }
        .sidebar-brand-logo {
            width: 34px; height: 34px; border-radius: 9px;
            background: rgba(246,224,22,.14);
            border: 1px solid rgba(246,224,22,.22);
            display: grid; place-items: center; flex-shrink: 0;
        }
        .sidebar-brand-text {
            min-width: 0;
            transition: opacity 160ms ease, width var(--transition);
            white-space: nowrap;
        }
        .sidebar.collapsed .sidebar-brand-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        .sidebar-brand-name { font-weight: 700; font-size: 15px; color: var(--p-inv-ink); line-height: 1.2; letter-spacing: -0.01em; }
        .sidebar-brand-sub { font-size: 11px; color: var(--p-inv-muted); }

        .sidebar-close-btn {
            display: none;
            margin-left: auto;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            color: var(--p-inv-muted);
            width: 28px; height: 28px;
            border-radius: 6px;
            place-items: center;
            cursor: pointer;
            flex-shrink: 0;
        }
        .sidebar-close-btn:hover { color: var(--p-inv-ink); background: rgba(255,255,255,.12); }

        .sidebar-section {
            font-size: 10px; font-weight: 700; letter-spacing: .09em; text-transform: uppercase;
            color: var(--p-inv-muted); opacity: .75;
            padding: 18px 12px 6px;
            white-space: nowrap;
            transition: opacity 150ms ease;
        }
        .sidebar.collapsed .sidebar-section {
            padding: 12px 0 6px;
            height: 18px;
            overflow: hidden;
            opacity: 0;
            position: relative;
        }
        .sidebar.collapsed .sidebar-section::after {
            content: "";
            position: absolute;
            left: 16px; right: 16px; top: 9px;
            height: 1px;
            background: rgba(255,255,255,.1);
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 10px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.15) transparent;
        }
        .nav-link {
            position: relative;
            display: flex; align-items: center; gap: 11px;
            padding: 9px 11px; border-radius: 8px;
            font-size: 13.5px; font-weight: 500;
            color: var(--p-inv-muted); text-decoration: none;
            transition: background var(--transition), color var(--transition);
            margin-bottom: 3px;
            white-space: nowrap;
        }
        .nav-link:hover { background: rgba(255,255,255,.07); color: var(--p-inv-ink); }
        .nav-link.active {
            background: rgba(246,224,22,.13);
            color: var(--p-inv-accent);
            font-weight: 600;
        }
        .nav-link.active::before {
            content: "";
            position: absolute;
            left: 0; top: 7px; bottom: 7px;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--p-inv-accent);
        }
        .nav-icon {
            width: 20px; height: 20px;
            display: grid; place-items: center;
            flex-shrink: 0;
        }
        .nav-label {
            transition: opacity 150ms ease;
        }
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 10px;
        }
        .sidebar.collapsed .nav-link.active::before {
            left: 2px;
        }
        .sidebar.collapsed .nav-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid rgba(255,255,255,.08);
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-shrink: 0;
            background: rgba(0,0,0,.1);
        }
        .sidebar-logout-btn {
            width: 100%;
            display: flex; align-items: center; gap: 10px;
            padding: 9px 11px; border-radius: 8px;
            border: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.05);
            color: var(--p-inv-muted);
            font-family: var(--font); font-size: 13px; font-weight: 500;
            cursor: pointer;
            transition: background var(--transition), color var(--transition), border-color var(--transition);
            white-space: nowrap;
        }
        .sidebar-logout-btn:hover {
            background: rgba(239,68,68,.15);
            border-color: rgba(239,68,68,.3);
            color: #fecaca;
        }
        .sidebar.collapsed .sidebar-logout-btn {
            justify-content: center;
            padding: 9px;
        }

        /* ── Main ── */
        .main {
            margin-left: var(--p-sidebar-w);
            min-height: 100dvh;
            min-width: 0;
            display: flex;
            flex-direction: column;
            transition: margin-left var(--transition);
        }
        .main.sidebar-collapsed {
            margin-left: var(--p-sidebar-collapsed-w);
        }

        /* ── Topbar ── */
        .topbar {
            position: sticky; top: 0; z-index: 20;
            background: rgba(247,247,247,.95); backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(13,42,28,.08);
            height: 56px; display: flex; align-items: center; padding: 0 24px; gap: 12px;
        }
        .sidebar-toggle-desktop {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 7px;
            border: 1px solid rgba(13,42,28,.12); background: #fff;
            color: var(--p-muted); cursor: pointer;
            transition: background var(--transition), color var(--transition), border-color var(--transition);
        }
        .sidebar-toggle-desktop:hover {
            background: var(--p-surface); color: var(--p-ink); border-color: rgba(13,42,28,.22);
        }
        .topbar-title { font-size: 15px; font-weight: 700; letter-spacing: -0.01em; }
        .topbar-user { margin-left: auto; font-size: 12.5px; color: var(--p-muted); display: flex; align-items: center; gap: 8px; }

        /* ── Page content ── */
        .page-content { flex: 1; padding: 24px; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            font-family: var(--font); font-weight: 500; font-size: 13.5px;
            padding: 8px 16px; border-radius: var(--radius); border: none;
            cursor: pointer; transition: transform var(--transition), background var(--transition), box-shadow var(--transition);
            text-decoration: none; white-space: nowrap; line-height: 1;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary { background: var(--p-action); color: var(--p-action-ink); }
        .btn-primary:hover { background: #1c3a28; box-shadow: 0 4px 12px rgba(13,42,28,.18); }
        .btn-accent { background: var(--p-accent); color: var(--p-accent-ink); }
        .btn-accent:hover { background: #e8d400; box-shadow: 0 4px 12px rgba(246,224,22,.35); }
        .btn-ghost { background: #fff; color: var(--p-ink); border: 1.5px solid rgba(13,42,28,.15); }
        .btn-ghost:hover { background: var(--p-surface); border-color: rgba(13,42,28,.3); }
        .btn-sm { padding: 6px 12px; font-size: 12.5px; }
        .btn-xs { padding: 4px 10px; font-size: 12px; }
        .btn-danger { background: #fef2f2; color: #b91c1c; border: 1.5px solid #fecaca; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-info { background: #eff6ff; color: #1d4ed8; border: 1.5px solid #bfdbfe; }
        .btn-info:hover { background: #dbeafe; }
        .btn-warning { background: #fff7ed; color: #c2410c; border: 1.5px solid #fed7aa; }
        .btn-warning:hover { background: #ffedd5; }
        .btn-success { background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0; }
        .btn-success:hover { background: #dcfce7; }

        /* ── Cards ── */
        .card {
            background: #fff; border-radius: var(--radius-lg);
            border: 1px solid rgba(13,42,28,.08);
            box-shadow: 0 1px 3px rgba(13,42,28,.04);
        }
        .card-body { padding: 20px; }
        .card-header {
            padding: 16px 20px 14px;
            border-bottom: 1px solid rgba(13,42,28,.06);
            font-size: 14px; font-weight: 600;
        }
        .card-footer {
            padding: 12px 20px;
            border-top: 1px solid rgba(13,42,28,.06);
        }

        /* ── Metric cards ── */
        .metric-card {
            background: #fff; border-radius: var(--radius-lg);
            border: 1px solid rgba(13,42,28,.08);
            box-shadow: 0 1px 3px rgba(13,42,28,.04);
            padding: 20px;
        }
        .metric-label { font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--p-muted); margin-bottom: 8px; }
        .metric-value { font-size: 24px; font-weight: 800; letter-spacing: -0.03em; line-height: 1; }
        .metric-sub { font-size: 11.5px; color: var(--p-muted); margin-top: 4px; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 11px; font-weight: 600; padding: 2px 8px;
            border-radius: 999px; letter-spacing: .02em;
        }
        .badge .dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-red { background: #fee2e2; color: #b91c1c; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-orange { background: #fff7ed; color: #c2410c; }
        .badge-purple { background: #f3e8ff; color: #7e22ce; }

        /* ── Tables ── */
        .data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .data-table thead th {
            padding: 10px 16px; text-align: left;
            font-size: 10.5px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
            color: var(--p-muted); background: var(--p-bg); border-bottom: 1px solid var(--p-surface);
            white-space: nowrap;
        }
        .data-table thead th.r { text-align: right; }
        .data-table tbody td { padding: 10px 16px; border-bottom: 1px solid rgba(13,42,28,.045); vertical-align: middle; }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr { transition: background var(--transition); }
        .data-table tbody tr:hover td { background: rgba(13,42,28,.018); }
        .data-table td.r { text-align: right; }
        .data-table td.nota { font-family: "SF Mono", "Fira Code", ui-monospace, monospace; font-size: 11px; color: #15803d; }
        .data-table td.num { font-variant-numeric: tabular-nums; }

        /* ── Form ── */
        .form-label { display: block; font-size: 11.5px; font-weight: 600; color: var(--p-muted); margin-bottom: 5px; letter-spacing: .02em; }
        .form-control {
            width: 100%; border: 1.5px solid rgba(13,42,28,.14);
            border-radius: var(--radius); padding: 8px 11px; font-size: 13.5px;
            font-family: var(--font); background: #fff; color: var(--p-ink);
            transition: border-color var(--transition), box-shadow var(--transition);
            -webkit-appearance: none;
        }
        .form-control:focus { outline: none; border-color: var(--p-accent); box-shadow: 0 0 0 3px rgba(246,224,22,.18); }
        .form-control::placeholder { color: #aab8b0; }
        .form-control:disabled { background: var(--p-bg); color: var(--p-muted); }

        /* ── Tabs ── */
        .tab-bar { display: flex; gap: 4px; margin-bottom: 20px; border-bottom: 1px solid rgba(13,42,28,.1); padding-bottom: 0; }
        .tab-btn {
            padding: 8px 16px; font-size: 13px; font-weight: 500; font-family: var(--font);
            border: none; background: none; cursor: pointer; color: var(--p-muted);
            border-bottom: 2px solid transparent; margin-bottom: -1px;
            transition: color var(--transition), border-color var(--transition);
        }
        .tab-btn:hover { color: var(--p-ink); }
        .tab-btn.active { color: var(--p-ink); border-bottom-color: var(--p-ink); font-weight: 600; }

        /* ── Flash ── */
        .flash { padding: 11px 14px; border-radius: var(--radius); font-size: 13px; margin-bottom: 18px; display: flex; align-items: flex-start; gap: 8px; }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

        /* ── Filter bar ── */
        .filter-bar { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; }
        .filter-bar .form-control { width: auto; }

        /* ── Overlay (mobile) ── */
        .overlay { position: fixed; inset: 0; background: rgba(13,42,28,.5); z-index: 40; }

        /* ── Hamburger ── */
        .hamburger { display: none; padding: 6px; border: none; background: none; cursor: pointer; color: var(--p-muted); }

        /* ── Grid helpers ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .span-2 { grid-column: span 2; }
        .span-3 { grid-column: span 3; }

        /* ── Pagination ── */
        nav[role="navigation"] .flex { display: flex; gap: 4px; flex-wrap: wrap; }
        nav[role="navigation"] a, nav[role="navigation"] span {
            display: inline-flex; align-items: center; padding: 5px 10px;
            border-radius: 6px; font-size: 13px; border: 1px solid var(--p-surface);
            color: var(--p-ink); text-decoration: none; transition: background var(--transition);
        }
        nav[role="navigation"] a:hover { background: var(--p-surface); }
        nav[role="navigation"] span[aria-current] { background: var(--p-action); color: var(--p-action-ink); border-color: var(--p-action); }

        @media (max-width: 1024px) {
            .sidebar {
                width: 256px !important;
                transform: translateX(-100%);
                box-shadow: 0 20px 40px rgba(0,0,0,.28);
            }
            .sidebar.open { transform: translateX(0); }
            .sidebar.collapsed .sidebar-brand-text,
            .sidebar.collapsed .nav-label { opacity: 1; width: auto; overflow: visible; }
            .sidebar.collapsed .nav-link { justify-content: flex-start; padding: 9px 11px; }
            .sidebar.collapsed .sidebar-logout-btn { justify-content: flex-start; padding: 9px 11px; }
            .sidebar.collapsed .sidebar-section { padding: 18px 12px 6px; height: auto; opacity: .75; }
            .sidebar.collapsed .sidebar-section::after { display: none; }
            .sidebar-close-btn { display: grid; }
            .sidebar-toggle-desktop { display: none; }
            .main, .main.sidebar-collapsed { margin-left: 0; }
            .hamburger { display: flex; }
            .grid-4 { grid-template-columns: 1fr 1fr; }
            .grid-3 { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .span-2, .span-3 { grid-column: span 1; }
            .page-content { padding: 16px; }
            .topbar { padding: 0 16px; }
        }
    </style>
</head>
<body x-data="{
    sidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('simanis_sidebar_collapsed') === 'true',
    toggleCollapse() {
        this.sidebarCollapsed = !this.sidebarCollapsed;
        localStorage.setItem('simanis_sidebar_collapsed', this.sidebarCollapsed);
    }
}">

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false" class="overlay"></div>

{{-- Sidebar --}}
<aside :class="{ 'open': sidebarOpen, 'collapsed': sidebarCollapsed }" class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">
            <svg width="18" height="18" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                <path d="M14 4C14 4 7 9 7 16a7 7 0 0014 0C21 9 14 4 14 4Z" fill="#f6e016"/>
            </svg>
        </div>
        <div class="sidebar-brand-text">
            <div class="sidebar-brand-name">Simanis</div>
            <div class="sidebar-brand-sub">BUMDes Payungsari</div>
        </div>
        <button type="button" class="sidebar-close-btn" @click="sidebarOpen=false" aria-label="Tutup Menu">
            <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section" style="padding-top:6px;">Operasional</div>

        <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M2 10a8 8 0 1116 0A8 8 0 012 10zm8-4a1 1 0 100 2 1 1 0 000-2zm0 4a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z"/></svg>
            </span>
            <span class="nav-label">Dashboard</span>
        </a>
        <a href="{{ route('admin.komoditas.index') }}" title="Stok & Harga" class="nav-link {{ request()->routeIs('admin.komoditas.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z"/></svg>
            </span>
            <span class="nav-label">Stok &amp; Harga</span>
        </a>
        <a href="{{ route('admin.petani.index') }}" title="Petani & Lahan" class="nav-link {{ request()->routeIs('admin.petani.*', 'admin.lahan.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zm-9 8a5 5 0 0110 0H4z"/></svg>
            </span>
            <span class="nav-label">Petani &amp; Lahan</span>
        </a>
        <a href="{{ route('admin.barang-masuk.index') }}" title="Barang Masuk" class="nav-link {{ request()->routeIs('admin.barang-masuk.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
            </span>
            <span class="nav-label">Barang Masuk</span>
        </a>
        <a href="{{ route('admin.penjualan.index') }}" title="Penjualan" class="nav-link {{ request()->routeIs('admin.penjualan.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </span>
            <span class="nav-label">Penjualan</span>
        </a>

        <div class="sidebar-section">Keuangan</div>

        <a href="{{ route('admin.biaya-operasional.index') }}" title="Biaya Operasional" class="nav-link {{ request()->routeIs('admin.biaya-operasional.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zm14 5H2v5a2 2 0 002 2h12a2 2 0 002-2V9zm-6 3a1 1 0 100 2 1 1 0 000-2z"/></svg>
            </span>
            <span class="nav-label">Biaya Operasional</span>
        </a>
        <a href="{{ route('admin.laporan.index') }}" title="Laporan LPJ" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 100 2h4a1 1 0 100-2H8zm0-3a1 1 0 100 2h4a1 1 0 100-2H8z" clip-rule="evenodd"/></svg>
            </span>
            <span class="nav-label">Laporan LPJ</span>
        </a>
        <a href="{{ route('admin.pengaturan.index') }}" title="Pengaturan" class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
            </span>
            <span class="nav-label">Pengaturan</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Keluar" class="sidebar-logout-btn">
                <span class="nav-icon">
                    <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                </span>
                <span class="nav-label">Keluar</span>
            </button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="main" :class="{ 'sidebar-collapsed': sidebarCollapsed }">
    <header class="topbar">
        <button class="hamburger" @click="sidebarOpen=!sidebarOpen" aria-label="Menu">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
        </button>
        <button type="button" class="sidebar-toggle-desktop" @click="toggleCollapse()" :title="sidebarCollapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'" aria-label="Toggle Sidebar">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm5 1v10h7V5H8z" clip-rule="evenodd"/>
            </svg>
        </button>
        <div class="topbar-title">@yield('page-title', 'Admin BUMDes')</div>
        <div class="topbar-user">
            <div style="width:28px; height:28px; border-radius:50%; background: var(--p-inv-bg); display:grid; place-items:center;">
                <svg width="14" height="14" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
            </div>
            {{ auth()->user()->name }}
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
            <div class="flash flash-success">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0; margin-top:1px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0; margin-top:1px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="flash flash-error">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0; margin-top:1px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>
