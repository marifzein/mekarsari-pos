<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $coas = ChartOfAccount::query()
            ->when($search, function ($query, $search) {
                return $query->where('account_code', 'like', "%{$search}%")
                             ->orWhere('account_name', 'like', "%{$search}%")
                             ->orWhere('account_type', 'like', "%{$search}%");
            })
            ->orderBy('account_code', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('coa.index', compact('coas'));
    }

    public function create()
    {
        return view('coa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_code' => 'required|numeric|unique:chart_of_accounts,account_code',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:HARTA,UTANG,MODAL,PENDAPATAN,HPP,BEBAN',
            'report_type'  => 'required|in:NERACA,LABA_RUGI',
        ]);

        ChartOfAccount::create($request->all() + ['is_active' => 1, 'is_system' => 0]);

        return redirect()->route('coa.index')->with('success', 'Akun COA baru berhasil ditambahkan.');
    }

    public function edit(ChartOfAccount $coa)
    {
        // Proteksi: Akun sistem tidak boleh di-edit
        if ($coa->is_system) {
            return redirect()->route('coa.index')->with('error', 'Akun bawaan sistem tidak dapat diubah.');
        }

        return view('coa.edit', compact('coa'));
    }

    public function update(Request $request, ChartOfAccount $coa)
    {
        if ($coa->is_system) {
            abort(403, 'Akun bawaan sistem diproteksi.');
        }

        $request->validate([
            'account_code' => 'required|numeric|unique:chart_of_accounts,account_code,' . $coa->id,
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:HARTA,UTANG,MODAL,PENDAPATAN,HPP,BEBAN',
            'report_type'  => 'required|in:NERACA,LABA_RUGI',
        ]);

        $coa->update($request->all());

        return redirect()->route('coa.index')->with('success', 'Akun COA berhasil diperbarui.');
    }

    public function toggleStatus(ChartOfAccount $coa)
    {
        if ($coa->is_system) {
            return back()->with('error', 'Akun bawaan sistem tidak dapat dinonaktifkan.');
        }

        $coa->update(['is_active' => !$coa->is_active]);

        return back()->with('success', 'Status akun berhasil diubah.');
    }
}