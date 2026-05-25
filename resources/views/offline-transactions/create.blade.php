@extends('layouts.app')
@section('title', 'Tambah Transaksi Offline')
@section('page-title', 'Tambah Transaksi Offline')

@section('content')
<div class="max-w-3xl" x-data="transactionForm()">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
        <h2 class="text-base font-bold text-gray-800">Form Tambah Transaksi Offline</h2>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('offline-transactions.store') }}" class="space-y-5">
            @csrf

            {{-- Tanggal & Catatan --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Transaksi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" readonly required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Catatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="notes" value="{{ old('notes') }}" required
                           class="w-full px-4 py-2.5 border @error('notes') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-2"
                           placeholder="Wajib isi nama pembeli">
                    @error('notes')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Daftar Produk --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Daftar Produk</h3>
                <p class="text-xs text-gray-500 mb-3">Cari dan klik produk untuk menambahkan ke transaksi.</p>

                {{-- Search dengan dropdown hasil --}}
                <div class="relative mb-4">
                    <input type="text"
                           x-model="searchTerm"
                           @input="onSearch"
                           @keydown.escape="closeSearch"
                           placeholder="Ketik nama produk..."
                           autocomplete="off"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                    {{-- Spinner --}}
                    <div x-show="loading" class="absolute right-3 top-2.5">
                        <i class="fa-solid fa-spinner fa-spin text-gray-400"></i>
                    </div>

                    {{-- Hasil pencarian --}}
                    <div x-show="showResults && searchResults.length > 0"
                         x-transition
                         @click.outside="closeSearch"
                         class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                        <template x-for="product in searchResults" :key="product.id">
                            <button type="button"
                                    @click="addProduct(product)"
                                    class="w-full text-left px-4 py-3 text-sm hover:bg-blue-50 flex items-center justify-between border-b border-gray-100 last:border-0 transition">
                                <span class="font-medium text-gray-800" x-text="product.nama_produk"></span>
                                <span class="text-xs text-gray-500">
                                    Stok: <span x-text="product.jumlah_stok"
                                                :class="product.jumlah_stok < 5 ? 'text-red-500 font-bold' : 'text-green-600 font-semibold'">
                                    </span>
                                </span>
                            </button>
                        </template>
                    </div>

                    {{-- Tidak ditemukan --}}
                    <div x-show="showResults && searchResults.length === 0 && searchTerm.length >= 2 && !loading"
                         x-transition
                         class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg px-4 py-3 text-sm text-gray-500">
                        Produk tidak ditemukan.
                    </div>
                </div>

                {{-- Daftar item yang sudah dipilih --}}
                <div x-show="items.length > 0" class="space-y-2">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">

                            {{-- Hidden input product_id untuk submit --}}
                            <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">

                            {{-- Nama & info stok --}}
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800" x-text="item.nama_produk"></p>
                                <p class="text-xs text-gray-400">
                                    Stok tersedia:
                                    <span x-text="item.jumlah_stok"
                                          :class="item.jumlah_stok < 5 ? 'text-red-500 font-bold' : 'text-green-600'">
                                    </span>
                                </p>
                            </div>

                            {{-- Input Qty --}}
                            <div class="w-28">
                                <input type="number"
                                       :name="'items[' + index + '][qty]'"
                                       x-model="item.qty"
                                       :max="item.jumlah_stok"
                                       min="1"
                                       required
                                       placeholder="Qty"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center">
                            </div>

                            {{-- Hapus --}}
                            <button type="button" @click="removeItem(index)"
                                    class="text-red-400 hover:text-red-600 transition p-1">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Placeholder kosong --}}
                <div x-show="items.length === 0"
                     class="text-center py-8 border-2 border-dashed border-gray-200 rounded-xl text-gray-400 text-sm">
                    <i class="fa-solid fa-box-open text-2xl mb-2 block"></i>
                    Belum ada produk dipilih. Cari produk di atas.
                </div>
            </div>

            {{-- Tombol aksi --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        :disabled="items.length === 0"
                        class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Transaksi
                </button>
                <a href="{{ route('offline-transactions.index') }}"
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function transactionForm() {
    return {
        searchTerm:    '',
        searchResults: [],
        showResults:   false,
        loading:       false,
        items:         [],
        debounceTimer: null,

        // Semua produk sebagai fallback lokal (tanpa perlu API)
        allProducts: @json($products->map(fn($p) => [
            'id'          => $p->id,
            'nama_produk' => $p->nama_produk,
            'jumlah_stok' => $p->jumlah_stok,
        ])->values()),

        onSearch() {
            clearTimeout(this.debounceTimer);
            const q = this.searchTerm.trim().toLowerCase();

            if (q.length < 2) {
                this.searchResults = [];
                this.showResults   = false;
                return;
            }

            this.loading = true;

            // Filter lokal dari data produk yang sudah diload
            this.debounceTimer = setTimeout(() => {
                this.searchResults = this.allProducts.filter(p =>
                    p.nama_produk.toLowerCase().includes(q)
                );
                this.showResults = true;
                this.loading     = false;
            }, 200);
        },

        addProduct(product) {
            // Jika produk sudah ada di list, tambah qty saja
            const existing = this.items.find(i => i.product_id === product.id);
            if (existing) {
                if (existing.qty < product.jumlah_stok) {
                    existing.qty += 1;
                }
            } else {
                this.items.push({
                    product_id:  product.id,
                    nama_produk: product.nama_produk,
                    jumlah_stok: product.jumlah_stok,
                    qty:         1,
                });
            }

            // Reset search
            this.searchTerm    = '';
            this.searchResults = [];
            this.showResults   = false;
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        closeSearch() {
            this.showResults = false;
        }
    }
}
</script>
@endsection