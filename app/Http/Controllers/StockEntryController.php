<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockEntryController extends Controller
{
    public function index()
    {
        $stockEntries = StockEntry::with('items.product')->latest()->paginate(15);
        return view('stock-entries.index', compact('stockEntries'));
    }

    public function create()
    {
        $products = Product::where('status', true)->orderBy('nama_produk')->get();
        return view('stock-entries.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date'         => ['required', 'date'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty'        => ['required', 'integer', 'min:1'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $stockEntry = StockEntry::create([
                    'entry_date' => $request->entry_date,
                    'notes'      => $request->notes,
                ]);

                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $qty     = (int) $item['qty'];

                    $stockEntry->items()->create([
                        'product_id' => $product->id,
                        'qty'        => $qty,
                    ]);

                    DB::table('products')->where('id', $product->id)->increment('jumlah_stok', $qty);
                }
            });

            return redirect()->route('stock-entries.index')->with('success', 'Stok masuk berhasil disimpan.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(StockEntry $stockEntry)
    {
        $products = Product::where('status', true)->orderBy('nama_produk')->get();
        $stockEntry->load('items.product');
        return view('stock-entries.edit', compact('stockEntry', 'products'));
    }

    public function update(Request $request, StockEntry $stockEntry)
    {
        $request->validate([
            'entry_date'         => ['required', 'date'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty'        => ['required', 'integer', 'min:1'],
        ]);

        try {
            DB::transaction(function () use ($request, $stockEntry) {
                // Kurangi stok lama
                foreach ($stockEntry->items as $oldItem) {
                    DB::table('products')->where('id', $oldItem->product_id)->decrement('jumlah_stok', $oldItem->qty);
                }

                $stockEntry->items()->delete();
                $stockEntry->update([
                    'entry_date' => $request->entry_date,
                    'notes'      => $request->notes,
                ]);

                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $qty     = (int) $item['qty'];

                    $stockEntry->items()->create([
                        'product_id' => $product->id,
                        'qty'        => $qty,
                    ]);

                    DB::table('products')->where('id', $product->id)->increment('jumlah_stok', $qty);
                }
            });

            return redirect()->route('stock-entries.index')->with('success', 'Stok masuk berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(StockEntry $stockEntry)
    {
        try {
            $stockEntry->delete(); 

            return back()->with('success', 'Data stok masuk berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
