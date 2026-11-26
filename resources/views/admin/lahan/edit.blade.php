@extends('layouts.admin')
@section('title', 'Edit Lahan')
@section('page-title', 'Edit Data Lahan')
@section('content')
<div class="max-w-lg bg-white rounded-xl p-6 shadow-sm border border-gray-200">
    <form method="POST" action="{{ route('admin.lahan.update', $lahan) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kepemilikan Lahan</label>
            <select name="petani_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">🏢 Lahan Kelolaan BUMDes</option>
                @foreach($petanis as $p)
                    <option value="{{ $p->id }}" {{ old('petani_id', $lahan->petani_id) == $p->id ? 'selected' : '' }}>
                        👨‍🌾 {{ $p->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Blok</label>
            <input type="text" name="nama_blok" value="{{ old('nama_blok', $lahan->nama_blok) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Luas (Ha)</label>
            <input type="number" name="luas_hektar" value="{{ old('luas_hektar', $lahan->luas_hektar) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" step="0.01" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Komoditas Tanam</label>
            <input type="text" name="komoditas_tanam" value="{{ old('komoditas_tanam', $lahan->komoditas_tanam) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi Panen</label>
            <input type="date" name="estimasi_panen" value="{{ old('estimasi_panen', $lahan->estimasi_panen?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Perbarui</button>
            <a href="{{ route('admin.lahan.index') }}" class="text-sm text-gray-500 hover:underline self-center">Batal</a>
        </div>
    </form>
</div>
@endsection
