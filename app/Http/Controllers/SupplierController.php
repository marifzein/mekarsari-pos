<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $suppliers = Supplier::when($search, function ($q) use ($search) {

                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");

            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view(
            'suppliers.index',
            compact('suppliers')
        );
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([

            'kode'=>'required|unique:suppliers',

            'nama'=>'required|max:150',

            'telepon'=>'nullable|max:30',

            'email'=>'nullable|email'

        ], [
            // 🔥 Tulis pesan kustom kamu di sini, Bos!
            'kode.unique'   => 'Kode supplier ini sudah terdaftar di sistem!',
            'kode.required' => 'Kode supplier wajib diisi!',
            'nama.required' => 'Nama supplier tidak boleh kosong!',
        ]);

        Supplier::create($request->all());

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                'Supplier berhasil ditambahkan.'
            );
    }

    public function edit(Supplier $supplier)
    {
        return view(
            'suppliers.edit',
            compact('supplier')
        );
    }

    public function update(
        Request $request,
        Supplier $supplier
    )
    {
        $request->validate([

            'kode'=>'required|unique:suppliers,kode,'.$supplier->id,

            'nama'=>'required|max:150',

            'telepon'=>'nullable|max:30',

            'email'=>'nullable|email'

        ], [
            // 🔥 Tulis pesan kustom kamu di sini, Bos!
            'kode.unique'   => 'Kode supplier ini sudah terdaftar di sistem!',
            'kode.required' => 'Kode supplier wajib diisi!',
            'nama.required' => 'Nama supplier tidak boleh kosong!',
        ]);

        $supplier->update($request->all());

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                'Supplier berhasil diupdate.'
            );
    }
}