<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%')
                  ->orWhere('id_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // FITUR BARU: Pengurutan berdasarkan jumlah stok
        if ($request->filled('sort_stok')) {
            $sortDirection = $request->sort_stok === 'asc' ? 'asc' : 'desc';
            $query->orderBy('jumlah_stok', $sortDirection);
        } else {
            // Default urutan jika filter stok tidak dipilih
            $query->latest();
        }

        $products = $query->paginate(15)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Menambahkan validasi unik agar ID Produk tidak duplikat saat input baru
        $data = $request->validate([
            'id_produk'     => ['required', 'string', 'max:255', 'unique:products,id_produk'],
            'nama_produk'   => ['required', 'string', 'max:255'],
            'harga_offline' => ['required', 'numeric', 'min:0'],
            'harga_online'  => ['required', 'numeric', 'min:0'],
            'jumlah_stok'   => ['required', 'integer', 'min:0'],
            'status'        => ['nullable'],
        ], [
            // Kustomisasi pesan error bahasa Indonesia
            'id_produk.unique' => 'ID Produk sudah terdaftar di sistem, gunakan ID yang lain.',
        ]);
        
        $data['status'] = $request->has('status') ? 1 : 0;

        try {
            Product::create($data);
            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->withErrors(['id_produk' => 'ID Produk sudah digunakan.']);
            }
            return back()->withInput()->with('error', 'Terjadi kesalahan database.');
        }
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Menambahkan pengecualian unik saat update agar tidak bentrok dengan ID dirinya sendiri
        $data = $request->validate([
            'id_produk'     => ['required', 'string', 'max:255', 'unique:products,id_produk,' . $product->id],
            'nama_produk'   => ['required', 'string', 'max:255'],
            'harga_offline' => ['required', 'numeric', 'min:0'],
            'harga_online'  => ['required', 'numeric', 'min:0'],
            'jumlah_stok'   => ['required', 'integer', 'min:0'],
            'status'        => ['nullable'],
        ], [
            'id_produk.unique' => 'ID Produk sudah digunakan oleh produk lain.',
        ]);
        
        $data['status'] = $request->has('status') ? 1 : 0;

        try {
            $product->update($data);
            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->withErrors(['id_produk' => 'ID Produk sudah digunakan oleh produk lain.']);
            }
            return back()->withInput()->with('error', 'Terjadi kesalahan database.');
        }
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['status' => !$product->status]);
        return back()->with('success', 'Status produk berhasil diubah.');
    }
}