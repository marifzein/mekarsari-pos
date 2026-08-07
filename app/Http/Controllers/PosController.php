<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use App\Helpers\DocumentNumber;

class PosController extends Controller
{
    public function index()
    {
        // 💡 Ambil kolom yang dibutuhkan, lalu ikut sertakan relasi productPrices
        $products = Product::select(
            'id',
            'kode_barang',
            'barcode',
            'nama_barang',
            'harga',
            'harga_diskon',
            'stok'
        )
        ->with('productPrices') // 💡 Data grosir dimasukkan ke sini, bos!
        ->get();

        // $noNota = Transaction::generateNoNota();
        $noNota = DocumentNumber::generate('transactions', 'no_nota', 'INV');

        $customers = Customer::where('status',1)
        ->orderBy('nama')
        ->get([
            'id',
            'kode_pelanggan',
            'nama',
            'telepon',
            'alamat',
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