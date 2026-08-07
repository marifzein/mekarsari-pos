<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $fillable = [

        'product_id',

        'min_qty',

        'potongan'

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}