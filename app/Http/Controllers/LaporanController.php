<?php

namespace App\Http\Controllers;

use App\Models\OfflineTransaction;
use App\Models\OfflineTransactionItem;
use App\Models\Product;
use App\Models\ShippingLabel;
use App\Models\StockEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function penjualan()
    {
        // Pengamanan Khusus: Hanya boleh diakses Admin & Pemilik
        if (!in_array(auth()->user()->role, ['admin', 'pemilik'])) {
            abort(403, 'Akses ditolak.');
        }

        $offlineOmset = OfflineTransaction::sum('total_amount');

        $onlineOmset = ShippingLabel::all()->sum(function (ShippingLabel $label) {
            return collect($label->items)->sum(function ($item) {
                $qty     = (int) ($item['qty'] ?? 0);
                $name    = ShippingLabel::normalizeProductName($item['produk'] ?? '');
                $product = Product::whereRaw('TRIM(`nama_produk`) = ?', [$name])->first();
                return $product ? $qty * ($product->harga_online ?? 0) : 0;
            });
        });

        $onlineQty = ShippingLabel::all()->sum(fn ($l) =>
            collect($l->items)->sum(fn ($i) => (int) ($i['qty'] ?? 0))
        );

        // Chart data: 7 hari terakhir
        $start        = now()->subDays(6)->startOfDay();
        $transactions = OfflineTransaction::whereBetween('transaction_date', [$start, now()])->get();
        $labels       = [];
        $offlineCounts = [];
        $onlineQtys    = [];

        for ($days = 6; $days >= 0; $days--) {
            $date    = now()->subDays($days);
            $labels[] = $date->format('d M');

            $offlineCounts[] = $transactions
                ->filter(fn ($t) => Carbon::parse($t->transaction_date)->isSameDay($date))
                ->count();

            $onlineQtys[] = ShippingLabel::whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                ->get()
                ->sum(fn ($l) => collect($l->items)->sum(fn ($i) => (int) ($i['qty'] ?? 0)));
        }

        return view('laporan.penjualan', compact(
            'offlineOmset', 'onlineOmset', 'onlineQty',
            'labels', 'offlineCounts', 'onlineQtys'
        ));
    }

    public function stok()
    {
        // Pengamanan Khusus: Hanya boleh diakses Admin & Pemilik
        if (!in_array(auth()->user()->role, ['admin', 'pemilik'])) {
            abort(403, 'Akses ditolak.');
        }

        $products = Product::orderBy('nama_produk')->get();

        // Chart stok masuk 7 hari
        $start   = now()->subDays(6)->startOfDay();
        $entries = StockEntry::with('items')->whereBetween('entry_date', [$start, now()])->get();
        $stokLabels  = [];
        $stokMasuk   = [];
        $stokKeluar  = [];

        $offlineItems = OfflineTransactionItem::with('transaction')
            ->whereHas('transaction', fn ($q) => $q->whereBetween('transaction_date', [$start, now()]))
            ->get();

        for ($days = 6; $days >= 0; $days--) {
            $date          = now()->subDays($days);
            $stokLabels[]  = $date->format('d M');

            $stokMasuk[] = $entries
                ->filter(fn ($e) => Carbon::parse($e->entry_date)->isSameDay($date))
                ->sum(fn ($e) => $e->items->sum('qty'));

            $dailyOfflineQty = $offlineItems
                ->filter(fn ($item) => Carbon::parse($item->transaction->transaction_date)->isSameDay($date))
                ->sum('qty');

            $dailyOnlineQty = ShippingLabel::whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                ->get()
                ->sum(fn ($l) => collect($l->items)->sum(fn ($i) => (int) ($i['qty'] ?? 0)));

            $stokKeluar[] = $dailyOfflineQty + $dailyOnlineQty;
        }

        return view('laporan.stok', compact('products', 'stokLabels', 'stokMasuk', 'stokKeluar'));
    }

    public function riwayat()
    {
        // Pengamanan Khusus: Hanya Admin (Pemilik tidak bisa akses)
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin.');
        }

        $offlineCount  = OfflineTransaction::count();
        $onlineCount   = ShippingLabel::count();
        $transactions  = OfflineTransaction::with('items.product')->latest()->take(20)->get();
        $shippingLabels = ShippingLabel::latest()->take(20)->get();

        return view('laporan.riwayat', compact('offlineCount', 'onlineCount', 'transactions', 'shippingLabels'));
    }
}