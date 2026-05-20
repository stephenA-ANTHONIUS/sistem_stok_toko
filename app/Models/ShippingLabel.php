<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Product;

class ShippingLabel extends Model
{
    protected $fillable = ['image_path', 'raw_text', 'items'];

    protected $casts = [
        'items' => 'array',
    ];

    protected static function booted()
    {
        static::created(function (ShippingLabel $shippingLabel) {
            $shippingLabel->adjustStockFromItems($shippingLabel->items, -1);
        });

        static::updated(function (ShippingLabel $shippingLabel) {
            $originalItems = $shippingLabel->getOriginal('items');
            if (is_string($originalItems)) {
                $originalItems = json_decode($originalItems, true) ?: [];
            }

            $shippingLabel->adjustStockFromItems($originalItems, 1);
            $shippingLabel->adjustStockFromItems($shippingLabel->items, -1);
        });

        static::deleted(function (ShippingLabel $shippingLabel) {
            $shippingLabel->adjustStockFromItems($shippingLabel->items, 1);
        });
    }

    public static function normalizeProductName(string $name): string
    {
        $name = preg_replace('/\s+/', ' ', trim($name));
        $name = preg_replace('/\bKUALIT\s*AS\b/i', 'KUALITAS', $name);
        $name = preg_replace('/\bKUALITAS\s*NO\.?\s*(\d+)\b/i', 'KUALITAS NO.$1', $name);
        $name = preg_replace('/\s*\|\s*/', ' | ', $name);
        return trim($name);
    }

    protected function adjustStockFromItems(array $items, int $multiplier): void
    {
        foreach ($items as $item) {
            $produk = $item['produk'] ?? null;
            $qty = isset($item['qty']) ? (int) $item['qty'] : 0;

            if (! $produk || $qty < 1) {
                continue;
            }

            $normalized = self::normalizeProductName($produk);
            $product = Product::query()
                ->whereRaw('TRIM(`nama_produk`) = ?', [$normalized], 'and')
                ->first();

            if (! $product) {
                continue;
            }

            $difference = $qty * $multiplier;
            $delta = abs($difference);
            if ($difference > 0) {
                Product::query()->whereKey($product->getKey())->increment('jumlah_stok', $delta);
            } else {
                Product::query()->whereKey($product->getKey())->decrement('jumlah_stok', $delta);
            }
        }
    }
}
