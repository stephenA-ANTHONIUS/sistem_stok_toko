@extends('layouts.app')
@section('title', 'Preview Resi')
@section('page-title', 'Preview Hasil Scan Resi')

@section('content')
@php
    // Fallback aman jika variabel tidak terdefinisi
    $mappedItems = $mappedItems ?? [];
    $data        = $data ?? ['image_path' => '', 'raw_text' => ''];
    $products    = $products ?? collect();
@endphp

@if(empty($mappedItems) && empty($data['raw_text']))
    <div class="max-w-2xl">
        <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm px-5 py-4 rounded-xl flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
            <div>
                <p class="font-semibold">Sesi habis atau tidak ada data untuk ditampilkan</p>
                <p class="mt-1 text-amber-700">Silakan upload ulang file PDF resi Anda.</p>
                <a href="{{ route('shipping-labels.create') }}"
                   class="inline-block mt-3 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg transition">
                    <i class="fa-solid fa-upload mr-1"></i> Upload Resi
                </a>
            </div>
        </div>
    </div>
@else
<div class="max-w-4xl" x-data="previewItems(
    {{ json_encode(array_map(fn($i) => ['produk' => $i['produk'] ?? '', 'qty' => $i['qty'] ?? 1, 'harga_online' => $i['harga_online'] ?? 0, 'stock' => $i['stock'] ?? 0,
      'original' => $i['original'] ?? $i['produk'], 'matched' => $i['matched'] ?? false], $mappedItems)) }},
    {{ json_encode($products->map(fn($p) => ['id' => $p->id, 'nama_produk' => $p->nama_produk, 'harga_online' => $p->harga_online ?? $p->harga ?? 0, 'stock' => $p->jumlah_stok ?? 0])->values()) }}
)">

    {{-- Banner info --}}
    <div class="mb-4 flex items-start gap-3 bg-blue-50 border border-blue-200 text-blue-800 text-sm px-4 py-3 rounded-xl">
        <i class="fa-solid fa-circle-info mt-0.5 shrink-0"></i>
        <div>
            <p class="font-semibold">Periksa hasil scan sebelum menyimpan</p>
            <p class="text-blue-600 mt-0.5">Sistem telah mencocokkan nama produk dari PDF dengan database. Produk yang <span class="font-medium text-amber-600">berwarna kuning

            </span> belum cocok — pilih manual dari dropdown. Stok akan dikurangi otomatis setelah klik <strong>Simpan</strong>.</p>
        </div>
    </div>

    {{-- Peringatan produk tidak cocok --}}
    @php $unmatchedCount = count(array_filter($mappedItems, fn($i) => !($i['matched'] ?? false))); @endphp
    @if($unmatchedCount > 0)
    <div class="mb-4 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl">
        <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
        <p>Terdapat <strong>{{ $unmatchedCount }} produk</strong> yang tidak ditemukan di database. Silakan pilih produk yang sesuai dari dropdown atau hapus baris tersebut.</p>
    </div>
    @endif
    <div x-show="items.some(i => i.stock && i.qty > i.stock)" class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl" x-cloak>
        <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
        <p>Terdapat produk dengan stok tidak mencukupi. Periksa kembali jumlah pesanan atau pilih produk lain.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-800">Rekap Produk Hasil Scan</h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    File: <span class="font-medium">{{ basename($data['image_path']) }}</span>
                    &bull; <span x-text="items.length + ' produk terdeteksi'"></span>
                </p>
            </div>
            <button type="button" @click="addItem"
                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                <i class="fa-solid fa-plus"></i> Tambah Baris
            </button>
        </div>

        <form method="POST" action="{{ route('shipping-labels.confirm') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="image_path" value="{{ $data['image_path'] }}">
            <input type="hidden" name="raw_text" value="{{ $data['raw_text'] }}">

            <div class="space-y-2">

                {{-- Kolom header diperlebar menjadi 12 Grid (Nama, Harga, Qty, Subtotal, Aksi) --}}
                <div class="grid grid-cols-12 gap-2 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    <div class="col-span-5">Nama Produk</div>
                    <div class="col-span-2 text-right">Harga</div>
                    <div class="col-span-2 text-center">Qty</div>
                    <div class="col-span-2 text-right">Subtotal</div>
                    <div class="col-span-1 text-center"></div>
                </div>

                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-12 gap-2 items-center rounded-xl px-3 py-2.5"
                         :class="item._unmatched ? 'bg-amber-50 border border-amber-200' : 'bg-gray-50 border border-transparent'">

                        {{-- Dropdown produk --}}
                        <div class="col-span-5">
                            <select :name="'items[' + index + '][produk]'"
                                    x-model="item.produk"
                                    required
                                    @change="updateProductData(index)"
                                    class="w-full px-2 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                    :class="item._unmatched ? 'border-amber-300 bg-amber-50 text-amber-800' : 'border-gray-300 bg-white text-gray-800'">
                                <option value="" disabled>-- Pilih Produk --</option>
                                <option x-show="item._unmatched"
                                        :value="item.produk"
                                        x-text="'Tidak cocok: ' + item._original"
                                        class="text-amber-700">
                                </option>
                                <template x-for="prod in dbProducts" :key="prod.id">
                                    <option :value="prod.nama_produk"
                                            x-text="prod.nama_produk"
                                            :selected="prod.nama_produk === item.produk">
                                    </option>
                                </template>
                            </select>
                            <div class="mt-1 text-xs" :class="item.qty > item.stock ? 'text-red-600' : 'text-gray-500'">
                                <span>Stok: </span><span x-text="item.stock"></span>
                                <template x-if="item.stock && item.qty > item.stock">
                                    <span> • Stok kurang</span>
                                </template>
                            </div>
                            <p x-show="!item._unmatched && item._original && item._original !== item.produk" class="text-xs text-gray-500 mt-1">
                                Dari scan: <span x-text="item._original" class="italic"></span>
                            </p>
                            <p x-show="item._unmatched" class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Dari PDF: <span x-text="item._original" class="italic"></span></span>
                            </p>
                        </div>

                        {{-- Harga Online (Readonly) --}}
                        <div class="col-span-2 text-right text-sm text-gray-600 font-medium pt-1">
                            Rp <span x-text="formatRupiah(item.harga_online)"></span>
                        </div>

                        {{-- Input qty --}}
                        <div class="col-span-2 px-2">
                            <input type="number"
                                   :name="'items[' + index + '][qty]'"
                                   x-model="item.qty"
                                   min="1" required
                                   @input="updateStockWarning(index)"
                                   class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm text-center font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- Subtotal (Harga * Qty) --}}
                        <div class="col-span-2 text-right text-sm font-bold text-gray-800 pt-1">
                            Rp <span x-text="formatRupiah(item.harga_online * (item.qty || 0))"></span>
                        </div>

                        {{-- Tombol hapus --}}
                        <div class="col-span-1 flex justify-center pt-1">
                            <button type="button"
                                    @click="removeItem(index)"
                                    x-show="items.length > 1"
                                    class="text-red-400 hover:text-red-600 transition p-1 rounded">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </template>

                {{-- Empty state --}}
                <div x-show="items.length === 0" class="py-8 text-center text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
                    <i class="fa-solid fa-box-open text-2xl mb-2 block"></i>
                    <p class="text-sm">Tidak ada produk terdeteksi</p>
                    <button type="button" @click="addItem" class="mt-2 text-blue-600 text-sm underline">+ Tambah baris manual</button>
                </div>
            </div>

            {{-- Ringkasan total --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 mt-4">
                <div class="flex justify-between text-sm font-semibold text-gray-700">
                    <span>Total Jenis Produk</span>
                    <span x-text="items.length + ' item'"></span>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mt-1">
                    <span>Total Qty Keseluruhan</span>
                    <span x-text="items.reduce((s, i) => s + (+i.qty || 0), 0) + ' pcs'"></span>
                </div>
                <div class="flex justify-between text-base font-bold text-blue-700 mt-2 pt-2 border-t border-gray-200">
                    <span>Estimasi Total Omset Online</span>
                    <span>Rp <span x-text="formatRupiah(items.reduce((s, i) => s + ((i.harga_online || 0) * (i.qty || 0)), 0))"></span></span>
                </div>
            </div>

            {{-- Log PDF --}}
            <div class="bg-gray-50 rounded-xl p-4 mt-4">
                <details>
                    <summary class="text-sm font-medium text-gray-600 cursor-pointer select-none">
                        <i class="fa-solid fa-file-lines mr-1"></i> Log Teks Mentah PDF
                    </summary>
                    <pre class="mt-3 text-xs text-gray-500 whitespace-pre-wrap max-h-60 overflow-y-auto font-mono leading-relaxed">{{ $data['raw_text'] }}</pre>
                </details>
            </div>

            {{-- Tombol aksi --}}
            <div class="flex gap-3 pt-2 mt-4">
                <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan & Kurangi Stok
                </button>
                <a href="{{ route('shipping-labels.create') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i> Upload Ulang
                </a>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function previewItems(initialItems, dbProducts) {
    const items = initialItems.map(item => {
        const matched = item.matched === true || item.matched === '1';
        const harga = parseFloat(item.harga_online) || 0;

        return {
            produk:       item.produk || '',
            qty:          item.qty || 1,
            harga_online: harga,
            stock:        Number(item.stock || 0),
            _matched:     matched,
            _unmatched:   !matched,
            _original:    item.original || item.produk || '',
        };
    });

    return {
        items,
        dbProducts,

        addItem() {
            this.items.push({ produk: '', qty: 1, harga_online: 0, stock: 0, _matched: false, _unmatched: true, _original: '' });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        updateProductData(index) {
            const item = this.items[index];
            const selectedProduct = this.dbProducts.find(p => p.nama_produk === item.produk);

            if (selectedProduct) {
                item.harga_online = parseFloat(selectedProduct.harga_online) || 0;
                item.stock = Number(selectedProduct.stock || 0);
                item._matched = true;
                item._unmatched = false;
                item._stockLow = Number(item.qty || 0) > item.stock;
            }
        },

        updateStockWarning(index) {
            const item = this.items[index];
            item._stockLow = Number(item.qty || 0) > Number(item.stock || 0);
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka || 0);
        }
    };
}
</script>
@endsection