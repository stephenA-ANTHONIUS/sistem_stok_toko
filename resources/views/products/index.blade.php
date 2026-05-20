@extends('layouts.app')
@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Produk</h2>
            <p class="text-sm text-gray-500">Kelola semua data produk toko.</p>
        </div>
        <a href="{{ route('products.create') }}"
           class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / ID produk..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>

            {{-- FITUR BARU: Dropdown Urutan Stok --}}
            <select name="sort_stok" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Urutkan Stok</option>
                <option value="asc" {{ request('sort_stok') === 'asc' ? 'selected' : '' }}>Stok: Sedikit ke Banyak</option>
                <option value="desc" {{ request('sort_stok') === 'desc' ? 'selected' : '' }}>Stok: Banyak ke Sedikit</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg text-sm hover:bg-gray-800 transition">
                <i class="fa-solid fa-search"></i> Cari
            </button>
            
            @if(request('search') || request('status') || request('sort_stok'))
            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Produk</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">ID Produk</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Stok</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Harga Offline</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Harga Online</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $product->{'nama_produk'} }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $product->id_produk }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $product->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $product->status ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-medium
                            {{ $product->jumlah_stok < 5 ? 'text-red-600' : 'text-gray-800' }}">
                            {{ number_format($product->jumlah_stok, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600">Rp {{ number_format($product->harga_offline, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">Rp {{ number_format($product->harga_online, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('products.edit', $product) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('products.toggle-status', $product) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs transition
                                            {{ $product->status ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                                        <i class="fa-solid {{ $product->status ? 'fa-ban' : 'fa-check' }}"></i>
                                        {{ $product->status ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                            <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                            Belum ada produk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection