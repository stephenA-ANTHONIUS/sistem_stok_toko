<?php

namespace App\Observers;

use App\Models\ShippingLabelItem;

class StockObserver
{
    public function created(ShippingLabelItem $shippingLabelItem): void
    {
        $product = $shippingLabelItem->product;
        if (!$product) {
            throw new \Exception('Produk tidak ditemukan.');
        }
        if ($product->stock < $shippingLabelItem->qty) {
            throw new \Exception("Stok produk {$product->{'nama produk'}} tidak cukup.");
        }
        $product->stock -= $shippingLabelItem->qty;
        $product->save();
    }

    public function deleted(ShippingLabelItem $shippingLabelItem): void
    {
        $product = $shippingLabelItem->product;
        $product->stock += $shippingLabelItem->qty;
        $product->save();
    }


    public function restored(ShippingLabelItem $shippingLabelItem): void
    {
        //
    }

    public function forceDeleted(ShippingLabelItem $shippingLabelItem): void
    {
        //
    }
}
