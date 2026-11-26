@extends('layouts.admin')
@section('title', 'Tambah Petani')
@section('page-title', 'Tambah Data Petani')
@section('content')
<div class="max-w-lg bg-white rounded-xl p-6 shadow-sm border border-gray-200">
    <form method="POST" action="{{ route('admin.petani.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NIK (opsional)</label>
            <input type="text" name="nik" value="{{ old('nik') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" maxlength="20">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No. HP <span class="text-red-500">*</span></label>
            <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            <p class="text-xs text-gray-400 mt-1">Akan dijadikan username & password login awal petani.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Dusun <span class="text-red-500">*</span></label>
            <input type="text" name="alamat_dusun" value="{{ old('alamat_dusun') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Simpan</button>
            <a href="{{ route('admin.petani.index') }}" class="text-sm text-gray-500 hover:underline self-center">Batal</a>
        </div>
    </form>
</div>
@endsection
