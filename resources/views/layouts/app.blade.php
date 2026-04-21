<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cipakat Hub - BUMDes</title>
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
        }

        /* SIDEBAR */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 50;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 72px;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-content.collapsed {
            margin-left: 72px;
        }

        /* TOGGLE BUTTON */
        .toggle-btn {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 12px 16px 0;
        }

        .toggle-btn button {
            background: #334155;
            border: none;
            color: #94a3b8;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .toggle-btn button:hover {
            background: #2563eb;
            color: white;
        }

        .toggle-btn button svg {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed .toggle-btn button svg {
            transform: rotate(180deg);
        }

        /* USER SECTION */
        .user-section {
            padding: 20px 16px;
            border-bottom: 1px solid #334155;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            overflow: hidden;
        }

        .avatar {
            width: 44px;
            height: 44px;
            background: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            flex-shrink: 0;
        }

        .user-info {
            overflow: hidden;
            transition: opacity 0.2s ease, width 0.3s ease;
            white-space: nowrap;
        }

        .sidebar.collapsed .user-info {
            opacity: 0;
            width: 0;
        }

        .user-name {
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-email {
            color: #94a3b8;
            font-size: 11px;
            margin-top: 2px;
        }

        .user-role {
            display: inline-block;
            background: #334155;
            color: #94a3b8;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            margin-top: 4px;
        }

        /* NAV ITEMS */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            margin: 3px 10px;
            border-radius: 10px;
            color: #cbd5e1;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        .nav-item.active {
            background: #2563eb;
            color: white;
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
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
            left: 80px;
            background: #1e293b;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            white-space: nowrap;
            z-index: 100;
            border: 1px solid #334155;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* LOGOUT */
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: calc(100% - 20px);
            margin: 4px 10px;
            padding: 11px 16px;
            border-radius: 10px;
            color: #f87171;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #fecaca;
        }

        .logout-btn svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .logout-label {
            transition: opacity 0.2s ease;
        }

        .sidebar.collapsed .logout-label {
            opacity: 0;
            width: 0;
        }

        /* HAMBURGER - Mobile only */
        .hamburger {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 60;
            background: #2563eb;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 45;
        }

        /* LOADING SKELETON */
        .skeleton {
            animation: skeleton-loading 1s linear infinite alternate;
            background: linear-gradient(90deg, #e2e8f0 0%, #f1f5f9 50%, #e2e8f0 100%);
            background-size: 200% 100%;
            border-radius: 8px;
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* FADE IN ANIMATION */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .sidebar {
                width: 280px !important;
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

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <!-- Hamburger Mobile -->
    <button class="hamburger" id="hamburgerBtn">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="overlay" id="overlay"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <!-- Tombol collapse -->
        <div class="toggle-btn">
            <button id="collapseBtn" title="Collapse sidebar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- User Info -->
        <div class="user-section">
            <div class="avatar">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div class="user-email">{{ Auth::user()->email ?? 'admin@cipakat.com' }}</div>
                <span class="user-role">{{ ucfirst(Auth::user()->role ?? 'admin') }}</span>
            </div>
        </div>

        <!-- Nav -->
        <nav style="flex: 1;">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                data-label="Dashboard">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="nav-label">Dashboard</span>
            </a>

            <!-- Profil Saya (Semua Role) -->
            <a href="{{ route('profile.edit') }}" class="nav-item" data-label="Profil Saya">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="nav-label">Profil Saya</span>
            </a>


            <!-- Pengumuman (Hanya untuk Admin & Petugas yang perlu manajemen) -->
            @auth
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                    <a href="{{ route('pengumuman.admin') }}" class="nav-item" data-label="Manajemen Pengumuman">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span class="nav-label">Manajemen Pengumuman</span>
                    </a>
                @endif
            @endauth

            @auth
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                    <a href="{{ route('layanan.admin') }}" class="nav-item" data-label="Manajemen Surat">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="nav-label">Manajemen Surat</span>
                    </a>
                @else
                    <a href="{{ route('layanan.index') }}" class="nav-item" data-label="Layanan Surat">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="nav-label">Layanan Surat</span>
                    </a>
                @endif
            @endauth

            @auth
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                    <a href="{{ route('produk.admin') }}" class="nav-item" data-label="Manajemen Produk">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z" />
                        </svg>
                        <span class="nav-label">Manajemen Produk</span>
                    </a>
                @else
                    <a href="{{ route('produk.index') }}" class="nav-item" data-label="Katalog Produk">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="nav-label">Katalog Produk</span>
                    </a>
                @endif
            @endauth

            @auth
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                    <!-- Manajemen Booking untuk Admin & Petugas -->
                    <a href="{{ route('booking.admin') }}" class="nav-item" data-label="Manajemen Booking">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="nav-label">Manajemen Booking</span>
                    </a>
                @else
                    <!-- Booking Saya untuk Warga -->
                    <a href="{{ route('booking.index') }}" class="nav-item" data-label="Booking Saya">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="nav-label">Booking Saya</span>
                    </a>
                @endif
            @endauth

            @auth
                @if (in_array(Auth::user()->role, ['admin', 'petugas']))
                    <a href="{{ route('keuangan.index') }}" class="nav-item" data-label="Keuangan">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="nav-label">Keuangan</span>
                    </a>
                @endif
            @endauth

            @auth
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('laporan.index') }}" class="nav-item" data-label="Laporan">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="nav-label">Laporan</span>
                    </a>

                    <a href="{{ route('users.index') }}" class="nav-item" data-label="Manajemen User">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="nav-label">Manajemen User</span>
                    </a>
                @endif
            @endauth

            @auth
                @if (Auth::user()->role == 'admin')
                    <!-- ... menu lain ... -->
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
        <div style="padding: 12px 0;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" data-label="Logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="logout-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT dengan Loading Skeleton -->
    <main class="main-content" id="mainContent">
        <div style="background: white; border-bottom: 1px solid #e2e8f0; padding: 16px 24px;">
            <h2 style="font-size: 18px; font-weight: 600; color: #0f172a;">@yield('title', 'Dashboard')</h2>
        </div>

        <!-- Loading Skeleton -->
        <div id="skeletonLoader" style="padding: 24px;">
            <div class="skeleton" style="height: 120px; margin-bottom: 20px;"></div>
            <div class="skeleton" style="height: 80px; margin-bottom: 16px;"></div>
            <div class="skeleton" style="height: 80px; margin-bottom: 16px;"></div>
            <div class="skeleton" style="height: 80px; margin-bottom: 16px;"></div>
            <div class="skeleton" style="height: 60px;"></div>
        </div>

        <!-- Konten Utama (awal disembunyikan) -->
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

        // Simpan state collapse ke localStorage
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('collapsed');
        }

        // Collapse toggle (desktop)
        if (collapseBtn) {
            collapseBtn.onclick = function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            };
        }

        // Mobile hamburger
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

        // Loading Skeleton Effect - hilangkan skeleton setelah halaman dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (skeletonLoader) {
                    skeletonLoader.style.display = 'none';
                }
                if (mainContentWrapper) {
                    mainContentWrapper.style.display = 'block';
                    mainContentWrapper.classList.add('fade-in');
                }
            }, 500); // 500ms delay untuk efek loading
        });
    </script>

    @stack('scripts')
</body>

</html>
