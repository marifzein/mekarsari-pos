<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
        'nomor_sa', 'tgl_sa', 'user_id', 'status', 'catatan', 'tgl_jam_selesai'
    ];

    protected $casts = [
        'tgl_sa' => 'date',
        'tgl_jam_selesai' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(StockAdjustmentDetail::class);
    }
}