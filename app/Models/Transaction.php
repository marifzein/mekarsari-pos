<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionDetail;

class Transaction extends Model
{
    protected $guarded = [];

        public function details()
    {
        return $this->hasMany(
            TransactionDetail::class
        );
    }

    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customerRelation()
    {
        // Menghubungkan kolom 'pelanggan' di transactions ke 'kode_pelanggan' di customers
        return $this->belongsTo(Customer::class, 'pelanggan', 'kode_pelanggan');
    }

    // Relasi ke Pembatalan Penjualan
    public function pembatalan()
    {
        return $this->hasOne(PembatalanPenjualan::class, 'transaction_id');
    }

    
}