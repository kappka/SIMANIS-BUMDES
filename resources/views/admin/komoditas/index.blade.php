@extends('layouts.admin')
@section('title', 'Stok & Harga')
@section('page-title', 'Stok & Harga Komoditas')
@section('content')

<div x-data="{ tab: 'stok', editId: null, editHargaBeli: '', editHargaJual: '' }">

    {{-- Tabs --}}
    <div class="flex gap-2 mb-6">
        <button @click="tab='stok'" :class="tab==='stok' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            🌾 Master Komoditas
        </button>
        <button @click="tab='opname'" :class="tab==='opname' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            📊 Stock Opname
        </button>
        <button @click="tab='tambah'" :class="tab==='tambah' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            ➕ Tambah Komoditas
        </button>
    </div>

    {{-- Tab: Master Komoditas --}}
    <div x-show="tab==='stok'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Komoditas</th>
                        <th class="px-5 py-3 text-right">Stok (Kg)</th>
                        <th class="px-5 py-3 text-right">HPP Rata2</th>
                        <th class="px-5 py-3 text-right">Harga Beli Aktif</th>
                        <th class="px-5 py-3 text-right">Harga Jual Aktif</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($komoditas as $k)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $k->nama_komoditas }}</td>
                            <td class="px-5 py-3 text-right font-mono">{{ number_format($k->stok_kg, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right text-gray-500">Rp {{ number_format($k->harga_modal_rata2, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right">Rp {{ number_format($k->harga_beli_aktif, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right text-green-600 font-medium">Rp {{ number_format($k->harga_jual_aktif, 0, ',', '.') }}</td>
                            <td class="px-5 py-3">
                                @php $s = $k->status_stok; @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $s === 'Tersedia' ? 'bg-green-100 text-green-700' : ($s === 'Menipis' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $s }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <button @click="editId={{ $k->id }}; editHargaBeli={{ $k->harga_beli_aktif }}; editHargaJual={{ $k->harga_jual_aktif }}"
                                    class="text-xs text-blue-600 hover:underline mr-2">Edit Harga</button>
                                <form method="POST" action="{{ route('admin.komoditas.destroy', $k) }}" class="inline"
                                      onsubmit="return confirm('Hapus komoditas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit Harga inline --}}
                        <tr x-show="editId === {{ $k->id }}" x-cloak class="bg-green-50">
                            <td colspan="7" class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.komoditas.update', $k) }}" class="flex flex-wrap gap-3 items-end">
                                    @csrf @method('PUT')
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Harga Beli (Rp/Kg)</label>
                                        <input type="number" name="harga_beli_aktif" x-model="editHargaBeli" class="border border-gray-300 rounded px-3 py-1.5 text-sm w-36" min="0" step="100">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Harga Jual (Rp/Kg)</label>
                                        <input type="number" name="harga_jual_aktif" x-model="editHargaJual" class="border border-gray-300 rounded px-3 py-1.5 text-sm w-36" min="0" step="100">
                                    </div>
                                    <button type="submit" class="bg-green-600 text-white px-4 py-1.5 rounded text-sm hover:bg-green-700">Simpan</button>
                                    <button type="button" @click="editId=null" class="text-sm text-gray-500 hover:underline">Batal</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-6 text-center text-gray-400">Belum ada komoditas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab: Stock Opname --}}
    <div x-show="tab==='opname'" x-cloak class="grid lg:grid-cols-5 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <h3 class="font-semibold text-gray-700 mb-4">Penyesuaian Stok</h3>
            <form method="POST" action="{{ route('admin.penyesuaian-stok.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Komoditas</label>
                    <select name="komoditas_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="">-- Pilih --</option>
                        @foreach($komoditas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_komoditas }} ({{ number_format($k->stok_kg,0,',','.') }} Kg)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Jenis</label>
                    <select name="jenis" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="susut">Penyusutan (Kurangi Stok)</option>
                        <option value="koreksi_tambah">Koreksi Tambah</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Berat (Kg)</label>
                    <input type="number" name="berat_kg" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0.01" step="0.01" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Alasan</label>
                    <input type="text" name="alasan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Misal: Gabah mengering" required>
                </div>
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg text-sm font-medium">Simpan Penyesuaian</button>
            </form>
        </div>
        <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 font-semibold text-gray-700">Riwayat Penyesuaian</div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Komoditas</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-right">Berat (Kg)</th>
                            <th class="px-4 py-3 text-left">Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($penyesuaians as $p)
                            <tr>
                                <td class="px-4 py-2.5">{{ $p->tanggal->format('d/m/Y') }}</td>
                                <td class="px-4 py-2.5">{{ $p->komoditas->nama_komoditas }}</td>
                                <td class="px-4 py-2.5">
                                    <span class="{{ $p->jenis === 'susut' ? 'text-red-600' : 'text-green-600' }} font-medium text-xs">
                                        {{ $p->label_jenis }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right">{{ number_format($p->berat_kg, 0, ',', '.') }}</td>
                                <td class="px-4 py-2.5 text-gray-500">{{ $p->alasan }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-6 text-center text-gray-400">Belum ada riwayat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tab: Tambah Komoditas --}}
    <div x-show="tab==='tambah'" x-cloak class="max-w-md bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <h3 class="font-semibold text-gray-700 mb-4">Tambah Komoditas Baru</h3>
        <form method="POST" action="{{ route('admin.komoditas.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs text-gray-500 mb-1">Nama Komoditas</label>
                <input type="text" name="nama_komoditas" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Harga Beli Awal (Rp/Kg)</label>
                <input type="number" name="harga_beli_aktif" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0" step="100" required>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Harga Jual Awal (Rp/Kg)</label>
                <input type="number" name="harga_jual_aktif" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0" step="100" required>
            </div>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm font-medium">Tambah Komoditas</button>
        </form>
    </div>

</div>
@endsection
