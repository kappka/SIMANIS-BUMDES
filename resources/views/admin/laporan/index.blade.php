@extends('layouts.admin')
@section('title', 'Laporan LPJ')
@section('page-title', 'Rekap Laporan Keuangan (LPJ)')
@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ $dari }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ $sampai }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Komoditas</label>
            <select name="komoditas_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Komoditas</option>
                @foreach($komoditas as $k)
                    <option value="{{ $k->id }}" {{ $komId == $k->id ? 'selected' : '' }}>{{ $k->nama_komoditas }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Tampilkan</button>
        <a href="{{ route('admin.laporan.pdf', ['dari' => $dari, 'sampai' => $sampai, 'komoditas_id' => $komId]) }}"
           target="_blank" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600">
            📄 Export PDF
        </a>
        <a href="{{ route('admin.laporan.excel', ['dari' => $dari, 'sampai' => $sampai, 'komoditas_id' => $komId]) }}"
           class="bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-600">
            📊 Export Excel
        </a>
    </form>
</div>

{{-- Ringkasan Metrik --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 text-center">
        <div class="text-xs text-gray-500 mb-1">Total Pembelian</div>
        <div class="font-bold text-orange-600">Rp {{ number_format($data['totalPembelian'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 text-center">
        <div class="text-xs text-gray-500 mb-1">Total Penjualan</div>
        <div class="font-bold text-blue-600">Rp {{ number_format($data['totalPenjualan'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 text-center">
        <div class="text-xs text-gray-500 mb-1">Laba Kotor</div>
        <div class="font-bold {{ $data['labaKotor'] >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($data['labaKotor'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 text-center">
        <div class="text-xs text-gray-500 mb-1">Biaya Operasional</div>
        <div class="font-bold text-orange-600">Rp {{ number_format($data['totalBiaya'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-blue-200 text-center {{ $data['labaBersih'] >= 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
        <div class="text-xs text-gray-500 mb-1">Laba Bersih</div>
        <div class="font-bold text-lg {{ $data['labaBersih'] >= 0 ? 'text-green-700' : 'text-red-700' }}">Rp {{ number_format($data['labaBersih'], 0, ',', '.') }}</div>
    </div>
</div>

{{-- Detail Penjualan --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 font-semibold text-gray-700">📤 Detail Penjualan</div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">No. Nota</th>
                    <th class="px-4 py-3 text-left">Pembeli</th>
                    <th class="px-4 py-3 text-left">Komoditas</th>
                    <th class="px-4 py-3 text-right">Berat (Kg)</th>
                    <th class="px-4 py-3 text-right">Harga/Kg</th>
                    <th class="px-4 py-3 text-right">HPP/Kg</th>
                    <th class="px-4 py-3 text-right">Laba Kotor</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($data['detailPenjualan'] as $pj)
                    <tr>
                        <td class="px-4 py-2.5">{{ $pj->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-2.5 font-mono text-xs text-green-700">{{ $pj->no_nota }}</td>
                        <td class="px-4 py-2.5">{{ $pj->nama_pembeli }}</td>
                        <td class="px-4 py-2.5">{{ $pj->komoditas->nama_komoditas }}</td>
                        <td class="px-4 py-2.5 text-right">{{ number_format($pj->berat_kg,0,',','.') }}</td>
                        <td class="px-4 py-2.5 text-right">{{ number_format($pj->harga_jual_satuan,0,',','.') }}</td>
                        <td class="px-4 py-2.5 text-right text-gray-500">{{ number_format($pj->hpp_satuan,0,',','.') }}</td>
                        <td class="px-4 py-2.5 text-right {{ $pj->laba_kotor >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ number_format($pj->laba_kotor,0,',','.') }}
                        </td>
                        <td class="px-4 py-2.5">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $pj->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $pj->label_status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-5 py-4 text-center text-gray-400">Tidak ada data penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Detail Biaya --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 font-semibold text-gray-700">💰 Detail Biaya Operasional</div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Keterangan</th>
                    <th class="px-4 py-3 text-right">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($data['detailBiaya'] as $b)
                    <tr>
                        <td class="px-4 py-2.5">{{ $b->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-2.5"><span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">{{ $b->label_kategori }}</span></td>
                        <td class="px-4 py-2.5 text-gray-600">{{ $b->keterangan }}</td>
                        <td class="px-4 py-2.5 text-right font-medium">{{ number_format($b->nominal,0,',','.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-4 text-center text-gray-400">Tidak ada data biaya.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
