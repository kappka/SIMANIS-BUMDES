@extends('layouts.admin')
@section('title', 'Data Petani')
@section('page-title', 'Petani & Lahan')
@section('content')
<div x-data="{ tab: 'petani' }">
    <div class="flex gap-2 mb-6">
        <button @click="tab='petani'" :class="tab==='petani' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium">
            👨‍🌾 Data Petani
        </button>
        <a href="{{ route('admin.lahan.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-white text-gray-600 border border-gray-300 hover:bg-gray-50">
            🌱 Data Lahan
        </a>
        <a href="{{ route('admin.petani.create') }}" class="ml-auto px-4 py-2 rounded-lg text-sm font-medium bg-green-600 text-white hover:bg-green-700">
            + Tambah Petani
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Nama Petani</th>
                        <th class="px-5 py-3 text-left">NIK</th>
                        <th class="px-5 py-3 text-left">No. HP</th>
                        <th class="px-5 py-3 text-left">Dusun</th>
                        <th class="px-5 py-3 text-left">Akun Login</th>
                        <th class="px-5 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($petanis as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $p->nama }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $p->nik ?? '—' }}</td>
                            <td class="px-5 py-3">{{ $p->no_hp }}</td>
                            <td class="px-5 py-3">{{ $p->alamat_dusun }}</td>
                            <td class="px-5 py-3">
                                @if($p->user)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $p->user->username }}</span>
                                @else
                                    <span class="text-xs text-gray-400">Belum ada akun</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 flex gap-2 flex-wrap">
                                <a href="{{ route('admin.petani.edit', $p) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.petani.reset-akun', $p) }}" class="inline"
                                      onsubmit="return confirm('Reset akun login petani ini?')">
                                    @csrf
                                    <button type="submit" class="text-xs text-orange-500 hover:underline">Reset Akun</button>
                                </form>
                                <form method="POST" action="{{ route('admin.petani.destroy', $p) }}" class="inline"
                                      onsubmit="return confirm('Hapus data petani ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-6 text-center text-gray-400">Belum ada data petani.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
