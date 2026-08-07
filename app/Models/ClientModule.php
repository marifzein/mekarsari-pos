<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientModule extends Model
{
    use HasFactory;

    // Definisikan nama tabel secara eksplisit (opsional, karena Laravel sudah otomatis membaca jamak)
    protected $table = 'client_modules';

    // Kolom yang diizinkan untuk diisi massal
    protected $fillable = [
        'controller_name',
        'is_active',
    ];

    // Cast properti agar is_active otomatis menjadi boolean saat dipanggil di Eloquent
    protected $casts = [
        'is_active' => 'boolean',
    ];
}