@extends('layouts.app')
@section('title', 'Review Hasil Upload Resi')
@section('page-title', 'Review Produk Terdeteksi')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('shipping-labels.confirm') }}">
        @csrf
        
        @if(count($matchedItems) > 0)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-green-700 mb-4">
                <i class="fa-solid fa-check-circle mr-2"></i>Produk Ditemukan ({{ count($matchedItems) }})
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pilih</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Saat Ini</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($matchedItems as $index => $item)
                        <tr>
                            <td class="px-4 py-3">
                                <input type="checkbox" name="selected_items[]" value="{{ $index }}" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm"
                                       {{ $item['status'] == 'insufficient' ? 'disabled' : 'checked' }}>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="font-medium">{{ $item['product']->nama_produk }}</div>
                                <div class="text-xs text-gray-500">Terdeteksi: {{ $item['produk'] }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item['qty'] }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="{{ $item['current_stock'] >= $item['qty'] ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $item['current_stock'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($item['status'] == 'available')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Stok Cukup
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Stok Kurang
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if(count($unmatchedItems) > 0)
        <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-yellow-700 mb-4">
                <i class="fa-solid fa-exclamation-triangle mr-2"></i>Produk Tidak Ditemukan ({{ count($unmatchedItems) }})
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-yellow-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-yellow-800 uppercase">Produk Terdeteksi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-yellow-800 uppercase">Qty</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-yellow-800 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($unmatchedItems as $item)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item['produk'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item['qty'] }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Tidak ditemukan di database
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 p-3 bg-yellow-100 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Produk-produk ini tidak akan diproses. Pastikan nama produk di database sesuai dengan yang tertera di PDF.
                </p>
            </div>
        </div>
        @endif

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition">
                <i class="fa-solid fa-check mr-1"></i> Konfirmasi & Proses
            </button>
            <a href="{{ route('shipping-labels.create') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Upload Ulang
            </a>
        </div>
    </form>
</div>
@endsection