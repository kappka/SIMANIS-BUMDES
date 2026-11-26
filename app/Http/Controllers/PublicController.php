<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use App\Models\Pengaturan;
use App\Models\Petani;

class PublicController extends Controller
{
    public function index()
    {
        $pengaturan  = Pengaturan::getAktif();
        $komoditas   = Komoditas::orderBy('nama_komoditas')->get();
        $totalStok   = $komoditas->sum('stok_kg');
        $totalPetani = Petani::count();

        $serapanBulanIni = \App\Models\BarangMasuk::where('sumber', 'beli_petani')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('berat_kg');

        return view('public.index', compact(
            'pengaturan', 'komoditas', 'totalStok', 'totalPetani', 'serapanBulanIni'
        ));
    }
}
