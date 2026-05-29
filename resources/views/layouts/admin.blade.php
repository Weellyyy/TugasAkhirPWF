<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Admin - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <div class="bg-gray-900 shadow-xl h-screen w-64 text-white flex flex-col hidden md:flex no-print">
        <div class="p-6 text-2xl font-bold tracking-wider text-center border-b border-gray-800">
            POS <span class="text-teal-400">Admin</span>
        </div>
        <nav class="flex-grow pt-4 flex flex-col gap-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-6 py-3 transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-teal-400 font-semibold bg-gray-950 border-l-4 border-teal-500' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                Dashboard
            </a>
            <a href="{{ route('pos') }}" 
               class="px-6 py-3 transition duration-200 text-gray-300 hover:text-white hover:bg-gray-800 border-l-4 border-transparent hover:border-teal-500">
                Buka Aplikasi POS
            </a>
            <a href="{{ route('admin.kategori.index') }}" 
               class="px-6 py-3 transition duration-200 {{ request()->routeIs('admin.kategori.*') ? 'text-teal-400 font-semibold bg-gray-950 border-l-4 border-teal-500' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                Kategori
            </a>
            <a href="{{ route('admin.produk.index') }}" 
               class="px-6 py-3 transition duration-200 {{ request()->routeIs('admin.produk.*') ? 'text-teal-400 font-semibold bg-gray-950 border-l-4 border-teal-500' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                Stok Barang
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="px-6 py-3 transition duration-200 {{ request()->routeIs('admin.users.*') ? 'text-teal-400 font-semibold bg-gray-950 border-l-4 border-teal-500' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                Manajemen Kasir
            </a>
            <a href="{{ route('admin.reports.index') }}" 
               class="px-6 py-3 transition duration-200 {{ request()->routeIs('admin.reports.*') ? 'text-teal-400 font-semibold bg-gray-950 border-l-4 border-teal-500' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                Laporan
            </a>
        </nav>
        <div class="p-4 border-t border-gray-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded transition duration-200">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-y-auto">
        <!-- Header -->
        <header class="bg-white shadow py-4 px-6 flex justify-between items-center no-print">
            <h1 class="text-2xl font-semibold text-gray-800">@yield('title')</h1>
            <div class="flex items-center gap-6 text-gray-600">
                <!-- Realtime Clock -->
                <div class="text-right no-print">
                    <div id="admin-clock" class="text-lg font-bold text-gray-800 tabular-nums"></div>
                    <div id="admin-date" class="text-xs text-gray-400"></div>
                </div>
                <span class="text-sm font-medium">Halo, <strong>{{ auth()->user()->nama }}</strong></span>
            </div>
        </header>
        <script>
            (function() {
                const hariIndo = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                const bulanIndo = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                function updateAdminClock() {
                    const now = new Date();
                    const jam = String(now.getHours()).padStart(2,'0');
                    const menit = String(now.getMinutes()).padStart(2,'0');
                    const detik = String(now.getSeconds()).padStart(2,'0');
                    const hari = hariIndo[now.getDay()];
                    const tgl = now.getDate();
                    const bulan = bulanIndo[now.getMonth()];
                    const tahun = now.getFullYear();
                    const clockEl = document.getElementById('admin-clock');
                    const dateEl = document.getElementById('admin-date');
                    if (clockEl) clockEl.textContent = jam + ':' + menit + ':' + detik;
                    if (dateEl) dateEl.textContent = hari + ', ' + tgl + ' ' + bulan + ' ' + tahun;
                }
                updateAdminClock();
                setInterval(updateAdminClock, 1000);
            })();
        </script>

        <!-- Content -->
        <main class="p-6 flex-1 bg-gray-50">
            @yield('content')
        </main>
    </div>

</body>
</html>
