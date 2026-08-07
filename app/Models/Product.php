<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StockMovement;

class Product extends Model
{
    protected $table = 'products';

    protected $guarded = [];

    public function stockMovements()
    {
        return $this->hasMany(
            StockMovement::class
        );
    }
    
    // relasi potongan grosir
    public function productPrices()
    {
        return $this->hasMany(
            ProductPrice::class
        )
        ->orderBy('min_qty');
    }

    public function stockOpnameDetails()
    {
        return $this->hasMany(
            StockOpnameDetail::class
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function generateKodeBarang($nama)
    {
        $prefix = strtolower(
            preg_replace(
                '/[^a-zA-Z0-9]/',
                '',
                $nama
            )
        );

        $prefix =
            substr($prefix,0,6);

        $i = 1;

        do{

            $kode =
                $prefix .
                str_pad(
                    $i,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

            $ada =
                self::where(
                    'kode_barang',
                    $kode
                )->exists();

            $i++;

        }
        while($ada);

        return $kode;
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}