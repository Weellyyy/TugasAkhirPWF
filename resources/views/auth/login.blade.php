<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Master - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-slate-50 relative overflow-hidden">
    <!-- Decorative background blobs -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40"></div>
    <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40"></div>
    <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40"></div>

    <div class="bg-white/80 backdrop-blur-2xl p-10 rounded-[2rem] shadow-[0_8px_40px_rgb(0,0,0,0.06)] border border-white w-full max-w-[440px] relative z-10 mx-4">
        
        <!-- Logo / Icon -->
        <div class="flex justify-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 transform -rotate-6 hover:rotate-0 transition-transform duration-300">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>

        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">POS Master</h2>
            <p class="text-slate-500 text-sm mt-2 font-medium">Selamat datang kembali! Silakan masuk.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-xl mb-6 text-[13px] font-medium flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-5 relative">
                <label for="username" class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-2">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 text-slate-700 font-medium transition-all" placeholder="Masukkan username">
                </div>
            </div>

            <div class="mb-8 relative">
                <label for="password" class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 text-slate-700 font-medium transition-all" placeholder="Masukkan password">
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-4 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:ring-4 focus:ring-blue-500/30 transform active:scale-[0.98] transition-all duration-200 shadow-lg shadow-blue-500/25 flex justify-center items-center gap-2 group">
                <span>Masuk Sekarang</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>
</body>
</html>
