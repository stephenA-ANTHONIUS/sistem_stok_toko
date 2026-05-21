<?php

namespace App\Http\Controllers;

use App\Models\OfflineTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfflineTransactionController extends Controller
{
    public function index()
    {
        $transactions = OfflineTransaction::with('items.product')->latest()->paginate(15);
        return view('offline-transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::where('status', true)->orderBy('nama_produk')->get();
        return view('offline-transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_date'   => ['required', 'date'],
            'notes'              => ['required', 'string', 'max:255'], // Wajib diisi
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty'        => ['required', 'integer', 'min:1'],
        ], [
            'notes.required'     => 'Wajib Isi nama Pembeli',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $items       = $request->items;
                $totalAmount = 0;
                $transaction = OfflineTransaction::create([
                    'transaction_date' => date('Y-m-d'),
                    'notes'            => $request->notes,
                    'total_amount'     => 0,
                ]);

                foreach ($items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    if ($product->jumlah_stok < $item['qty']) {
                        throw new \Exception("Stok {$product->{'nama_produk'}} tidak mencukupi (Sisa: {$product->jumlah_stok}).");
                    }

                    $harga        = $product->harga_offline > 0 ? $product->harga_offline : ($product->harga ?? 0);
                    $totalAmount += $harga * $item['qty'];

                    $transaction->items()->create([
                        'product_id' => $product->id,
                        'qty'        => $item['qty'],
                    ]);

                    DB::table('products')->where('id', $product->id)->decrement('jumlah_stok', $item['qty']);
                }

                $transaction->update(['total_amount' => $totalAmount]);
            });

            return redirect()->route('offline-transactions.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(OfflineTransaction $offlineTransaction)
    {
        $products = Product::where('status', true)->orderBy('nama_produk')->get();
        $offlineTransaction->load('items.product');
        return view('offline-transactions.edit', compact('offlineTransaction', 'products'));
    }

    public function update(Request $request, OfflineTransaction $offlineTransaction)
    {
        $request->validate([
            'transaction_date'   => ['required', 'date'],
            'notes'              => ['required', 'string', 'max:255'], // Wajib diisi saat edit
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty'        => ['required', 'integer', 'min:1'],
        ], [
            'notes.required'     => 'Wajib Isi nama Pembeli',
        ]);

        try {
            DB::transaction(function () use ($request, $offlineTransaction) {
                foreach ($offlineTransaction->items as $oldItem) {
                    DB::table('products')->where('id', $oldItem->product_id)->increment('jumlah_stok', $oldItem->qty);
                }

                $offlineTransaction->items()->delete();
                $offlineTransaction->update([
                    'transaction_date' => $request->transaction_date,
                    'notes'            => $request->notes,
                ]);

                $totalAmount = 0;
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    if ($product->jumlah_stok < $item['qty']) {
                        throw new \Exception("Stok {$product->{'nama_produk'}} tidak mencukupi (Sisa: {$product->jumlah_stok}).");
                    }

                    $harga        = $product->harga_offline > 0 ? $product->harga_offline : ($product->harga ?? 0);
                    $totalAmount += $harga * $item['qty'];

                    $offlineTransaction->items()->create([
                        'product_id' => $product->id,
                        'qty'        => $item['qty'],
                    ]);

                    DB::table('products')->where('id', $product->id)->decrement('jumlah_stok', $item['qty']);
                }

                $offlineTransaction->update(['total_amount' => $totalAmount]);
            });

            return redirect()->route('offline-transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(OfflineTransaction $offlineTransaction)
    {
        $offlineTransaction->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}