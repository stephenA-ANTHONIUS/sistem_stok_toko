<?php

namespace App\Observers;

use App\Models\OfflineTransactionItem;

class OfflineTransactionItemObserver
{

    public function updated(OfflineTransactionItem $offlineTransactionItem): void
    {
        $originalQty = $offlineTransactionItem->getOriginal('qty');
        $newQty = $offlineTransactionItem->qty;
        $difference = $newQty - $originalQty;

        if ($difference === 0) {
            return;
        }

        $product = $offlineTransactionItem->product;
        if (!$product) {
            throw new \Exception('Produk tidak ditemukan.');
        }

        if ($difference > 0) {
            // Qty bertambah, kurangi stok
            if ($product->{'jumlah stok'} < $difference) {
                throw new \Exception("Stok produk {$product->{'nama_produk'}} tidak cukup.");
            }
            $product->{'jumlah stok'} -= $difference;
        } else {
            // Qty berkurang, kembalikan stok
            $product->{'jumlah stok'} -= $difference; // difference negative, so add
        }

        $product->save();
    }

    public function deleted(OfflineTransactionItem $offlineTransactionItem): void
    {
        $product = $offlineTransactionItem->product;
        if ($product) {
            $product->{'jumlah stok'} += $offlineTransactionItem->qty;
            $product->save();
        }
    }

    public function restored(OfflineTransactionItem $offlineTransactionItem): void
    {
        $product = $offlineTransactionItem->product;
        if ($product) {
            $product->{'jumlah stok'} -= $offlineTransactionItem->qty;
            $product->save();
        }
    }

    public function forceDeleted(OfflineTransactionItem $offlineTransactionItem): void
    {
        $product = $offlineTransactionItem->product;
        if ($product) {
            $product->{'jumlah stok'} += $offlineTransactionItem->qty;
            $product->save();
        }
    }
}
