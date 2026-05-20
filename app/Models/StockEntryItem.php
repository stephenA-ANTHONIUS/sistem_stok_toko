<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntryItem extends Model
{
    protected $fillable = [
        'stock_entry_id',
        'product_id',
        'qty',
    ];

    public function entry()
    {
        return $this->belongsTo(StockEntry::class, 'stock_entry_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
