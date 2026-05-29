<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
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
<body class="bg-gray-100 h-screen flex flex-col overflow-hidden" x-data="posApp()">

    <!-- Header -->
    <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center z-10">
        <h1 class="text-2xl font-bold text-blue-600">POS <span class="text-gray-800">Kasir</span></h1>
        <div class="flex items-center gap-6">
            <!-- Realtime Clock -->
            <div class="text-center">
                <div id="pos-clock" class="text-xl font-black text-gray-800 tabular-nums leading-none"></div>
                <div id="pos-date" class="text-xs text-gray-400 mt-0.5"></div>
            </div>
            <span class="text-gray-600 font-medium">Kasir: {{ auth()->user()->nama }}</span>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold py-1.5 px-4 rounded transition duration-200 border border-blue-200">
                    Admin Panel
                </a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-600 font-semibold py-1.5 px-4 rounded transition duration-200">
                    Logout
                </button>
            </form>
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
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="flex-1 flex overflow-hidden">
        
        <!-- Left/Center: Products & Filters -->
        <div class="flex-1 flex flex-col bg-gray-50 p-6 overflow-hidden">
            <!-- Search & Filter -->
            <div class="mb-6 flex gap-4">
                <input type="text" x-model="searchQuery" placeholder="Cari produk..." class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                
                <select x-model="selectedKategori" class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto pr-2">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    <template x-for="produk in filteredProduks" :key="produk.id">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-200 cursor-pointer flex flex-col" @click="addToCart(produk)">
                            <!-- Image Placeholder -->
                            <div class="h-32 bg-gray-200 relative">
                                <template x-if="produk.gambar">
                                    <img :src="'/storage/' + produk.gambar" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!produk.gambar">
                                    <div class="flex items-center justify-center h-full text-gray-400">No Image</div>
                                </template>
                                <div class="absolute top-2 right-2 bg-white bg-opacity-90 px-2 py-1 rounded text-xs font-bold text-gray-700 shadow-sm">
                                    Stok: <span x-text="produk.stok"></span>
                                </div>
                            </div>
                            <!-- Details -->
                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="text-gray-800 font-semibold text-sm mb-1 leading-tight" x-text="produk.nama_produk"></h3>
                                <p class="text-blue-600 font-bold mt-auto" x-text="formatRupiah(produk.harga)"></p>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Empty State -->
                <div x-show="filteredProduks.length === 0" class="text-center py-12 text-gray-500">
                    Tidak ada produk ditemukan.
                </div>
            </div>
        </div>

        <!-- Right: Cart -->
        <div class="w-96 bg-white shadow-xl flex flex-col z-10 border-l border-gray-200">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Keranjang</h2>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full" x-text="cart.length + ' Item'"></span>
            </div>

            <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-800 line-clamp-1" x-text="item.nama_produk"></h4>
                            <p class="text-xs text-gray-500" x-text="formatRupiah(item.harga)"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="updateQty(index, -1)" class="w-6 h-6 rounded bg-gray-200 text-gray-700 flex items-center justify-center hover:bg-gray-300">-</button>
                            <span class="text-sm font-semibold w-4 text-center" x-text="item.qty"></span>
                            <button @click="updateQty(index, 1)" class="w-6 h-6 rounded bg-gray-200 text-gray-700 flex items-center justify-center hover:bg-gray-300">+</button>
                        </div>
                        <div class="text-right ml-2 min-w-[70px]">
                            <p class="text-sm font-bold text-blue-600" x-text="formatRupiah(item.harga * item.qty)"></p>
                        </div>
                    </div>
                </template>
                <div x-show="cart.length === 0" class="flex-1 flex items-center justify-center text-gray-400 text-sm">
                    Keranjang masih kosong
                </div>
            </div>

            <!-- Cart Summary & Checkout -->
            <div class="p-4 border-t border-gray-200 bg-white">
                <div class="flex justify-between mb-4">
                    <span class="text-gray-600 font-semibold">Total</span>
                    <span class="text-xl font-bold text-gray-900" x-text="formatRupiah(totalCart())"></span>
                </div>
                <button @click="showPaymentModal = true" :disabled="cart.length === 0" class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 rounded-lg shadow-md transition duration-200 text-lg">
                    Bayar
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="showPaymentModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden" @click.away="showPaymentModal = false">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">Pembayaran</h3>
                <button @click="showPaymentModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            
            <form action="{{ route('pos.checkout') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                
                <div class="mb-6 text-center">
                    <p class="text-gray-500 text-sm mb-1">Total Tagihan</p>
                    <p class="text-4xl font-black text-gray-900" x-text="formatRupiah(totalCart())"></p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-3">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="Cash" x-model="paymentMethod" class="peer sr-only" required>
                            <div class="rounded-lg border-2 border-gray-200 p-4 hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 text-center transition">
                                <span class="font-bold text-gray-700 peer-checked:text-blue-700">Cash</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="Qris" x-model="paymentMethod" class="peer sr-only">
                            <div class="rounded-lg border-2 border-gray-200 p-4 hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 text-center transition">
                                <span class="font-bold text-gray-700 peer-checked:text-blue-700">QRIS</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Fake QR Code display when Qris is selected -->
                <div x-show="paymentMethod === 'Qris'" class="mb-6 flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <div class="w-32 h-32 bg-white flex items-center justify-center p-2 mb-2 shadow-sm rounded border">
                        <!-- Mock QR code svg -->
                        <svg class="w-full h-full text-gray-800" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h-3v2h3v-2zm-3 4h3v2h-3v-2zm-2-2h2v2h-2v-2zm-2-2h2v2h-2v-2zm0 4h2v2h-2v-2zm6-4h2v2h-2v-2zm0 4h2v2h-2v-2z"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">Scan QRIS untuk membayar</p>
                </div>

                <div class="flex gap-4">
                    <button type="button" @click="showPaymentModal = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-lg transition duration-200">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow transition duration-200">
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
                },

                totalCart() {
                    return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0);
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
                }
            }
        }
    </script>

    @if($receipt)
        <!-- Receipt Modal -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm" id="receiptModal">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-[420px] flex flex-col relative border border-gray-100 overflow-hidden mx-4">
                <!-- Close Button (X) -->
                <button onclick="document.getElementById('receiptModal').remove()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-[#f1f5f9] hover:bg-[#e2e8f0] flex items-center justify-center text-slate-500 hover:text-slate-700 transition duration-200 z-20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <!-- Printable Area -->
                <div id="printable-receipt" class="bg-white font-sans text-gray-800 p-8 pb-5">
                    <!-- Store Header -->
                    <div class="text-center mt-3 mb-5">
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight">POS Master</h2>
                        <p class="text-xs text-slate-400 mt-1">Jl. Sudirman No. 123, Jakarta Raya</p>
                        <p class="text-xs text-slate-400">Telp: 021-555-0198</p>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-dotted border-slate-300 my-4"></div>

                    <!-- Meta Info -->
                    <div class="flex flex-col gap-2.5 text-xs mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-medium">No. Transaksi:</span>
                            <span class="font-bold text-slate-800">TRX-{{ sprintf('%04d', $receipt->id) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-medium">Tanggal:</span>
                            <span class="font-bold text-slate-800">{{ $receipt->created_at->format('j/n/Y, H.i.s') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-medium">Kasir:</span>
                            <span class="font-bold text-slate-800">{{ $receipt->kasir->nama ?? 'Unknown' }}</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-dotted border-slate-300 my-4"></div>

                    <!-- Items List -->
                    <div class="flex flex-col gap-4 mb-4">
                        @foreach($receipt->detail as $d)
                            <div class="flex flex-col">
                                <div class="flex justify-between font-bold text-slate-800 text-sm">
                                    <span>{{ $d->produk->nama_produk ?? 'Produk Dihapus' }}</span>
                                    <span>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5 font-medium">
                                    {{ $d->jumlah }} x Rp {{ number_format($d->harga, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-dotted border-slate-300 my-4"></div>

                    <!-- Grand Total -->
                    <div class="flex justify-between items-center py-1 mb-4">
                        <span class="font-extrabold text-slate-800 tracking-wide text-sm">GRAND TOTAL:</span>
                        <span class="text-xl font-black text-[#6366f1]">Rp {{ number_format($receipt->total_harga, 0, ',', '.') }}</span>
                    </div>

                    <!-- Payment Method Card -->
                    <div class="bg-[#f8fafc] rounded-2xl p-4 flex justify-between items-center border border-[#f1f5f9] mb-5">
                        <span class="text-slate-400 text-xs font-semibold">Metode Pembayaran</span>
                        <span class="font-black text-[#6366f1] text-xs">
                            {{ $receipt->metode_pembayaran === 'Cash' ? 'Tunai / Cash' : 'QRIS' }}
                        </span>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-dotted border-slate-300 my-4"></div>

                    <!-- Footer -->
                    <div class="text-center text-xs text-slate-400 font-medium py-2">
                        <p class="mb-1">Salinan Struk Transaksi</p>
                        <p>Terima kasih atas kunjungan Anda.</p>
                    </div>
                </div>

                <!-- Action Button at Bottom -->
                <div class="bg-[#f8fafc] p-6 border-t border-slate-100 no-print">
                    <button onclick="printReceipt()" class="w-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-800 font-extrabold py-3.5 px-4 rounded-2xl shadow-sm transition duration-200 flex items-center justify-center gap-2 text-xs tracking-wider">
                        <!-- Printer Icon -->
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
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
