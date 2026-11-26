<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;

class BiayaOperasionalController extends Controller
{
    public function index(Request $request)
    {
        $query = BiayaOperasional::latest('tanggal');

        if ($request->filled('dari')) {
            $query->where('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal', '<=', $request->sampai);
        }

        $biayas = $query->paginate(20)->withQueryString();
        $totalBiaya = BiayaOperasional::sum('nominal');

        return view('admin.biaya-operasional.index', compact('biayas', 'totalBiaya'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'kategori'   => 'required|in:lahan_bumdes,angkut_timbang,kemasan_karung,lainnya',
            'keterangan' => 'required|string|max:255',
            'nominal'    => 'required|numeric|min:0',
        ]);

        BiayaOperasional::create($request->only(['tanggal', 'kategori', 'keterangan', 'nominal']));

        return back()->with('success', 'Biaya operasional berhasil dicatat.');
    }

    public function destroy(BiayaOperasional $biayaOperasional)
    {
        $biayaOperasional->delete();
        return back()->with('success', 'Data biaya berhasil dihapus.');
    }
}
