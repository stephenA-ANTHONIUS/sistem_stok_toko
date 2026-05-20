<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nama_produk',
        'id produk',
        'id_produk',
        'jumlah stok',
        'jumlah_stok',
        'harga',
        'harga_offline',
        'harga_online',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function shippingLabelItems()
    {
        return $this->hasMany(ShippingLabelItem::class);
    }

    public function offlineTransactionItems()
    {
        return $this->hasMany(OfflineTransactionItem::class);
    }

    // Accessor untuk stock
    public function getStockAttribute()
    {
        return $this->{'jumlah stok'};
    }

    public function setStockAttribute($value)
    {
        $this->{'jumlah stok'} = $value;
    }

    public function getAttribute($key)
    {
        if ($key === 'id produk') {
            return parent::getAttribute('id_produk');
        }

        if ($key === 'jumlah stok') {
            return parent::getAttribute('jumlah_stok');
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if ($key === 'id produk') {
            return parent::setAttribute('id_produk', $value);
        }

        if ($key === 'jumlah stok') {
            return parent::setAttribute('jumlah_stok', $value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getHargaAttribute()
    {
        return $this->attributes['harga_offline'] ?? $this->attributes['harga'] ?? 0;
    }
}
