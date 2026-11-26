@extends('layouts.admin')
@section('title', 'Barang Masuk')
@section('page-title', 'Transaksi Barang Masuk')
@section('content')

{{-- Form Input --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6" x-data="{
    sumber: 'beli_petani',
    hargaDefault: {{ $komoditas->first()?->harga_beli_aktif ?? 0 }},
    berat: 0,
    harga: 0,
    total: 0,
    updateHarga(hargaBeli) { this.harga = hargaBeli; this.updateTotal(); },
    updateTotal() { this.total = this.berat * this.harga; }
}">
    <h3 class="font-semibold text-gray-700 mb-4">📥 Input Transaksi Barang Masuk</h3>
    <form method="POST" action="{{ route('admin.barang-masuk.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @csrf

        <div>
            <label class="block text-xs text-gray-500 mb-1">Tanggal <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-xs text-gray-500 mb-1">Sumber <span class="text-red-500">*</span></label>
            <select name="sumber" x-model="sumber" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                <option value="beli_petani">🧑‍🌾 Beli dari Petani</option>
                <option value="panen_bumdes">🏢 Panen Lahan BUMDes</option>
            </select>
        </div>

        <div>
            <label class="block text-xs text-gray-500 mb-1">Komoditas <span class="text-red-500">*</span></label>
            <select name="komoditas_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required
                    @change="updateHarga(parseFloat($event.target.selectedOptions[0].dataset.harga || 0))">
                <option value="">-- Pilih Komoditas --</option>
                @foreach($komoditas as $k)
                    <option value="{{ $k->id }}" data-harga="{{ $k->harga_beli_aktif }}">
                        {{ $k->nama_komoditas }} (Stok: {{ number_format($k->stok_kg,0,',','.') }} Kg)
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Petani (hanya jika sumber = beli_petani) --}}
        <div x-show="sumber === 'beli_petani'" x-cloak>
            <label class="block text-xs text-gray-500 mb-1">Petani <span class="text-red-500">*</span></label>
            <select name="petani_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">-- Pilih Petani --</option>
                @foreach($petanis as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->no_hp }})</option>
                @endforeach
            </select>
        </div>

        {{-- Lahan BUMDes (hanya jika sumber = panen_bumdes) --}}
        <div x-show="sumber === 'panen_bumdes'" x-cloak>
            <label class="block text-xs text-gray-500 mb-1">Lahan Kelolaan BUMDes</label>
            <select name="lahan_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">-- Pilih Lahan (opsional) --</option>
                @foreach($lahans as $l)
                    <option value="{{ $l->id }}">{{ $l->nama_blok }} ({{ $l->luas_hektar }} Ha)</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs text-gray-500 mb-1">Berat Bersih (Kg) <span class="text-red-500">*</span></label>
            <input type="number" name="berat_kg" x-model.number="berat" @input="updateTotal()"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0.01" step="0.01" required>
        </div>

        <div x-show="sumber === 'beli_petani'" x-cloak>
            <label class="block text-xs text-gray-500 mb-1">Harga Beli/Kg (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="harga_satuan" x-model.number="harga" @input="updateTotal()"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0" step="100">
        </div>

        <div x-show="sumber === 'panen_bumdes'" x-cloak>
            <label class="block text-xs text-gray-500 mb-1">Harga Beli/Kg</label>
            <input type="text" value="Rp 0 (Panen Sendiri)" disabled class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-400">
        </div>

        <div>
            <label class="block text-xs text-gray-500 mb-1">Total Nominal</label>
            <div class="border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-medium">
                Rp <span x-text="new Intl.NumberFormat('id-ID').format(total)">0</span>
            </div>
        </div>

        <div x-show="sumber === 'beli_petani'" x-cloak>
            <label class="block text-xs text-gray-500 mb-1">Status Bayar</label>
            <select name="status_bayar" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="lunas">✅ Lunas</option>
                <option value="belum_lunas">⏳ Belum Lunas</option>
            </select>
        </div>

        <div class="md:col-span-2 lg:col-span-3">
            <label class="block text-xs text-gray-500 mb-1">Catatan (opsional)</label>
            <input type="text" name="catatan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Keterangan tambahan...">
        </div>

        <div class="md:col-span-2 lg:col-span-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium">
                💾 Simpan Transaksi
            </button>
        </div>
    </form>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
    <form method="GET" action="{{ route('admin.barang-masuk.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Sumber</label>
            <select name="sumber" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                <option value="">Semua</option>
                <option value="beli_petani" {{ request('sumber') === 'beli_petani' ? 'selected' : '' }}>Beli Petani</option>
                <option value="panen_bumdes" {{ request('sumber') === 'panen_bumdes' ? 'selected' : '' }}>Panen BUMDes</option>
            </select>
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
        <a href="{{ route('admin.barang-masuk.index') }}" class="text-sm text-gray-500 hover:underline self-end pb-1.5">Reset</a>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">No. Nota</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Sumber</th>
                    <th class="px-5 py-3 text-left">Petani/Lahan</th>
                    <th class="px-5 py-3 text-left">Komoditas</th>
                    <th class="px-5 py-3 text-right">Berat (Kg)</th>
                    <th class="px-5 py-3 text-right">Harga/Kg</th>
                    <th class="px-5 py-3 text-right">Total (Rp)</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($barangMasuks as $bm)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-mono text-xs text-green-700">{{ $bm->no_nota }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ $bm->tanggal->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs {{ $bm->sumber === 'beli_petani' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }} px-2 py-0.5 rounded-full">
                                {{ $bm->label_sumber }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $bm->petani?->nama ?? $bm->lahan?->nama_blok ?? '—' }}
                        </td>
                        <td class="px-5 py-3">{{ $bm->komoditas->nama_komoditas }}</td>
                        <td class="px-5 py-3 text-right font-mono">{{ number_format($bm->berat_kg, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right">{{ $bm->harga_satuan > 0 ? 'Rp '.number_format($bm->harga_satuan,0,',','.') : 'Rp 0' }}</td>
                        <td class="px-5 py-3 text-right font-medium">{{ number_format($bm->total_nominal,0,',','.') }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $bm->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $bm->label_status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <div class="flex gap-2">
                                @if($bm->status_bayar === 'belum_lunas')
                                    <form method="POST" action="{{ route('admin.barang-masuk.lunas', $bm) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs text-green-600 hover:underline font-medium">✓ Tandai Lunas</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.barang-masuk.pdf', $bm) }}" target="_blank"
                                   class="text-xs text-blue-600 hover:underline">📄 Nota PDF</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="px-5 py-6 text-center text-gray-400">Belum ada transaksi barang masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $barangMasuks->links() }}
    </div>
</div>
@endsection
