<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OfflineTransaction extends Model
{
    protected $fillable = ['transaction_date', 'total_amount', 'notes'];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(OfflineTransactionItem::class, 'offline_transaction_id');
    }

    protected static function booted()
    {
        static::deleting(function ($transaction) {
            DB::transaction(function () use ($transaction) {
                foreach ($transaction->items as $item) {
                    $product = Product::query()->find($item->product_id);
                    
                    if ($product) {
                        DB::table('products')
                            ->where('id', $product->id)
                            ->increment('jumlah_stok', $item->qty);
                    }
                }

                // Hapus detail item transaksi secara manual
                $transaction->items()->delete();
            });
        });
    }
}