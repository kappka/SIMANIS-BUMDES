<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\BiayaOperasional;
use App\Models\Komoditas;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // 4 Kartu Metrik
        $labaBersihBulanIni = $this->hitungLabaBersihBulan($bulanIni, $tahunIni);
        $nilaiAsetStok = Komoditas::selectRaw('SUM(stok_kg * harga_modal_rata2) as nilai')->value('nilai') ?? 0;
        $hutangKePetani = BarangMasuk::where('status_bayar', 'belum_lunas')->sum('total_nominal');
        $piutangPembeli = Penjualan::where('status_bayar', 'belum_lunas')->sum('total_nominal');

        // Grafik 6 bulan terakhir
        $grafikData = $this->getGrafikData();

        // 5 transaksi terakhir
        $transaksiTerakhir = BarangMasuk::with(['komoditas', 'petani'])
            ->latest()->limit(5)->get();

        // Komoditas stok menipis (< 100 kg)
        $stokMenipis = Komoditas::where('stok_kg', '<', 100)->where('stok_kg', '>', 0)->get();

        return view('admin.dashboard', compact(
            'labaBersihBulanIni', 'nilaiAsetStok', 'hutangKePetani', 'piutangPembeli',
            'grafikData', 'transaksiTerakhir', 'stokMenipis'
        ));
    }

    private function hitungLabaBersihBulan(int $bulan, int $tahun): float
    {
        $labaKotor = Penjualan::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->selectRaw('SUM((harga_jual_satuan - hpp_satuan) * berat_kg) as laba')
            ->value('laba') ?? 0;

        $biaya = BiayaOperasional::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('nominal');

        return $labaKotor - $biaya;
    }

    private function getGrafikData(): array
    {
        $labels = [];
        $pembelian = [];
        $penjualan = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');

            $pembelian[] = BarangMasuk::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)->sum('total_nominal');

            $penjualan[] = Penjualan::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)->sum('total_nominal');
        }

        return compact('labels', 'pembelian', 'penjualan');
    }
}
