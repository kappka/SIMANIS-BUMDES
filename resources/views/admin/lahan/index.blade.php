@extends('layouts.admin')
@section('title', 'Data Lahan')
@section('page-title', 'Data Lahan')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex gap-2">
        <a href="{{ route('admin.petani.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-white text-gray-600 border border-gray-300 hover:bg-gray-50">
            👨‍🌾 Data Petani
        </a>
        <span class="px-4 py-2 rounded-lg text-sm font-medium bg-green-600 text-white">🌱 Data Lahan</span>
    </div>
    <a href="{{ route('admin.lahan.create') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-green-600 text-white hover:bg-green-700">
        + Tambah Lahan
    </a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">Nama Blok</th>
                    <th class="px-5 py-3 text-left">Kepemilikan</th>
                    <th class="px-5 py-3 text-right">Luas (Ha)</th>
                    <th class="px-5 py-3 text-left">Komoditas Tanam</th>
                    <th class="px-5 py-3 text-left">Est. Panen</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($lahans as $l)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $l->nama_blok }}</td>
                        <td class="px-5 py-3">
                            @if($l->petani_id)
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ $l->petani->nama }}</span>
                            @else
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Lahan BUMDes</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">{{ $l->luas_hektar }}</td>
                        <td class="px-5 py-3">{{ $l->komoditas_tanam ?? '—' }}</td>
                        <td class="px-5 py-3">{{ $l->estimasi_panen ? $l->estimasi_panen->format('d/m/Y') : '—' }}</td>
                        <td class="px-5 py-3 flex gap-2">
                            <a href="{{ route('admin.lahan.edit', $l) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.lahan.destroy', $l) }}" class="inline"
                                  onsubmit="return confirm('Hapus lahan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-gray-400">Belum ada data lahan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
