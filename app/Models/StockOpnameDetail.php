<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected $fillable = [

        'stock_opname_id',

        'product_id',

        'stock_system',

        'stock_physical',

        'difference',

        'notes'
    ];

    public function opname()
    {
        return $this->belongsTo(
            StockOpname::class,
            'stock_opname_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class
        );
    }
}