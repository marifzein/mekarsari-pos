<?php

namespace App\Http\Controllers;

use App\Helpers\DocumentNumber;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $customers = Customer::when($search, function ($q) use ($search) {

            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kode_pelanggan', 'like', "%{$search}%")
              ->orWhere('telepon', 'like', "%{$search}%");

        })
        ->latest()
        ->paginate(10);

        return view('customers.index', compact(
            'customers',
            'search'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nama'       => 'required|max:150',
            'telepon'    => 'nullable|max:30',
            'alamat'     => 'nullable',
            'email'      => 'nullable|email',
            'catatan'    => 'nullable',
            'is_member'  => 'nullable',
            'status'     => 'required',

        ]);

        $validated['kode_pelanggan'] =
            DocumentNumber::generateMaster(
                'customers',
                'kode_pelanggan',
                'CUST'
            );

        $validated['is_member'] =
            $request->boolean('is_member');

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([

            'nama'       => 'required|max:150',
            'telepon'    => 'nullable|max:30',
            'alamat'     => 'nullable',
            'email'      => 'nullable|email',
            'catatan'    => 'nullable',
            'is_member'  => 'nullable',
            'status'     => 'required',

        ]);

        $validated['is_member'] =
            $request->boolean('is_member');

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Store a newly created resource via AJAX/API for POS.
     */
    public function storeApi(Request $request)
    {
        // 1. Validasi input minimal dari modal POS
        $request->validate([
            'nama'    => 'required|max:150',
            'telepon' => 'nullable|max:30',
            'alamat'  => 'nullable',
        ]);

        // 2. Buat data baru dengan default status aktif (1) dan bukan member (0)
        //    serta generate otomatis kode pelanggan menggunakan Helper 
        $customer = Customer::create([
            'kode_pelanggan' => DocumentNumber::generateMaster('customers', 'kode_pelanggan', 'CUST'),
            'nama'           => $request->nama,
            'telepon'        => $request->telepon,
            'alamat'         => $request->alamat,
            'status'         => 1,
            'is_member'      => 0,
        ]);

        // 3. Kembalikan respons JSON agar dibaca lancar oleh Alpine.js di POS
        return response()->json([
            'success'  => true,
            'customer' => [
                'id'             => $customer->id,
                'kode_pelanggan' => $customer->kode_pelanggan,
                'nama'           => $customer->nama,
                'telepon'        => $customer->telepon,
                'alamat'         => $customer->alamat,
                'is_member'      => $customer->is_member,
            ]
        ]);
    }

}