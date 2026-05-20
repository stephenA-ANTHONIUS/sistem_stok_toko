<?php

namespace App\Http\Controllers;

use App\Models\OfflineTransaction;
use App\Models\Product;
use App\Models\ShippingLabel;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk      = Product::count();
        $totalStok        = Product::sum('jumlah_stok');
        $stokMenipis      = Product::where('jumlah_stok', '<', 5)->count();
        $transaksiHariIni = OfflineTransaction::whereDate('created_at', today())->count()
                          + ShippingLabel::whereDate('created_at', today())->count();

        $offlineOmset = OfflineTransaction::sum('total_amount');

        $onlineOmset = ShippingLabel::all()->sum(function (ShippingLabel $label) {
            return collect($label->items)->sum(function ($item) {
                $qty  = (int) ($item['qty'] ?? 0);
                $name = ShippingLabel::normalizeProductName($item['produk'] ?? '');
                $product = Product::whereRaw('TRIM(`nama_produk`) = ?', [$name])->first();
                return $product ? $qty * ($product->harga_online ?? 0) : 0;
            });
        });

        $onlineQty = ShippingLabel::all()->sum(fn ($l) =>
            collect($l->items)->sum(fn ($i) => (int) ($i['qty'] ?? 0))
        );

        return view('dashboard', compact(
            'totalProduk', 'totalStok', 'stokMenipis', 'transaksiHariIni',
            'offlineOmset', 'onlineOmset', 'onlineQty'
        ));
    }
}
