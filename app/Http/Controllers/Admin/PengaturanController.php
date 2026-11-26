<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::getAktif();
        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_bumdes'   => 'required|string|max:200',
            'alamat_bumdes' => 'required|string',
            'no_wa_stok'    => 'required|string|max:20',
            'nama_ketua'    => 'required|string|max:100',
        ]);

        $pengaturan = Pengaturan::getAktif();

        // Normalisasi nomor WhatsApp
        $noWa = preg_replace('/\D/', '', $request->no_wa_stok);
        if (str_starts_with($noWa, '0')) {
            $noWa = '62' . substr($noWa, 1);
        }

        $pengaturan->update([
            'nama_bumdes'   => $request->nama_bumdes,
            'alamat_bumdes' => $request->alamat_bumdes,
            'no_wa_stok'    => $noWa,
            'nama_ketua'    => $request->nama_ketua,
        ]);

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
