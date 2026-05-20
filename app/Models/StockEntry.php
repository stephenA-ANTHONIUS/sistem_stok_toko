<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockEntry extends Model
{
    protected $fillable = [
        'entry_date',
        'notes',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(StockEntryItem::class, 'stock_entry_id');
    }

    protected static function booted()
    {
        static::deleting(function (StockEntry $stockEntry) {
            DB::transaction(function () use ($stockEntry) {
                foreach ($stockEntry->items as $item) {
                    DB::table('products')
                        ->where('id', $item->product_id)
                        ->decrement('jumlah_stok', $item->qty);
                }
            });
        });
    }
}
