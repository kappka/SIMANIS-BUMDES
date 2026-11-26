@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan BUMDes')
@section('content')
<div class="max-w-lg bg-white rounded-xl p-6 shadow-sm border border-gray-200">
    <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama BUMDes <span class="text-red-500">*</span></label>
            <input type="text" name="nama_bumdes" value="{{ old('nama_bumdes', $pengaturan->nama_bumdes) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat BUMDes <span class="text-red-500">*</span></label>
            <textarea name="alamat_bumdes" rows="2"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>{{ old('alamat_bumdes', $pengaturan->alamat_bumdes) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ketua / Penandatangan Nota <span class="text-red-500">*</span></label>
            <input type="text" name="nama_ketua" value="{{ old('nama_ketua', $pengaturan->nama_ketua) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Layanan Stok <span class="text-red-500">*</span></label>
            <input type="text" name="no_wa_stok" value="{{ old('no_wa_stok', $pengaturan->no_wa_stok) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            <p class="text-xs text-gray-400 mt-1">Format: 081234567890 atau 6281234567890. Digunakan pada tombol WhatsApp halaman publik.</p>
        </div>
        <div class="p-3 bg-green-50 rounded-lg border border-green-200 text-xs text-green-700">
            Link WA aktif: <a href="https://wa.me/{{ $pengaturan->normalized_no_wa }}" target="_blank" class="underline">https://wa.me/{{ $pengaturan->normalized_no_wa }}</a>
        </div>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Simpan Pengaturan</button>
    </form>
</div>
@endsection
