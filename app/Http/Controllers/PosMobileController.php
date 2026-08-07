<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Helpers\DocumentNumber;

class PosMobileController extends Controller
{
    public function index()
    {
        // Mengambil data yang sama persis seperti kasir PC, tanpa menyentuh file lamanya
        $products = Product::select(
            'id',
            'kode_barang',
            'barcode',
            'nama_barang',
            'harga',
            'harga_diskon',
            'stok'
        )
        ->with('productPrices') // Menyesuaikan nama relasi grosir asli kamu
        ->get();

        $noNota = DocumentNumber::generate('transactions', 'no_nota', 'INV');

        $customers = Customer::where('status', 1)
            ->orderBy('nama')
            ->get([
                'id',
                'kode_pelanggan',
                'nama',
                'telepon',
                'alamat',
                'is_member'
            ]);

        // Diarahkan ke file view mobile baru yang terpisah
        return view('pos.mobile', compact('products', 'noNota', 'customers'));
    }
}