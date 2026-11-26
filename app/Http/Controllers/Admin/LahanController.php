<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lahan;
use App\Models\Petani;
use Illuminate\Http\Request;

class LahanController extends Controller
{
    public function index()
    {
        $lahans  = Lahan::with('petani')->orderBy('nama_blok')->get();
        $petanis = Petani::orderBy('nama')->get();
        return view('admin.lahan.index', compact('lahans', 'petanis'));
    }

    public function create()
    {
        $petanis = Petani::orderBy('nama')->get();
        return view('admin.lahan.create', compact('petanis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'petani_id'       => 'nullable|exists:petanis,id',
            'nama_blok'       => 'required|string|max:100',
            'luas_hektar'     => 'required|numeric|min:0.01',
            'komoditas_tanam' => 'nullable|string|max:100',
            'estimasi_panen'  => 'nullable|date',
        ]);

        Lahan::create($request->only(['petani_id', 'nama_blok', 'luas_hektar', 'komoditas_tanam', 'estimasi_panen']));

        return redirect()->route('admin.lahan.index')->with('success', 'Data lahan berhasil ditambahkan.');
    }

    public function edit(Lahan $lahan)
    {
        $petanis = Petani::orderBy('nama')->get();
        return view('admin.lahan.edit', compact('lahan', 'petanis'));
    }

    public function update(Request $request, Lahan $lahan)
    {
        $request->validate([
            'petani_id'       => 'nullable|exists:petanis,id',
            'nama_blok'       => 'required|string|max:100',
            'luas_hektar'     => 'required|numeric|min:0.01',
            'komoditas_tanam' => 'nullable|string|max:100',
            'estimasi_panen'  => 'nullable|date',
        ]);

        $lahan->update($request->only(['petani_id', 'nama_blok', 'luas_hektar', 'komoditas_tanam', 'estimasi_panen']));

        return redirect()->route('admin.lahan.index')->with('success', 'Data lahan berhasil diperbarui.');
    }

    public function destroy(Lahan $lahan)
    {
        $lahan->delete();
        return back()->with('success', 'Data lahan berhasil dihapus.');
    }
}
