<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // Jika model kategori Anda bernama Category
use Illuminate\Http\Request;

class StockCardController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 1. Filter Pencarian Nama / Barcode
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Filter Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category); // Sesuaikan foreign key Anda
        }

        // 3. Filter Status Stok
        if ($request->filled('stock')) {
            if ($request->stock === 'available') {
                $query->where('stok', '>', 15);
            } elseif ($request->stock === 'low') {
                $query->whereBetween('stok', [1, 15]);
            } elseif ($request->stock === 'empty') {
                $query->where('stok', '<=', 0);
            }
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('stock-cards.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        // Ubah movements() menjadi stockMovements() agar sinkron dengan model
        $movements = $product->stockMovements()->latest()->get(); 

        return view('stock-cards.show', compact('product', 'movements'));
    }
}