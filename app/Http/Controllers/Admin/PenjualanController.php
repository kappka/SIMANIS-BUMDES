<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komoditas;
use App\Models\Pengaturan;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('komoditas')->latest('tanggal');

        if ($request->filled('status')) {
            $query->where('status_bayar', $request->status);
        }
        if ($request->filled('dari')) {
            $query->where('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal', '<=', $request->sampai);
        }

        $penjualans = $query->paginate(20)->withQueryString();
        $komoditas  = Komoditas::where('stok_kg', '>', 0)->orderBy('nama_komoditas')->get();

        return view('admin.penjualan.index', compact('penjualans', 'komoditas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'          => 'required|date',
            'nama_pembeli'     => 'required|string|max:100',
            'no_hp_pembeli'    => 'nullable|string|max:20',
            'komoditas_id'     => 'required|exists:komoditas,id',
            'berat_kg'         => 'required|numeric|min:0.01',
            'harga_jual_satuan'=> 'required|numeric|min:0',
            'status_bayar'     => 'required|in:lunas,belum_lunas',
            'catatan'          => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $komoditas = Komoditas::lockForUpdate()->findOrFail($request->komoditas_id);

            // Validasi anti-stok minus
            if ($request->berat_kg > $komoditas->stok_kg) {
                throw ValidationException::withMessages([
                    'berat_kg' => "Stok tidak mencukupi. Stok tersedia: {$komoditas->stok_kg} Kg.",
                ]);
            }

            // Snapshot HPP saat transaksi
            $hppSatuan    = $komoditas->harga_modal_rata2;
            $totalNominal = $request->harga_jual_satuan * $request->berat_kg;

            $komoditas->stok_kg -= $request->berat_kg;
            $komoditas->save();

            $noNota = $this->generateNoNota('PJ', $request->tanggal);

            Penjualan::create([
                'no_nota'          => $noNota,
                'tanggal'          => $request->tanggal,
                'nama_pembeli'     => $request->nama_pembeli,
                'no_hp_pembeli'    => $request->no_hp_pembeli,
                'komoditas_id'     => $request->komoditas_id,
                'berat_kg'         => $request->berat_kg,
                'harga_jual_satuan'=> $request->harga_jual_satuan,
                'hpp_satuan'       => $hppSatuan,
                'total_nominal'    => $totalNominal,
                'status_bayar'     => $request->status_bayar,
                'catatan'          => $request->catatan,
            ]);
        });

        return back()->with('success', 'Transaksi penjualan berhasil dicatat.');
    }

    public function tandaiLunas(Penjualan $penjualan)
    {
        $penjualan->update(['status_bayar' => 'lunas']);
        return back()->with('success', 'Transaksi berhasil ditandai Lunas.');
    }

    public function cetakNota(Penjualan $penjualan)
    {
        $penjualan->load('komoditas');
        $pengaturan = Pengaturan::getAktif();
        $pdf = Pdf::loadView('admin.penjualan.nota-pdf', compact('penjualan', 'pengaturan'))
            ->setPaper([0, 0, 419.53, 595.28]); // A5
        return $pdf->download('Nota-' . $penjualan->no_nota . '.pdf');
    }

    private function generateNoNota(string $prefix, string $tanggal): string
    {
        $dateStr = \Carbon\Carbon::parse($tanggal)->format('Ymd');
        $count   = Penjualan::where('no_nota', 'like', "{$prefix}-{$dateStr}-%")->count() + 1;
        return "{$prefix}-{$dateStr}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
