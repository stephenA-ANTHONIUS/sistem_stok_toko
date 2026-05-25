@extends('layouts.app')
@section('title', 'Tambah Stok Masuk')
@section('page-title', 'Tambah Stok Masuk')

@section('content')
<div class="max-w-3xl" x-data="stockForm({{ $products->map(fn($p) => [
    'id'          => $p->id,
    'nama_produk' => $p->nama_produk,
    'jumlah_stok' => $p->jumlah_stok,
])->values() }})">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
        <h2 class="text-base font-bold text-gray-800">Form Tambah Stok Masuk</h2>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('stock-entries.store') }}" class="space-y-5">
            @csrf
            
            {{-- Tanggal & Catatan --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk <span class="text-red-500">*</span></label>
                    <input type="date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <input type="text" name="notes" value="{{ old('notes') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Opsional...">
                </div>
            </div>

            {{-- Bagian Pencarian Produk --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Daftar Produk</h3>
                <p class="text-xs text-gray-500 mb-3">Cari dan klik tombol tambah untuk memasukkan ke stok masuk.</p>

                {{-- Search Input --}}
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

                    {{-- Hasil pencarian dropdown --}}
                    <div x-show="showResults && searchResults.length > 0"
                         x-transition
                         @click.outside="closeSearch"
                         class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-y-auto max-h-64">
                        <template x-for="product in searchResults" :key="product.id">
                            <div class="px-4 py-3 text-sm hover:bg-slate-50 flex items-center justify-between border-b border-gray-100 last:border-0 transition">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800" x-text="product.nama_produk"></span>
                                    <span class="text-xs text-gray-500 mt-1">
                                        Stok saat ini: <span x-text="product.jumlah_stok" class="font-semibold text-gray-600"></span>
                                    </span>
                                </div>
                                <button type="button"
                                        @click="addProduct(product)"
                                        class="px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition">
                                    <i class="fa-solid fa-plus"></i> Tambah
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Tidak ditemukan --}}
                    <div x-show="showResults && searchResults.length === 0 && searchTerm.length >= 2 && !loading"
                         x-transition
                         class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg px-4 py-3 text-sm text-gray-500">
                        Produk tidak ditemukan.
                    </div>
                </div>

                {{-- Daftar item dipilih --}}
                <div x-show="items.length > 0" class="space-y-2">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">
                            <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                            
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800" x-text="item.nama_produk"></p>
                                <p class="text-xs text-gray-400">Stok awal: <span x-text="item.jumlah_stok"></span></p>
                            </div>

                            <div class="w-28">
                                <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty"
                                       min="1" required placeholder="Qty"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center">
                            </div>

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

            <div class="flex gap-3 pt-2">
                <button type="submit" :disabled="items.length === 0"
                        class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan
                </button>
                <a href="{{ route('stock-entries.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function stockForm(initialProducts, initialItems = []) {
    return {
        searchTerm:    '',
        searchResults: [],
        showResults:   false,
        loading:       false,
        items:         initialItems,
        debounceTimer: null,
        allProducts:   initialProducts,

        onSearch() {
            clearTimeout(this.debounceTimer);
            const q = this.searchTerm.trim().toLowerCase();

            if (q.length < 2) {
                this.searchResults = [];
                this.showResults   = false;
                return;
            }

            this.loading = true;

            this.debounceTimer = setTimeout(() => {
                this.searchResults = this.allProducts.filter(p =>
                    p.nama_produk.toLowerCase().includes(q)
                );
                this.showResults = true;
                this.loading     = false;
            }, 200);
        },

        addProduct(product) {
            const existing = this.items.find(i => i.product_id === product.id);
            if (existing) {
                existing.qty = parseInt(existing.qty) + 1;
            } else {
                this.items.push({
                    product_id:  product.id,
                    nama_produk: product.nama_produk,
                    jumlah_stok: product.jumlah_stok,
                    qty:         1,
                });
            }
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