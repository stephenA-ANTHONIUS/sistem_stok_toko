@extends('layouts.app')
@section('title', 'Edit Transaksi Offline')
@section('page-title', 'Edit Transaksi Offline')

@section('content')
<div class="max-w-3xl" x-data="editForm({{ json_encode($offlineTransaction->items->map(fn($i) => ['product_id' => (string)$i->product_id, 'qty' => $i->qty])->values()) }})">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
        <h2 class="text-base font-bold text-gray-800">Form Edit Transaksi Offline</h2>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('offline-transactions.update', $offlineTransaction) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    {{-- Tanggal bawaan dikunci agar admin tidak bisa memanipulasinya saat proses edit --}}
                    <input type="date" name="transaction_date"
                           value="{{ old('transaction_date', \Carbon\Carbon::parse($offlineTransaction->transaction_date)->format('Y-m-d')) }}" readonly required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan <span class="text-red-500">*</span></label>
                    {{-- Catatan pada halaman edit juga diwajibkan --}}
                    <input type="text" name="notes" value="{{ old('notes', $offlineTransaction->notes) }}" required
                           class="w-full px-4 py-2.5 border @error('notes') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror rounded-xl text-sm focus:outline-none focus:ring-2"
                           placeholder="Wajib Isi nama Pembeli">
                    @error('notes')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Daftar Produk</h3>
                    <button type="button" @click="addItem"
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                        <i class="fa-solid fa-plus"></i> Tambah Produk
                    </button>
                </div>

                <div class="space-y-2">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">
                            <div class="flex-1">
                                <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->{'nama_produk'} }} (Stok: {{ $p->jumlah_stok }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-28">
                                <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty"
                                       min="1" required placeholder="Qty"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="button" @click="removeItem(index)"
                                    :class="items.length === 1 && item.product_id === '' ? 'text-gray-400 cursor-not-allowed' : 'text-red-400 hover:text-red-600'"
                                    :disabled="items.length === 1 && item.product_id === ''"
                                    class="transition p-1">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Perbarui Transaksi
                </button>
                <a href="{{ route('offline-transactions.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function editForm(initialItems) {
    return {
        items: initialItems.length ? initialItems : [{ product_id: '', qty: 1 }],
        addItem() { this.items.push({ product_id: '', qty: 1 }); },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            } else {
                this.items = [{ product_id: '', qty: 1 }];
            }
        }
    }
}
</script>
@endsection