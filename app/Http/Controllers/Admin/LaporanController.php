<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\BiayaOperasional;
use App\Models\Komoditas;
use App\Models\Pengaturan;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari    = $request->dari    ?? now()->startOfMonth()->format('Y-m-d');
        $sampai  = $request->sampai  ?? now()->format('Y-m-d');
        $komId   = $request->komoditas_id;

        $data = $this->getRekap($dari, $sampai, $komId);
        $komoditas = Komoditas::orderBy('nama_komoditas')->get();

        return view('admin.laporan.index', compact('data', 'komoditas', 'dari', 'sampai', 'komId'));
    }

    public function exportPdf(Request $request)
    {
        $dari   = $request->dari   ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');
        $komId  = $request->komoditas_id;

        $data       = $this->getRekap($dari, $sampai, $komId);
        $pengaturan = Pengaturan::getAktif();

        $pdf = Pdf::loadView('admin.laporan.lpj-pdf', compact('data', 'pengaturan', 'dari', 'sampai'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('LPJ-BUMDes-Payungsari-' . $dari . '-' . $sampai . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $dari   = $request->dari   ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');
        $komId  = $request->komoditas_id;

        return (new LaporanExport($dari, $sampai, $komId))
            ->download('LPJ-BUMDes-Payungsari-' . $dari . '-' . $sampai . '.xlsx');
    }

    private function getRekap(string $dari, string $sampai, ?string $komId): array
    {
        $bmQuery = BarangMasuk::whereBetween('tanggal', [$dari, $sampai]);
        $pjQuery = Penjualan::whereBetween('tanggal', [$dari, $sampai]);
        $boQuery = BiayaOperasional::whereBetween('tanggal', [$dari, $sampai]);

        if ($komId) {
            $bmQuery->where('komoditas_id', $komId);
            $pjQuery->where('komoditas_id', $komId);
        }

        $totalPembelian   = $bmQuery->sum('total_nominal');
        $totalPenjualan   = $pjQuery->sum('total_nominal');
        $totalBiaya       = $boQuery->sum('nominal');

        $labaKotor = $pjQuery->selectRaw('SUM((harga_jual_satuan - hpp_satuan) * berat_kg) as laba')->value('laba') ?? 0;
        $labaBersih = $labaKotor - $totalBiaya;

        $detailPenjualan = Penjualan::with('komoditas')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->when($komId, fn($q) => $q->where('komoditas_id', $komId))
            ->orderBy('tanggal')->get();

        $detailBiaya = BiayaOperasional::whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal')->get();

        return compact(
            'totalPembelian', 'totalPenjualan', 'totalBiaya',
            'labaKotor', 'labaBersih',
            'detailPenjualan', 'detailBiaya'
        );
    }
}
