<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Sembunyikan panah (spin button) pada input number */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media print {
            header, .flex-1, .no-print {
                display: none !important;
            }
            .print-modal-active {
                display: block !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background: white !important;
                z-index: 9999 !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-modal-active > div {
                box-shadow: none !important;
                border: none !important;
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                border-radius: 0 !important;
            }
            .print-receipt-active {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            html, body {
                height: auto !important;
                overflow: visible !important;
                background: white !important;
            }
        }
    </style>
</head>
<body class="bg-[#f4f6f8] h-screen flex flex-col overflow-hidden" x-data="posApp()">

    <!-- Header -->
    <header class="bg-white shadow-sm py-3 px-6 flex justify-between items-center z-10 no-print border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#111827] rounded-xl flex items-center justify-center text-white shadow-sm shadow-[#111827]/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h1 class="text-[22px] font-black text-[#0f172a] tracking-tight">POS <span class="text-[#0f172a]">Kasir</span></h1>
        </div>
        
        <div class="flex items-center gap-6">
            <!-- Search Bar in Header -->
            <div class="relative w-80">
                <input type="text" x-model="searchQuery" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2.5 bg-[#f1f5f9] text-gray-700 rounded-full border-none focus:ring-2 focus:ring-blue-500 text-sm font-medium transition-all placeholder-gray-400">
                <svg class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Realtime Clock -->
            <div class="text-center ml-2">
                <div id="pos-clock" class="text-lg font-black text-gray-900 tabular-nums leading-none"></div>
                <div id="pos-date" class="text-[11px] text-gray-400 mt-1 font-medium"></div>
            </div>
            
            <div class="h-8 w-px bg-gray-200"></div>
            
            <span class="text-gray-700 font-semibold text-sm">{{ auth()->user()->nama }}</span>
            
            <div class="flex items-center gap-3">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="bg-[#eff6ff] hover:bg-blue-100 text-blue-600 font-bold py-2 px-4 rounded-xl transition duration-200 text-sm">
                        Panel Admin
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-[#fef2f2] hover:bg-red-100 text-red-600 font-bold py-2 px-4 rounded-xl transition duration-200 text-sm">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>
    <script>
        (function() {
            const hariIndo = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const bulanIndo = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            function updatePosClock() {
                const now = new Date();
                const jam = String(now.getHours()).padStart(2,'0');
                const menit = String(now.getMinutes()).padStart(2,'0');
                const detik = String(now.getSeconds()).padStart(2,'0');
                const hari = hariIndo[now.getDay()];
                const tgl = now.getDate();
                const bulan = bulanIndo[now.getMonth()];
                const tahun = now.getFullYear();
                const clockEl = document.getElementById('pos-clock');
                const dateEl = document.getElementById('pos-date');
                if (clockEl) clockEl.textContent = jam + ':' + menit + ':' + detik;
                if (dateEl) dateEl.textContent = hari + ', ' + tgl + ' ' + bulan + ' ' + tahun;
            }
            updatePosClock();
            setInterval(updatePosClock, 1000);
        })();
    </script>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="flex-1 flex overflow-hidden p-6 gap-6">
        
        <!-- Left: Products & Filters -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Category Pills -->
            <div class="flex gap-3 overflow-x-auto mb-6 pb-2 no-scrollbar">
                <button @click="selectedKategori = ''" 
                        :class="selectedKategori === '' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 shadow-sm'"
                        class="px-6 py-2.5 rounded-full font-bold text-sm transition-all whitespace-nowrap">
                    Semua Kategori
                </button>
                @foreach($kategoris as $k)
                    <button @click="selectedKategori = '{{ $k->id }}'" 
                            :class="selectedKategori === '{{ $k->id }}' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 shadow-sm'"
                            class="px-6 py-2.5 rounded-full font-bold text-sm transition-all whitespace-nowrap">
                        {{ $k->nama_kategori }}
                    </button>
                @endforeach
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                    <template x-for="produk in filteredProduks" :key="produk.id">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition duration-300 cursor-pointer flex flex-col relative group" @click="addToCart(produk)">
                            
                            <!-- Image Container -->
                            <div class="h-40 bg-gray-100 relative overflow-hidden">
                                <template x-if="produk.gambar">
                                    <img :src="'/storage/' + produk.gambar" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </template>
                                <template x-if="!produk.gambar">
                                    <div class="flex flex-col items-center justify-center h-full text-gray-300 bg-gray-50">
                                        <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                </template>
                                
                                <!-- Stock Badge -->
                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-md text-[10px] font-extrabold text-gray-700 shadow-sm border border-white/50">
                                    <span x-show="produk.stok > 90" class="text-green-600">Stok Banyak</span>
                                    <span x-show="produk.stok <= 90">Stok: <span x-text="produk.stok"></span></span>
                                </div>
                            </div>
                            
                            <!-- Details -->
                            <div class="p-4 flex-1 flex flex-col bg-white">
                                <h3 class="text-gray-900 font-bold text-[15px] mb-1 line-clamp-2 leading-snug" x-text="produk.nama_produk"></h3>
                                <p class="text-blue-600 font-extrabold mt-auto text-[15px]" x-text="formatRupiah(produk.harga)"></p>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Empty State -->
                <div x-show="filteredProduks.length === 0" class="flex flex-col items-center justify-center py-20 text-gray-400">
                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <p class="text-lg font-medium text-gray-500">Tidak ada produk ditemukan.</p>
                </div>
            </div>
        </div>

        <!-- Right: Cart Sidebar -->
        <div class="w-[380px] bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col overflow-hidden shrink-0">
            <!-- Cart Header -->
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-3 text-gray-900">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <h2 class="text-lg font-bold">Keranjang</h2>
                </div>
                <button @click="cart = []" x-show="cart.length > 0" class="text-gray-400 hover:text-red-500 transition-colors p-2 hover:bg-red-50 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>

            <!-- Cart Items Area -->
            <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3 custom-scrollbar bg-[#fafbfc]">
                
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex gap-3 bg-white p-3.5 rounded-2xl shadow-[0_2px_8px_-3px_rgba(0,0,0,0.05)] border border-gray-100 items-center hover:border-blue-100 transition-colors">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 line-clamp-1 mb-1" x-text="item.nama_produk"></h4>
                            <p class="text-[13px] text-gray-500 font-medium" x-text="formatRupiah(item.harga)"></p>
                        </div>
                        
                        <div class="flex items-center gap-1.5 bg-gray-50 rounded-xl p-1 border border-gray-100">
                            <button @click="updateQty(index, -1)" class="w-7 h-7 rounded-lg bg-white text-gray-600 hover:bg-gray-200 shadow-sm flex items-center justify-center font-bold transition-colors">-</button>
                            <span class="text-[13px] font-bold w-6 text-center text-gray-800" x-text="item.qty"></span>
                            <button @click="updateQty(index, 1)" class="w-7 h-7 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm flex items-center justify-center font-bold transition-colors">+</button>
                        </div>
                    </div>
                </template>
                
                <!-- Empty State -->
                <div x-show="cart.length === 0" class="flex-1 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Keranjang masih kosong.<br>Mulai tambahkan produk!</p>
                </div>
            </div>

            <!-- Cart Summary & Checkout -->
            <div class="p-6 bg-white border-t border-gray-100 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.02)]">
                <div class="flex justify-between mb-3 text-sm">
                    <span class="text-gray-500 font-medium">Subtotal</span>
                    <span class="text-gray-700 font-bold" x-text="formatRupiah(totalCart())"></span>
                </div>
                <div class="flex justify-between mb-5 text-sm">
                    <span class="text-gray-500 font-medium">Biaya Layanan (5%)</span>
                    <span class="text-gray-700 font-bold" x-text="formatRupiah(serviceCharge())"></span>
                </div>
                <div class="flex justify-between mb-6 items-end">
                    <span class="text-gray-900 font-black text-lg">Total Tagihan</span>
                    <span class="text-2xl font-black text-blue-600" x-text="formatRupiah(grandTotal())"></span>
                </div>
                <button @click="showPaymentModal = true" :disabled="cart.length === 0" 
                        class="w-full py-4 rounded-2xl font-bold text-center transition-all duration-200"
                        :class="cart.length === 0 ? 'bg-[#cbd5e1] text-white cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-600/30'">
                    Bayar Pesanan
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="showPaymentModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden m-auto" @click.away="showPaymentModal = false">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                <h3 class="text-lg font-bold text-gray-900">Pembayaran</h3>
                <button @click="showPaymentModal = false" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 w-7 h-7 rounded-full flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="{{ route('pos.checkout') }}" method="POST" class="p-5">
                @csrf
                <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                <input type="hidden" name="kembalian" :value="kembalian">
                
                <div class="mb-4 text-center bg-[#f8fafc] p-4 rounded-2xl border border-gray-100">
                    <p class="text-gray-500 text-xs font-semibold mb-1">Total Tagihan</p>
                    <p class="text-3xl font-black text-blue-600" x-text="formatRupiah(grandTotal())"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-slate-800 text-[11px] font-black uppercase tracking-wide mb-3">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="metode_pembayaran" value="Cash" x-model="paymentMethod" class="peer sr-only" required>
                            <div class="rounded-xl border-[1.5px] border-slate-200 py-3.5 hover:border-slate-300 peer-checked:border-blue-400 peer-checked:bg-blue-50/50 text-center transition-all bg-white shadow-sm flex flex-col items-center justify-center gap-1.5">
                                <svg class="w-6 h-6 text-slate-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span class="font-bold text-[11px] text-slate-500 peer-checked:text-blue-600 uppercase tracking-wide">Tunai</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="metode_pembayaran" value="Qris" x-model="paymentMethod" class="peer sr-only">
                            <div class="rounded-xl border-[1.5px] border-slate-200 py-3.5 hover:border-slate-300 peer-checked:border-blue-400 peer-checked:bg-blue-50/50 text-center transition-all bg-white shadow-sm flex flex-col items-center justify-center gap-1.5 relative">
                                <div class="absolute top-2 right-2.5 w-2 h-2 rounded-full bg-red-400"></div>
                                <svg class="w-6 h-6 text-slate-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span class="font-bold text-[11px] text-slate-500 peer-checked:text-blue-600 uppercase tracking-wide">QRIS</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- QRIS Barcode Section -->
                <div x-show="paymentMethod === 'Qris'" x-transition class="mb-4 flex flex-col items-center justify-center py-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="w-28 h-28 bg-white flex items-center justify-center p-2 mb-3 shadow-sm rounded-xl border border-slate-200">
                        <svg class="w-full h-full text-slate-900" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h-3v2h3v-2zm-3 4h3v2h-3v-2zm-2-2h2v2h-2v-2zm-2-2h2v2h-2v-2zm0 4h2v2h-2v-2zm6-4h2v2h-2v-2zm0 4h2v2h-2v-2z"/></svg>
                    </div>
                    <p class="text-[13px] text-slate-700 font-bold">Scan QRIS untuk membayar</p>
                </div>

                <!-- Cash Input Section -->
                <div x-show="paymentMethod === 'Cash'" x-transition class="mb-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <label class="block text-slate-500 text-[10px] font-black tracking-wide uppercase mb-2">Uang Tunai Diterima (Rp)</label>
                    <input type="number" name="uang_bayar" x-model.number="uangBayar" min="0" class="w-full px-4 py-3.5 bg-white border-[1.5px] border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-0 text-[17px] font-black text-blue-600 text-center mb-4 shadow-sm" placeholder="Contoh: 50000">
                    
                    <label class="block text-slate-500 text-[10px] font-black tracking-wide uppercase mb-2">Pecahan Cepat & Uang Pas</label>
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <button type="button" @click="uangBayar = grandTotal()" class="py-2.5 bg-white border-[1.5px] rounded-xl text-[11px] font-bold transition-all shadow-sm" :class="uangBayar === grandTotal() ? 'border-blue-400 text-blue-600 bg-blue-50/50' : 'border-slate-100 text-blue-700 hover:border-slate-300'">PAS</button>
                        <button type="button" @click="uangBayar = 50000" class="py-2.5 bg-white border-[1.5px] rounded-xl text-[11px] font-bold transition-all shadow-sm" :class="uangBayar === 50000 ? 'border-blue-400 text-blue-600 bg-blue-50/50' : 'border-slate-100 text-slate-500 hover:border-slate-300'">Rp 50K</button>
                        <button type="button" @click="uangBayar = 100000" class="py-2.5 bg-white border-[1.5px] rounded-xl text-[11px] font-bold transition-all shadow-sm" :class="uangBayar === 100000 ? 'border-blue-400 text-blue-600 bg-blue-50/50' : 'border-slate-100 text-slate-500 hover:border-slate-300'">Rp 100K</button>
                        <button type="button" @click="uangBayar = 150000" class="py-2.5 bg-white border-[1.5px] rounded-xl text-[11px] font-bold transition-all shadow-sm" :class="uangBayar === 150000 ? 'border-blue-400 text-blue-600 bg-blue-50/50' : 'border-slate-100 text-slate-500 hover:border-slate-300'">Rp 150K</button>
                        <button type="button" @click="uangBayar = 200000" class="py-2.5 bg-white border-[1.5px] rounded-xl text-[11px] font-bold transition-all shadow-sm" :class="uangBayar === 200000 ? 'border-blue-400 text-blue-600 bg-blue-50/50' : 'border-slate-100 text-slate-500 hover:border-slate-300'">Rp 200K</button>
                        <button type="button" @click="uangBayar = 500000" class="py-2.5 bg-white border-[1.5px] rounded-xl text-[11px] font-bold transition-all shadow-sm" :class="uangBayar === 500000 ? 'border-blue-400 text-blue-600 bg-blue-50/50' : 'border-slate-100 text-slate-500 hover:border-slate-300'">Rp 500K</button>
                    </div>
                    
                    <div class="bg-slate-100/60 rounded-xl px-4 py-3 border border-slate-200/60">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-slate-500 text-[10px] font-black tracking-wide uppercase">Uang Masuk:</span>
                            <span class="font-bold text-slate-700 text-[12px]" x-text="uangBayar > 0 ? formatRupiah(uangBayar) : 'Rp 0'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-700 text-[10px] font-black tracking-wide uppercase">Kembalian:</span>
                            <span class="font-black text-[13px]" :class="kembalian >= 0 ? 'text-blue-600' : 'text-red-500'" x-text="formatRupiah(kembalian > 0 ? kembalian : 0)"></span>
                        </div>
                    </div>
                    <p x-show="uangBayar > 0 && uangBayar < grandTotal()" class="text-[10px] text-red-500 mt-2 font-medium text-center">Uang bayar kurang dari total tagihan!</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="showPaymentModal = false" class="w-1/3 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-bold py-3 rounded-xl transition duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            :disabled="paymentMethod === 'Cash' && (uangBayar < grandTotal() || uangBayar === 0)"
                            class="w-2/3 py-3 rounded-xl transition duration-200 text-sm font-bold shadow-lg"
                            :class="(paymentMethod === 'Cash' && (uangBayar < grandTotal() || uangBayar === 0)) ? 'bg-gray-400 text-white cursor-not-allowed shadow-none' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-600/30'">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function posApp() {
            return {
                semuaProduk: @json($produks),
                searchQuery: '',
                selectedKategori: '',
                cart: [],
                showPaymentModal: false,
                paymentMethod: 'Cash',
                uangBayar: 0,

                get kembalian() {
                    return this.uangBayar - this.grandTotal();
                },

                get filteredProduks() {
                    return this.semuaProduk.filter(p => {
                        const matchQuery = p.nama_produk.toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchKategori = this.selectedKategori === '' || p.kategori_id == this.selectedKategori;
                        return matchQuery && matchKategori;
                    });
                },

                addToCart(produk) {
                    const existingItemIndex = this.cart.findIndex(item => item.id === produk.id);
                    if (existingItemIndex > -1) {
                        if (this.cart[existingItemIndex].qty < produk.stok) {
                            this.cart[existingItemIndex].qty++;
                        } else {
                            alert('Stok tidak mencukupi!');
                        }
                    } else {
                        if (produk.stok > 0) {
                            this.cart.push({
                                id: produk.id,
                                nama_produk: produk.nama_produk,
                                harga: produk.harga,
                                max_stok: produk.stok,
                                qty: 1
                            });
                        }
                    }
                    // Reset modal inputs when cart changes
                    this.uangBayar = 0;
                },

                updateQty(index, change) {
                    const item = this.cart[index];
                    const newQty = item.qty + change;
                    if (newQty > 0) {
                        if (newQty <= item.max_stok) {
                            item.qty = newQty;
                        } else {
                            alert('Stok tidak mencukupi!');
                        }
                    } else {
                        this.cart.splice(index, 1);
                    }
                    this.uangBayar = 0;
                },

                totalCart() {
                    return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0);
                },
                
                serviceCharge() {
                    return this.totalCart() * 0.05;
                },
                
                grandTotal() {
                    return this.totalCart() + this.serviceCharge();
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
                }
            }
        }
    </script>

    @if($receipt)
    <!-- Receipt Modal -->
    <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto" id="receiptModal">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden m-auto relative">
            <button onclick="document.getElementById('receiptModal').remove()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-700 transition duration-200 z-20 no-print">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div id="printable-receipt" class="px-7 py-8 bg-white font-sans text-slate-800">
                <!-- Receipt Header -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">POS Master</h2>
                    <p class="text-xs text-slate-400 mt-1">Jl. Sudirman No. 123, Jakarta Raya<br>Telp: 021-555-0198</p>
                </div>
                
                <!-- Receipt Details -->
                <div class="border-t border-dashed border-slate-200 pt-4 pb-4 text-[13px]">
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-400 font-medium">No. Transaksi:</span>
                        <span class="font-bold text-slate-700">TRX-{{ str_pad($receipt->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-400 font-medium">Tanggal:</span>
                        @php
                            $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            $monthIdx = (int)$receipt->created_at->format('n') - 1;
                            $formattedDate = $receipt->created_at->format('d') . ' ' . $months[$monthIdx] . ' ' . $receipt->created_at->format('Y, H.i');
                        @endphp
                        <span class="font-bold text-slate-700">{{ $formattedDate }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Kasir:</span>
                        <span class="font-bold text-slate-700">{{ $receipt->kasir->nama ?? 'Administrator' }}</span>
                    </div>
                </div>

                <!-- Items -->
                <div class="border-t border-dashed border-slate-200 pt-4 pb-1">
                    @foreach($receipt->detail as $item)
                        <div class="flex justify-between items-start mb-4 text-[13px]">
                            <div>
                                <h4 class="font-bold text-slate-800 mb-1">{{ $item->produk->nama_produk }}</h4>
                                <p class="text-[12px] text-slate-400 font-medium">{{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                            <span class="font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Subtotals -->
                <div class="border-t border-dashed border-slate-200 pt-4 pb-3 text-[13px]">
                    <div class="flex justify-between mb-2.5">
                        <span class="text-slate-400 font-medium">Subtotal:</span>
                        <span class="font-bold text-slate-700">Rp {{ number_format($receipt->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Biaya Layanan (5%):</span>
                        <span class="font-bold text-slate-700">Rp {{ number_format($receipt->total_harga * 0.05, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Totals -->
                <div class="border-t-[1.5px] border-slate-300 pt-4 pb-5">
                    <div class="flex justify-between items-center mb-5">
                        <span class="font-black text-slate-900 tracking-wider text-[15px]">GRAND TOTAL:</span>
                        <span class="text-xl font-black text-blue-600">Rp {{ number_format($receipt->total_harga * 1.05, 0, ',', '.') }}</span>
                    </div>

                    @if($receipt->metode_pembayaran === 'Cash' && session('uang_bayar'))
                        <div class="flex justify-between items-center mb-2.5 text-[13px]">
                            <span class="text-slate-400 font-medium">Uang Diterima:</span>
                            <span class="font-medium text-slate-400">Rp {{ number_format(session('uang_bayar', 0), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[13px] mb-5">
                            <span class="text-blue-600 font-bold">Kembalian:</span>
                            <span class="font-bold text-blue-600">Rp {{ number_format(session('kembalian', 0), 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <!-- Payment Method Card -->
                    <div class="flex justify-between items-center border-[1.5px] border-slate-400/50 rounded-xl py-3.5 px-4 mt-2">
                        <span class="text-[13px] font-semibold text-slate-500">Metode Pembayaran</span>
                        <span class="text-[14px] font-bold text-blue-600">{{ $receipt->metode_pembayaran === 'Cash' ? 'Tunai / Cash' : 'QRIS' }}</span>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-200 mt-2 pt-6 pb-2 text-center text-[11.5px] text-slate-400 font-medium leading-relaxed">
                    <p>Terima kasih atas kunjungan Anda.</p>
                    <p>Barang yang sudah dibeli tidak dapat ditukar.</p>
                </div>
            </div>
            
            <div class="bg-slate-50 p-4 border-t border-slate-100 text-center no-print">
                <button onclick="printReceipt()" class="w-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-800 font-bold py-3.5 rounded-xl shadow-sm transition duration-200 flex items-center justify-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    CETAK ULANG STRUK
                </button>
            </div>
        </div>
    </div>
    
    <script>
        function printReceipt() {
            const modal = document.getElementById('receiptModal');
            const receiptContent = document.getElementById('printable-receipt');
            
            modal.classList.add('print-modal-active');
            receiptContent.classList.add('print-receipt-active');
            
            window.print();
            
            setTimeout(() => {
                modal.classList.remove('print-modal-active');
                receiptContent.classList.remove('print-receipt-active');
            }, 500);
        }
    </script>
    @endif
</body>
</html>
