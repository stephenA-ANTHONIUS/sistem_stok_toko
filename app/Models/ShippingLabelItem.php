<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingLabelItem extends Model
{
    protected $fillable = ['shipping_label_id', 'product_id', 'qty'];

    public function shippingLabel()
    {
        return $this->belongsTo(ShippingLabel::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
