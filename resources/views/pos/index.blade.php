<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col overflow-hidden" x-data="posApp()">

    <!-- Header -->
    <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center z-10">
        <h1 class="text-2xl font-bold text-blue-600">POS <span class="text-gray-800">Kasir</span></h1>
        <div class="flex items-center gap-4">
            <span class="text-gray-600 font-medium">Kasir: {{ auth()->user()->nama }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-600 font-semibold py-1.5 px-4 rounded transition duration-200">
                    Logout
                </button>
            </form>
        </div>
    </header>

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
</body>
</html>
