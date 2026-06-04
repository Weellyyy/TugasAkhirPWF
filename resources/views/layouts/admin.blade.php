<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Admin - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: #f0f2f5; display: flex; height: 100vh; overflow: hidden; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 250px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            height: 100vh;
            flex-shrink: 0;
            border-right: 1px solid #e5e7eb;
        }
        .sidebar-brand {
            padding: 24px 24px 10px;
        }
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
        }
        .sidebar-logo-icon {
            width: 34px; height: 34px;
            background: #111827;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar-logo-icon svg { width: 18px; height: 18px; color: #fff; }
        .sidebar-title { font-size: 18px; font-weight: 700; color: #111827; letter-spacing: 0.2px; }
        .sidebar-subtitle { font-size: 10px; font-weight: 600; color: #9ca3af; letter-spacing: 2px; text-transform: uppercase; }

        .sidebar-label {
            font-size: 10px; font-weight: 700; color: #9ca3af; letter-spacing: 1.5px; text-transform: uppercase;
            padding: 16px 24px 8px;
        }

        .sidebar-nav { flex: 1; padding: 8px 0; display: flex; flex-direction: column; gap: 4px; overflow-y: auto; }
        .nav-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 24px;
            color: #4b5563;
            font-size: 14px; font-weight: 500;
            text-decoration: none;
            border-left: 4px solid transparent;
            margin-right: 16px;
            border-radius: 0 8px 8px 0;
            transition: all 0.2s;
            cursor: pointer;
        }
        .nav-item:hover { color: #111827; background: #f9fafb; }
        .nav-item.active {
            color: #111827;
            background: #f3f4f6;
            border-left-color: #3b82f6;
            font-weight: 600;
        }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; color: #6b7280; transition: color 0.2s; }
        .nav-item:hover svg { color: #111827; }
        .nav-item.active svg { color: #111827; }

        .sidebar-footer { padding: 16px; }
        .btn-logout {
            width: 100%;
            background: #f3f4f6;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: space-between;
            transition: background 0.2s;
        }
        .btn-logout:hover { background: #e5e7eb; }
        
        .logout-user { display: flex; align-items: center; gap: 12px; text-align: left; }
        .logout-avatar {
            width: 36px; height: 36px; border-radius: 50%; background: #e0e7ff; color: #3730a3;
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;
            flex-shrink: 0;
        }
        .logout-details { display: flex; flex-direction: column; }
        .logout-name { font-size: 13px; font-weight: 600; color: #111827; }
        .logout-role { font-size: 10px; color: #6b7280; text-transform: capitalize; }
        
        .btn-logout svg { width: 18px; height: 18px; color: #4b5563; }

        /* ===== MAIN ===== */
        .main-wrap { flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }

        /* TOPBAR */
        .topbar {
            background: #fff;
            padding: 0 24px;
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
            flex-shrink: 0;
        }
        .topbar-title { font-size: 20px; font-weight: 700; color: #111827; }
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .topbar-clock { text-align: right; }
        .topbar-clock-time { font-size: 16px; font-weight: 700; color: #3b82f6; font-variant-numeric: tabular-nums; line-height: 1.2; }
        .topbar-clock-label { font-size: 10px; color: #9ca3af; }
        .topbar-user { display: flex; align-items: center; gap: 8px; }
        .topbar-user-text { font-size: 13px; color: #6b7280; }
        .topbar-user-text strong { color: #111827; }
        .topbar-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff;
        }

        /* CONTENT */
        .main-content { flex: 1; overflow-y: auto; padding: 24px; background: #f0f2f5; }

        /* FAB */
        .fab {
            position: fixed; bottom: 24px; right: 24px;
            width: 48px; height: 48px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 24px;
            box-shadow: 0 4px 16px rgba(59,130,246,0.4);
            cursor: pointer; border: none;
            transition: transform 0.2s, box-shadow 0.2s;
            z-index: 50;
        }
        .fab:hover { transform: scale(1.1); box-shadow: 0 6px 20px rgba(59,130,246,0.5); }

        @media print {
            .sidebar, .topbar, .fab { display: none !important; }
            .main-content { padding: 0; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="sidebar-title">POS Admin</span>
            </div>
            <div class="sidebar-subtitle">Management Suite</div>
        </div>

        <div class="sidebar-label">MAIN MENU</div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('pos') }}" class="nav-item {{ request()->routeIs('pos') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
                Buka Aplikasi POS
            </a>
            <a href="{{ route('admin.kategori.index') }}" class="nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori
            </a>
            <a href="{{ route('admin.produk.index') }}" class="nav-item {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Stok Barang
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Manajemen Kasir
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout" title="Logout">
                    <div class="logout-user">
                        <div class="logout-avatar">{{ strtoupper(substr(auth()->user()->nama ?? 'A', 0, 2)) }}</div>
                        <div class="logout-details">
                            <span class="logout-name">{{ auth()->user()->nama ?? 'Admin' }}</span>
                            <span class="logout-role">{{ auth()->user()->role ?? 'Manager' }}</span>
                        </div>
                    </div>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="main-wrap">
        <!-- TOPBAR -->
        <header class="topbar no-print">
            <h1 class="topbar-title">@yield('title')</h1>
            <div class="topbar-right">
                <div class="topbar-clock">
                    <div class="topbar-clock-time" id="admin-clock">00:00:00</div>
                    <div class="topbar-clock-label" id="admin-clock-label">Memuat...</div>
                </div>
                <div class="topbar-user">
                    <span class="topbar-user-text">Halo, <strong>{{ auth()->user()->nama }}</strong></span>
                    <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}</div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- FAB -->
    @yield('fab')

    <script>
        (function() {
            const hariIndo = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const bulanIndo = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            function updateClock() {
                const now = new Date();
                const jam = String(now.getHours()).padStart(2,'0');
                const menit = String(now.getMinutes()).padStart(2,'0');
                const detik = String(now.getSeconds()).padStart(2,'0');
                const hari = hariIndo[now.getDay()];
                const tgl = now.getDate();
                const bulan = bulanIndo[now.getMonth()];
                const tahun = now.getFullYear();
                const el = document.getElementById('admin-clock');
                const lbl = document.getElementById('admin-clock-label');
                if (el) el.textContent = jam + ':' + menit + ':' + detik;
                if (lbl) lbl.textContent = hari + ', ' + tgl + ' ' + bulan + ' ' + tahun;
            }
            updateClock();
            setInterval(updateClock, 1000);
        })();
    </script>
    @stack('scripts')
</body>
</html>
