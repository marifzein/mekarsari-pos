<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    // Karena nama tabel jamak (chart_of_accounts), kita set eksplisit biar aman
    protected $table = 'chart_of_accounts';

    // Daftarkan kolom yang boleh diisi massal oleh seeder
    protected $fillable = [
        'account_code',
        'account_name',
        'account_type',
        'report_type',
        'is_active',
    ];
}