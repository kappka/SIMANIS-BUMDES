<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\Komoditas;
use App\Models\Lahan;
use App\Models\Petani;
use App\Models\Pengaturan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['komoditas', 'petani', 'lahan'])->latest('tanggal');

        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber);
        }
        if ($request->filled('status')) {
            $query->where('status_bayar', $request->status);
        }
        if ($request->filled('dari')) {
            $query->where('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal', '<=', $request->sampai);
        }

        $barangMasuks = $query->paginate(20)->withQueryString();
        $komoditas    = Komoditas::orderBy('nama_komoditas')->get();
        $petanis      = Petani::orderBy('nama')->get();
        $lahans       = Lahan::where('petani_id', null)->orderBy('nama_blok')->get(); // lahan BUMDes

        return view('admin.barang-masuk.index', compact('barangMasuks', 'komoditas', 'petanis', 'lahans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'sumber'       => 'required|in:beli_petani,panen_bumdes',
            'komoditas_id' => 'required|exists:komoditas,id',
            'berat_kg'     => 'required|numeric|min:0.01',
            'harga_satuan' => 'nullable|numeric|min:0',
            'status_bayar' => 'required|in:lunas,belum_lunas',
            'petani_id'    => 'required_if:sumber,beli_petani|nullable|exists:petanis,id',
            'lahan_id'     => 'nullable|exists:lahans,id',
            'catatan'      => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $komoditas = Komoditas::lockForUpdate()->findOrFail($request->komoditas_id);

            $hargaSatuan = $request->sumber === 'panen_bumdes' ? 0 : ($request->harga_satuan ?? $komoditas->harga_beli_aktif);
            $statusBayar = $request->sumber === 'panen_bumdes' ? 'lunas' : $request->status_bayar;
            $totalNominal = $hargaSatuan * $request->berat_kg;

            // Update HPP rata-rata (Moving Weighted Average)
            if ($komoditas->stok_kg + $request->berat_kg > 0) {
                $hppBaru = (($komoditas->stok_kg * $komoditas->harga_modal_rata2) + ($request->berat_kg * $hargaSatuan))
                    / ($komoditas->stok_kg + $request->berat_kg);
            } else {
                $hppBaru = 0;
            }

            $komoditas->stok_kg          += $request->berat_kg;
            $komoditas->harga_modal_rata2 = $hppBaru;
            $komoditas->save();

            // Generate no_nota: BM-YYYYMMDD-XXX
            $noNota = $this->generateNoNota('BM', $request->tanggal);

            BarangMasuk::create([
                'no_nota'      => $noNota,
                'tanggal'      => $request->tanggal,
                'sumber'       => $request->sumber,
                'petani_id'    => $request->sumber === 'beli_petani' ? $request->petani_id : null,
                'lahan_id'     => $request->lahan_id,
                'komoditas_id' => $request->komoditas_id,
                'berat_kg'     => $request->berat_kg,
                'harga_satuan' => $hargaSatuan,
                'total_nominal'=> $totalNominal,
                'status_bayar' => $statusBayar,
                'catatan'      => $request->catatan,
            ]);
        });

        return back()->with('success', 'Transaksi barang masuk berhasil dicatat.');
    }

    public function tandaiLunas(BarangMasuk $barangMasuk)
    {
        $barangMasuk->update(['status_bayar' => 'lunas']);
        return back()->with('success', 'Transaksi berhasil ditandai Lunas.');
    }

    public function cetakNota(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load(['komoditas', 'petani', 'lahan']);
        $pengaturan = Pengaturan::getAktif();
        $pdf = Pdf::loadView('admin.barang-masuk.nota-pdf', compact('barangMasuk', 'pengaturan'))
            ->setPaper([0, 0, 419.53, 595.28]); // A5
        return $pdf->download('Nota-' . $barangMasuk->no_nota . '.pdf');
    }

    private function generateNoNota(string $prefix, string $tanggal): string
    {
        $dateStr = \Carbon\Carbon::parse($tanggal)->format('Ymd');
        $count   = BarangMasuk::where('no_nota', 'like', "{$prefix}-{$dateStr}-%")->count() + 1;
        return "{$prefix}-{$dateStr}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
