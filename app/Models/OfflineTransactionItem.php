<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineTransactionItem extends Model
{
    protected $fillable = ['offline_transaction_id', 'product_id', 'qty'];

    public function transaction()
    {
        return $this->belongsTo(OfflineTransaction::class, 'offline_transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
