@extends('layouts.admin')
@section('title', 'Edit Petani')
@section('page-title', 'Edit Data Petani')
@section('content')
<div class="max-w-lg bg-white rounded-xl p-6 shadow-sm border border-gray-200">
    <form method="POST" action="{{ route('admin.petani.update', $petani) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="nama" value="{{ old('nama', $petani->nama) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
            <input type="text" name="nik" value="{{ old('nik', $petani->nik) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" maxlength="20">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $petani->no_hp) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Dusun</label>
            <input type="text" name="alamat_dusun" value="{{ old('alamat_dusun', $petani->alamat_dusun) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Perbarui</button>
            <a href="{{ route('admin.petani.index') }}" class="text-sm text-gray-500 hover:underline self-center">Batal</a>
        </div>
    </form>
</div>
@endsection
