<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'shifts';

    // Daftarkan semua kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'user_id',
        'starting_cash',
        'total_cash_sales',
        'operational_expense',
        'expected_cash',
        'ending_cash_actual',
        'variance',
        'variance_reason',
        'status',
        'opened_at',
        'closed_at',
    ];

    // Mengatur casting tipe data agar otomatis rapi saat dihitung di PHP
    protected $casts = [
        'starting_cash' => 'decimal:2',
        'total_cash_sales' => 'decimal:2',
        'operational_expense' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'ending_cash_actual' => 'decimal:2',
        'variance' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Relasi balik ke tabel User (Siapa yang jaga shift ini)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}