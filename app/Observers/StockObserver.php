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

    /**
     * Handle the ShippingLabelItem "deleted" event.
     */
    public function deleted(ShippingLabelItem $shippingLabelItem): void
    {
        $product = $shippingLabelItem->product;
        $product->stock += $shippingLabelItem->qty;
        $product->save();
    }

    /**
     * Handle the ShippingLabelItem "restored" event.
     */
    public function restored(ShippingLabelItem $shippingLabelItem): void
    {
        //
    }

    /**
     * Handle the ShippingLabelItem "force deleted" event.
     */
    public function forceDeleted(ShippingLabelItem $shippingLabelItem): void
    {
        //
    }
}
