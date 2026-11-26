<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komoditas;
use App\Models\PenyesuaianStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KomoditasController extends Controller
{
    public function index()
    {
        $komoditas = Komoditas::orderBy('nama_komoditas')->get();
        $penyesuaians = PenyesuaianStok::with('komoditas')->latest()->limit(20)->get();
        return view('admin.komoditas.index', compact('komoditas', 'penyesuaians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_komoditas'  => 'required|string|max:100|unique:komoditas',
            'harga_beli_aktif'=> 'required|numeric|min:0',
            'harga_jual_aktif'=> 'required|numeric|min:0',
        ]);

        Komoditas::create([
            'nama_komoditas'   => $request->nama_komoditas,
            'harga_beli_aktif' => $request->harga_beli_aktif,
            'harga_jual_aktif' => $request->harga_jual_aktif,
            'stok_kg'          => 0,
            'harga_modal_rata2'=> 0,
        ]);

        return back()->with('success', 'Komoditas berhasil ditambahkan.');
    }

    public function update(Request $request, Komoditas $komoditas)
    {
        $request->validate([
            'harga_beli_aktif' => 'required|numeric|min:0',
            'harga_jual_aktif' => 'required|numeric|min:0',
        ]);

        $komoditas->update([
            'harga_beli_aktif' => $request->harga_beli_aktif,
            'harga_jual_aktif' => $request->harga_jual_aktif,
        ]);

        return back()->with('success', 'Harga komoditas berhasil diperbarui.');
    }

    public function destroy(Komoditas $komoditas)
    {
        // Proteksi: tidak boleh hapus jika sudah ada transaksi
        if ($komoditas->barangMasuks()->exists() || $komoditas->penjualans()->exists()) {
            return back()->with('error', 'Komoditas tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        $komoditas->delete();
        return back()->with('success', 'Komoditas berhasil dihapus.');
    }
}
