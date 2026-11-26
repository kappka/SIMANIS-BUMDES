<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lahan;
use App\Models\Petani;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PetaniController extends Controller
{
    public function index()
    {
        $petanis = Petani::with('user')->orderBy('nama')->get();
        return view('admin.petani.index', compact('petanis'));
    }

    public function create()
    {
        return view('admin.petani.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:100',
            'nik'          => 'nullable|string|max:20|unique:petanis',
            'no_hp'        => 'required|string|max:20',
            'alamat_dusun' => 'required|string|max:200',
        ]);

        DB::transaction(function () use ($request) {
            $username = $request->no_hp;
            $password = $request->no_hp; // default password = no_hp

            $user = User::create([
                'name'     => $request->nama,
                'username' => $username,
                'password' => Hash::make($password),
                'role'     => 'petani',
            ]);

            Petani::create([
                'user_id'      => $user->id,
                'nik'          => $request->nik,
                'nama'         => $request->nama,
                'no_hp'        => $request->no_hp,
                'alamat_dusun' => $request->alamat_dusun,
            ]);
        });

        return redirect()->route('admin.petani.index')->with('success', 'Data petani berhasil ditambahkan. Akun login: username = no HP, password = no HP.');
    }

    public function edit(Petani $petani)
    {
        return view('admin.petani.edit', compact('petani'));
    }

    public function update(Request $request, Petani $petani)
    {
        $request->validate([
            'nama'         => 'required|string|max:100',
            'nik'          => 'nullable|string|max:20|unique:petanis,nik,' . $petani->id,
            'no_hp'        => 'required|string|max:20',
            'alamat_dusun' => 'required|string|max:200',
        ]);

        $petani->update($request->only(['nama', 'nik', 'no_hp', 'alamat_dusun']));

        if ($petani->user) {
            $petani->user->update(['name' => $request->nama]);
        }

        return redirect()->route('admin.petani.index')->with('success', 'Data petani berhasil diperbarui.');
    }

    public function destroy(Petani $petani)
    {
        DB::transaction(function () use ($petani) {
            if ($petani->user) {
                $petani->user->delete();
            }
            $petani->delete();
        });

        return back()->with('success', 'Data petani berhasil dihapus.');
    }

    public function resetAkun(Request $request, Petani $petani)
    {
        if ($petani->user) {
            $petani->user->update([
                'username' => $petani->no_hp,
                'password' => Hash::make($petani->no_hp),
            ]);
        }

        return back()->with('success', 'Akun petani berhasil direset. Username & Password = No HP.');
    }
}
