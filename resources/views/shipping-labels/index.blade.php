@extends('layouts.app')
@section('title', 'Transaksi Online')
@section('page-title', 'Transaksi Online (Resi)')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Resi / Transaksi Online</h2>
            <p class="text-sm text-gray-500">Upload dan kelola resi PDF transaksi online.</p>
        </div>
        <a href="{{ route('shipping-labels.create') }}"
           class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
            <i class="fa-solid fa-upload"></i> Upload Resi
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">File PDF</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal Upload</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Produk Terdeteksi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Preview Isi</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($shippingLabels as $label)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i>
                                <span class="text-gray-600 text-xs">{{ basename($label->image_path) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $label->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-700 text-xs max-w-xs">
                            @if($label->items)
                                @foreach(array_slice($label->items, 0, 3) as $item)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 mr-1 mb-1">
                                        {{ $item['produk'] ?? '' }} ({{ $item['qty'] ?? 0 }})
                                    </span>
                                @endforeach
                                @if(count($label->items) > 3)
                                    <span class="text-gray-400">+{{ count($label->items) - 3 }} lainnya</span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        {{-- BAGIAN INI YANG DIPERBAIKI --}}
                        <td class="px-4 py-3 text-gray-500 text-xs max-w-xs truncate">{{ \Illuminate\Support\Str::limit($label->raw_text, 60) }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('shipping-labels.edit', $label) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('shipping-labels.destroy', $label) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus resi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs hover:bg-red-100 transition">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                            <i class="fa-solid fa-file-pdf text-3xl mb-2 block"></i>
                            Belum ada resi diupload.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shippingLabels->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $shippingLabels->links() }}</div>
        @endif
    </div>
</div>
@endsection