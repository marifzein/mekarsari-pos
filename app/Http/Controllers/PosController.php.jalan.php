<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::select(
            'id',
            'kode_barang',
            'barcode',
            'nama_barang',
            'harga',
            'harga_diskon',
            'stok'
        )->get();

        $noNota = Transaction::generateNoNota();

        $customers = Customer::where('status',1)
        ->orderBy('nama')
        ->get([
            'kode_pelanggan',
            'nama',
            'telepon',
            'is_member'
        ]);

        return view(
            'pos.index',
            compact(
                'products',
                'noNota',
                'customers'
            )
        );
    }
}