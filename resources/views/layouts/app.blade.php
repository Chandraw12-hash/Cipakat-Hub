<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cipakat Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            overflow: hidden;
        }

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            width: 270px;
            background: linear-gradient(160deg, #1a4731 0%, #2d6a4f 40%, #3CB371 100%);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 50;
            box-shadow: 6px 0 30px rgba(0, 0, 0, 0.18);
            display: flex;
            flex-direction: column;
            transition: width 0.3s cubic-bezier(.4, 0, .2, 1);
        }

        .sidebar.collapsed {
            width: 68px;
        }

        /* ===================== MAIN CONTENT ===================== */
        .main-content {
            margin-left: 270px;
            height: 100vh;
            overflow-y: auto;
            transition: margin-left 0.3s cubic-bezier(.4, 0, .2, 1);
        }

        .main-content.collapsed {
            margin-left: 68px;
        }

        /* ===================== SIDEBAR HEADER ===================== */
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 16px 16px;
            gap: 8px;
        }

        .sidebar-brand-row {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
            flex: 1;
            min-width: 0;
        }

        .brand-icon-box {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        .brand-icon-box svg {
            width: 20px;
            height: 20px;
            color: white;
        }

        .brand-texts {
            overflow: hidden;
            white-space: nowrap;
            transition: opacity 0.25s ease, max-width 0.3s ease;
            max-width: 200px;
        }

        .sidebar.collapsed .brand-texts {
            opacity: 0;
            max-width: 0;
        }

        .brand-title {
            color: white;
            font-weight: 700;
            font-size: 15px;
            line-height: 1.2;
            letter-spacing: 0.1px;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.48);
            font-size: 10px;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: 2px;
        }

        .collapse-btn {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: rgba(255, 255, 255, 0.80);
            width: 40px;
            height: 40px;
            border-radius: 11px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .collapse-btn:hover {
            background: rgba(255, 255, 255, 0.20);
            color: white;
        }

        .collapse-btn svg {
            width: 14px;
            height: 14px;
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed .collapse-btn svg {
            transform: rotate(180deg);
        }

        /* hide old toggle-btn */
        .toggle-btn {
            display: none;
        }

        .sidebar-brand {
            display: none;
        }

        /* ===================== DIVIDER ===================== */
        .sidebar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.10);
            margin: 0 16px 12px;
        }

        /* ===================== USER SECTION ===================== */
        .user-section {
            padding: 10px 12px;
            margin: 4px 8px 6px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.10);
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .user-section:hover {
            background: rgba(255, 255, 255, 0.13);
        }

        .avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #1b4d3e, #2d6a4f);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.25);
        }

        .avatar img {
            width: 100%;
            height: 100%;
            border-radius: 9px;
            object-fit: cover;
        }

        .user-info {
            overflow: hidden;
            transition: opacity 0.2s ease, width 0.3s ease;
            white-space: nowrap;
            flex: 1;
            min-width: 0;
        }

        .sidebar.collapsed .user-info {
            opacity: 0;
            width: 0;
        }

        .user-name {
            color: white;
            font-weight: 600;
            font-size: 13px;
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            color: rgba(255, 255, 255, 0.50);
            font-size: 10px;
            margin-top: 1px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            display: inline-block;
            background: rgba(255, 255, 255, 0.12);
            color: #d1fae5;
            font-size: 9px;
            padding: 2px 7px;
            border-radius: 20px;
            margin-top: 3px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* ===================== NAV SECTION LABEL ===================== */
        .nav-section-label {
            color: rgba(255, 255, 255, 0.35);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 10px 20px 4px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s ease;
        }

        .sidebar.collapsed .nav-section-label {
            opacity: 0;
        }

        /* ===================== NAV ITEMS ===================== */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 14px;
            margin: 2px 8px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.70);
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            transform: translateX(2px);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            font-weight: 600;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            height: 50%;
            width: 3px;
            background: white;
            border-radius: 0 3px 3px 0;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.85;
        }

        .nav-item.active svg,
        .nav-item:hover svg {
            opacity: 1;
        }

        .nav-label {
            transition: opacity 0.2s ease;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-label {
            opacity: 0;
            width: 0;
        }

        /* TOOLTIP saat collapsed */
        .sidebar.collapsed .nav-item:hover::after {
            content: attr(data-label);
            position: fixed;
            left: 76px;
            background: #1a4731;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 100;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            font-weight: 500;
        }

        /* ===================== LOGOUT ===================== */
        .logout-section {
            padding: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 4px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 11px;
            width: 100%;
            padding: 10px 14px;
            border-radius: 12px;
            color: rgba(254, 202, 202, 0.80);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        .logout-btn:hover {
            background: rgba(254, 202, 202, 0.12);
            color: #fecaca;
            transform: translateX(2px);
        }

        .logout-btn svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .logout-label {
            transition: opacity 0.2s ease;
        }

        .sidebar.collapsed .logout-label {
            opacity: 0;
            width: 0;
        }

        /* ===================== HAMBURGER MOBILE ===================== */
        .hamburger {
            display: none;
            position: fixed;
            top: 14px;
            left: 14px;
            z-index: 60;
            background: #2d6a4f;
            border: none;
            color: white;
            padding: 9px;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 45;
            backdrop-filter: blur(2px);
        }

        /* ===================== SKELETON ===================== */
        .skeleton {
            animation: skeleton-loading 1s linear infinite alternate;
            background: linear-gradient(90deg, #e2e8f0 0%, #f1f5f9 50%, #e2e8f0 100%);
            background-size: 200% 100%;
            border-radius: 10px;
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .fade-in {
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===================== HEADER ===================== */
        .page-header {
            background: white;
            padding: 14px 24px;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 40;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .page-header-dot {
            width: 8px;
            height: 8px;
            background: linear-gradient(135deg, #2d6a4f, #3CB371);
            border-radius: 50%;
        }

        .page-header h2 {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        /* ===================== SCROLLBAR SIDEBAR ===================== */
        .sidebar::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        /* ===================== MOBILE ===================== */
        @media (max-width: 768px) {
            .sidebar {
                width: 270px !important;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 55;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar .toggle-btn {
                display: none;
            }

            .sidebar.collapsed .nav-label,
            .sidebar.collapsed .logout-label,
            .sidebar.collapsed .user-info {
                opacity: 1;
                width: auto;
            }

            .main-content,
            .main-content.collapsed {
                margin-left: 0;
            }

            .hamburger {
                display: block;
            }

            .overlay.show {
                display: block;
            }
        }
    </style>
</head>

<body>

    <!-- Hamburger Mobile -->
    <button class="hamburger" id="hamburgerBtn">
        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="overlay" id="overlay"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <!-- SIDEBAR HEADER: Brand + Collapse -->
        <div class="sidebar-header">
            <div class="sidebar-brand-row">
                <div class="brand-icon-box">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div class="brand-texts">
                    <div class="brand-title">Cipakat Hub</div>
                    <div class="brand-subtitle">Desa Cipakat</div>
                </div>
            </div>
            <button class="collapse-btn" id="collapseBtn" title="Collapse sidebar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <div class="sidebar-divider"></div>

        <!-- Nav -->
        <nav style="flex: 1; padding-bottom: 8px;">

            <div class="nav-section-label">Menu Utama</div>

            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                data-label="Dashboard">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="nav-item" data-label="Profil Saya">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="nav-label">Profil Saya</span>
            </a>

            @auth
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                    <div class="nav-section-label">Manajemen</div>

                    <a href="{{ route('pengumuman.admin') }}" class="nav-item" data-label="Manajemen Pengumuman">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span class="nav-label">Manajemen Pengumuman</span>
                    </a>

                    <a href="{{ route('layanan.admin') }}" class="nav-item" data-label="Manajemen Surat">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="nav-label">Manajemen Surat</span>
                    </a>

                    <a href="{{ route('produk.admin') }}" class="nav-item" data-label="Manajemen Produk">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z" />
                        </svg>
                        <span class="nav-label">Manajemen Produk</span>
                    </a>

                    <a href="{{ route('booking.admin') }}" class="nav-item" data-label="Manajemen Booking">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="nav-label">Manajemen Booking</span>
                    </a>

                    <a href="#" class="nav-item" data-label="Pengaduan Masyarakat" id="pengaduanLink">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="nav-label">Pengaduan Masyarakat</span>
                    </a>

                    <a href="{{ route('laporan.index') }}" class="nav-item" data-label="Laporan Layanan">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="nav-label">Laporan Layanan</span>
                    </a>
                @endif
            @endauth

            @auth
                @if (Auth::user()->role == 'warga')
                    <div class="nav-section-label">Layanan</div>

                    <a href="{{ route('layanan.index') }}" class="nav-item" data-label="Layanan Surat">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="nav-label">Layanan Surat</span>
                    </a>

                    <a href="{{ route('produk.index') }}" class="nav-item" data-label="Katalog Produk">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="nav-label">Katalog Produk</span>
                    </a>

                    <a href="{{ route('booking.index') }}" class="nav-item" data-label="Booking Saya">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="nav-label">Booking Saya</span>
                    </a>

                    <a href="#" class="nav-item" data-label="Pengaduan Saya" id="pengaduanWargaLink">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="nav-label">Pengaduan Saya</span>
                    </a>
                @endif
            @endauth

            @auth
                @if (Auth::user()->role == 'admin')
                    <div class="nav-section-label">Admin</div>

                    <a href="{{ route('users.index') }}" class="nav-item" data-label="Manajemen User">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="nav-label">Manajemen User</span>
                    </a>

                    <a href="{{ route('analisis.warga') }}" class="nav-item" data-label="Analisis Warga">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="nav-label">Analisis Warga</span>
                    </a>

                    <a href="{{ route('settings') }}" class="nav-item" data-label="Pengaturan">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="nav-label">Pengaturan</span>
                    </a>
                @endif
            @endauth
        </nav>

        <!-- Logout -->
        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" data-label="Logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="logout-label">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content" id="mainContent">

        <!-- Header sticky -->
        <div class="page-header">
            <div class="page-header-dot"></div>
            <h2>@yield('title', 'Dashboard')</h2>
        </div>

        <!-- Loading Skeleton -->
        <div id="skeletonLoader" style="padding: 24px;">
            <div class="skeleton" style="height: 120px; margin-bottom: 20px;"></div>
            <div class="skeleton" style="height: 80px; margin-bottom: 16px;"></div>
            <div class="skeleton" style="height: 80px; margin-bottom: 16px;"></div>
            <div class="skeleton" style="height: 80px; margin-bottom: 16px;"></div>
            <div class="skeleton" style="height: 60px;"></div>
        </div>

        <!-- Konten Utama -->
        <div id="mainContentWrapper" style="padding: 24px; display: none;">
            @yield('content')
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const hamburger = document.getElementById('hamburgerBtn');
        const overlay = document.getElementById('overlay');
        const collapseBtn = document.getElementById('collapseBtn');
        const skeletonLoader = document.getElementById('skeletonLoader');
        const mainContentWrapper = document.getElementById('mainContentWrapper');

        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('collapsed');
        }

        if (collapseBtn) {
            collapseBtn.onclick = function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            };
        }

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }

        if (hamburger) hamburger.onclick = toggleSidebar;
        if (overlay) overlay.onclick = closeSidebar;

        document.querySelectorAll('.nav-item').forEach(link => {
            link.onclick = () => {
                if (window.innerWidth <= 768) closeSidebar();
            };
        });

        window.addEventListener('load', function() {
            setTimeout(function() {
                if (skeletonLoader) {
                    skeletonLoader.style.display = 'none';
                }
                if (mainContentWrapper) {
                    mainContentWrapper.style.display = 'block';
                    mainContentWrapper.classList.add('fade-in');
                }
            }, 500);
        });

        // Alert untuk menu yang belum dibuat (temporary)
        const menuLinks = document.querySelectorAll('#pengaduanLink, #pengaduanWargaLink, #analisisWargaLink');
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Fitur sedang dalam pengembangan!');
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
