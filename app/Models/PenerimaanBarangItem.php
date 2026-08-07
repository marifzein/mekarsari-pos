<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarangItem extends Model
{
    use HasFactory;

    // Definisikan nama tabel asli di database
    protected $table = 'penerimaan_barang_items';

    protected $guarded = [];

    /**
     * Relasi balik ke induk Penerimaan Barang
     */
    public function penerimaanBarang()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'penerimaan_barang_id');
    }

    /**
     * Relasi ke Master Produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}