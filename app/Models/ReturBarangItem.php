<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturBarangItem extends Model
{
    protected $table = 'retur_barang_items';
    protected $fillable = ['retur_barang_id', 'product_id', 'qty_retur', 'harga_beli', 'subtotal'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}