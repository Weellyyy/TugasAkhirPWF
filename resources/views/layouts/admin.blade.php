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
    <div class="bg-gray-900 shadow-xl h-screen w-64 text-white flex flex-col hidden md:flex">
        <div class="p-6 text-2xl font-bold tracking-wider text-center border-b border-gray-800">
            POS Admin
        </div>
        <nav class="flex-grow pt-4 flex flex-col gap-2">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 hover:bg-gray-800 transition duration-200">Dashboard</a>
            <a href="{{ route('admin.kategori.index') }}" class="px-6 py-3 hover:bg-gray-800 transition duration-200">Kategori</a>
            <a href="{{ route('admin.produk.index') }}" class="px-6 py-3 hover:bg-gray-800 transition duration-200">Produk</a>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 hover:bg-gray-800 transition duration-200">Manajemen User</a>
            <a href="{{ route('admin.reports.index') }}" class="px-6 py-3 hover:bg-gray-800 transition duration-200">Laporan</a>
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
        <header class="bg-white shadow py-4 px-6 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-800">@yield('title')</h1>
            <div class="flex items-center text-gray-600">
                Halo, {{ auth()->user()->nama }}
            </div>
        </header>

        <!-- Content -->
        <main class="p-6 flex-1 bg-gray-50">
            @yield('content')
        </main>
    </div>

</body>
</html>
