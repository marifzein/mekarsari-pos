<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil data pertama, jika belum ada buat objek kosong
        $setting = Setting::first() ?? new Setting();
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'owner' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'footer_nota' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_toko', 'owner', 'telepon', 'alamat', 'footer_nota']);

        $setting = Setting::first();

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($setting && $setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('assets/logo', 'public');
        }

        Setting::updateOrCreate(['id' => 1], $data);

        return redirect()->back()->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}