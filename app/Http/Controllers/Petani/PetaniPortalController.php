<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\Pengaturan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetaniPortalController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $petani = $user->petani;

        if (!$petani) {
            abort(403, 'Data petani tidak ditemukan.');
        }

        $lahans = $petani->lahans()->orderBy('nama_blok')->get();

        $riwayatSetor = BarangMasuk::with('komoditas')
            ->where('petani_id', $petani->id)
            ->latest('tanggal')
            ->get();

        $totalSetor         = $riwayatSetor->sum('berat_kg');
        $totalTagihanBelumLunas = $riwayatSetor->where('status_bayar', 'belum_lunas')->sum('total_nominal');

        return view('petani.dashboard', compact(
            'petani', 'lahans', 'riwayatSetor', 'totalSetor', 'totalTagihanBelumLunas'
        ));
    }

    public function downloadNota(BarangMasuk $barangMasuk)
    {
        $user   = Auth::user();
        $petani = $user->petani;

        // Pastikan nota milik petani yang login
        if ($barangMasuk->petani_id !== $petani->id) {
            abort(403);
        }

        $barangMasuk->load(['komoditas', 'petani', 'lahan']);
        $pengaturan = Pengaturan::getAktif();

        $pdf = Pdf::loadView('admin.barang-masuk.nota-pdf', compact('barangMasuk', 'pengaturan'))
            ->setPaper([0, 0, 419.53, 595.28]);

        return $pdf->download('Nota-' . $barangMasuk->no_nota . '.pdf');
    }
}
