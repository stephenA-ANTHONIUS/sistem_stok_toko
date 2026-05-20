@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-6">

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-store text-white text-xs"></i>
                </div>
                <span class="text-xs text-gray-500 font-medium">Transaksi Offline</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($offlineCount, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total semua waktu</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-globe text-white text-xs"></i>
                </div>
                <span class="text-xs text-gray-500 font-medium">Resi Online</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($onlineCount, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total resi diupload</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-white text-xs"></i>
                </div>
                <span class="text-xs text-gray-500 font-medium">Total Semua</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($offlineCount + $onlineCount, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Offline + Online</p>
        </div>
    </div>

    {{-- Tabel Offline Terbaru --}}
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-4">20 Transaksi Offline Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Total</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Produk</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-800">{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs text-xs">
                            {{ $tx->items->map(fn($i) => ($i->product->{'nama_produk'} ?? '?').' ('.$i->qty.')')->join(', ') }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $tx->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada transaksi offline.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Tabel Resi Online Terbaru --}}
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-4">20 Resi Online Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">File PDF</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal Upload</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Produk Terdeteksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($shippingLabels as $label)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            <i class="fa-solid fa-file-pdf text-red-500 mr-1"></i>
                            {{ basename($label->image_path) }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $label->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs max-w-sm">
                            @if($label->items)
                                {{ collect($label->items)->map(fn($i) => ($i['produk'] ?? '?').' ('.$i['qty'].')')->join(', ') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">Belum ada resi diupload.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection
