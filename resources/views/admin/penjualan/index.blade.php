@extends('layouts.admin')
@section('title', 'Penjualan')
@section('page-title', 'Transaksi Penjualan')
@section('content')

{{-- Form Input --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6" x-data="{
    berat: 0, harga: 0, total: 0,
    updateHarga(h) { this.harga = h; this.updateTotal(); },
    updateTotal() { this.total = this.berat * this.harga; }
}">
    <h3 class="font-semibold text-gray-700 mb-4">📤 Input Transaksi Penjualan</h3>
    <form method="POST" action="{{ route('admin.penjualan.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @csrf
        <div>
            <label class="block text-xs text-gray-500 mb-1">Tanggal <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Nama Pembeli/Tengkulak <span class="text-red-500">*</span></label>
            <input type="text" name="nama_pembeli" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">No. HP Pembeli</label>
            <input type="text" name="no_hp_pembeli" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Komoditas <span class="text-red-500">*</span></label>
            <select name="komoditas_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required
                    @change="updateHarga(parseFloat($event.target.selectedOptions[0].dataset.harga || 0))">
                <option value="">-- Pilih Komoditas --</option>
                @foreach($komoditas as $k)
                    <option value="{{ $k->id }}" data-harga="{{ $k->harga_jual_aktif }}">
                        {{ $k->nama_komoditas }} (Stok: {{ number_format($k->stok_kg,0,',','.') }} Kg)
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Berat Terjual (Kg) <span class="text-red-500">*</span></label>
            <input type="number" name="berat_kg" x-model.number="berat" @input="updateTotal()"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0.01" step="0.01" required>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Harga Jual/Kg (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="harga_jual_satuan" x-model.number="harga" @input="updateTotal()"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0" step="100" required>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Total Nominal</label>
            <div class="border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-medium">
                Rp <span x-text="new Intl.NumberFormat('id-ID').format(total)">0</span>
            </div>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status Bayar</label>
            <select name="status_bayar" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="lunas">✅ Lunas</option>
                <option value="belum_lunas">⏳ Belum Lunas</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Catatan</label>
            <input type="text" name="catatan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="md:col-span-2 lg:col-span-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium">
                💾 Simpan Penjualan
            </button>
        </div>
    </form>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
    <form method="GET" action="{{ route('admin.penjualan.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                <option value="">Semua</option>
                <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="belum_lunas" {{ request('status') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-600 text-white px-4 py-1.5 rounded text-sm">Filter</button>
        <a href="{{ route('admin.penjualan.index') }}" class="text-sm text-gray-500 hover:underline self-end pb-1.5">Reset</a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">No. Nota</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Pembeli</th>
                    <th class="px-5 py-3 text-left">Komoditas</th>
                    <th class="px-5 py-3 text-right">Berat (Kg)</th>
                    <th class="px-5 py-3 text-right">Harga/Kg</th>
                    <th class="px-5 py-3 text-right">HPP/Kg</th>
                    <th class="px-5 py-3 text-right">Total (Rp)</th>
                    <th class="px-5 py-3 text-right">Laba Kotor</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($penjualans as $pj)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-mono text-xs text-green-700">{{ $pj->no_nota }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ $pj->tanggal->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">{{ $pj->nama_pembeli }}</td>
                        <td class="px-5 py-3">{{ $pj->komoditas->nama_komoditas }}</td>
                        <td class="px-5 py-3 text-right font-mono">{{ number_format($pj->berat_kg,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right">{{ number_format($pj->harga_jual_satuan,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right text-gray-500">{{ number_format($pj->hpp_satuan,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right font-medium">{{ number_format($pj->total_nominal,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right {{ $pj->laba_kotor >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ number_format($pj->laba_kotor,0,',','.') }}
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $pj->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $pj->label_status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <div class="flex gap-2">
                                @if($pj->status_bayar === 'belum_lunas')
                                    <form method="POST" action="{{ route('admin.penjualan.lunas', $pj) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs text-green-600 hover:underline font-medium">✓ Lunas</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.penjualan.pdf', $pj) }}" target="_blank"
                                   class="text-xs text-blue-600 hover:underline">📄 Nota PDF</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="px-5 py-6 text-center text-gray-400">Belum ada transaksi penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $penjualans->links() }}</div>
</div>
@endsection
