<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komoditas;
use App\Models\PenyesuaianStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyesuaianStokController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'komoditas_id' => 'required|exists:komoditas,id',
            'jenis'        => 'required|in:susut,koreksi_tambah',
            'berat_kg'     => 'required|numeric|min:0.01',
            'alasan'       => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $komoditas = Komoditas::lockForUpdate()->findOrFail($request->komoditas_id);

            if ($request->jenis === 'susut') {
                if ($request->berat_kg > $komoditas->stok_kg) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'berat_kg' => 'Berat penyusutan melebihi stok tersedia (' . $komoditas->stok_kg . ' Kg).',
                    ]);
                }
                $komoditas->stok_kg -= $request->berat_kg;
            } else {
                $komoditas->stok_kg += $request->berat_kg;
            }

            $komoditas->save();

            PenyesuaianStok::create($request->only(['tanggal', 'komoditas_id', 'jenis', 'berat_kg', 'alasan']));
        });

        return back()->with('success', 'Penyesuaian stok berhasil dicatat.');
    }
}
