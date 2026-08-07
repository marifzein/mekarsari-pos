<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    use HasFactory;

    // Karena nama tabel kita bukan 'penerimaan_barangs', wajib didefinisikan manual
    protected $table = 'penerimaan_barang';

    // Izinkan semua field diisi mass-assignment
    protected $guarded = [];

    /**
     * Relasi ke detail item penerimaan (One to Many)
     */
    public function items()
    {
        return $this->hasMany(PenerimaanBarangItem::class, 'penerimaan_barang_id');
    }

    /**
     * Relasi ke data Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Relasi ke User / Kasir yang menginput data
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}