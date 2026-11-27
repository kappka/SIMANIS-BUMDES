@extends('layouts.admin')
@section('title', 'Laporan LPJ')
@section('page-title', 'Rekap Laporan Keuangan (LPJ)')
@section('content')

<style>
    .lpj-metrics {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }
    @media (max-width: 1200px) {
        .lpj-metrics { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .lpj-metrics { grid-template-columns: 1fr; }
    }
</style>

{{-- Filter --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="filter-bar">
            <div>
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari }}" class="form-control">
            </div>
            <div>
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai }}" class="form-control">
            </div>
            <div>
                <label class="form-label">Komoditas</label>
                <select name="komoditas_id" class="form-control">
                    <option value="">Semua Komoditas</option>
                    @foreach($komoditas as $k)
                        <option value="{{ $k->id }}" {{ $komId == $k->id ? 'selected' : '' }}>{{ $k->nama_komoditas }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
            <div style="margin-left:auto; display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('admin.laporan.pdf', ['dari' => $dari, 'sampai' => $sampai, 'komoditas_id' => $komId]) }}"
                   target="_blank" class="btn btn-danger">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/></svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.laporan.excel', ['dari' => $dari, 'sampai' => $sampai, 'komoditas_id' => $komId]) }}"
                   class="btn btn-success">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    Export Excel
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Ringkasan Metrik --}}
<div class="lpj-metrics">
    <div class="metric-card">
        <div class="metric-label">Total Pembelian</div>
        <div class="metric-value" style="font-size:19px; color:#c2410c; font-variant-numeric:tabular-nums;">
            Rp {{ number_format($data['totalPembelian'], 0, ',', '.') }}
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Total Penjualan</div>
        <div class="metric-value" style="font-size:19px; color:#1d4ed8; font-variant-numeric:tabular-nums;">
            Rp {{ number_format($data['totalPenjualan'], 0, ',', '.') }}
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Laba Kotor</div>
        <div class="metric-value" style="font-size:19px; color:{{ $data['labaKotor'] >= 0 ? '#15803d' : '#b91c1c' }}; font-variant-numeric:tabular-nums;">
            Rp {{ number_format($data['labaKotor'], 0, ',', '.') }}
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Biaya Operasional</div>
        <div class="metric-value" style="font-size:19px; color:#c2410c; font-variant-numeric:tabular-nums;">
            Rp {{ number_format($data['totalBiaya'], 0, ',', '.') }}
        </div>
    </div>
    <div class="metric-card" style="{{ $data['labaBersih'] >= 0 ? 'background:var(--p-inv-bg); color:var(--p-inv-ink); border-color:var(--p-inv-bg);' : 'background:#fef2f2; border-color:#fecaca;' }}">
        <div class="metric-label" style="{{ $data['labaBersih'] >= 0 ? 'color:var(--p-inv-muted);' : 'color:#b91c1c;' }}">Laba Bersih</div>
        <div class="metric-value" style="font-size:20px; color:{{ $data['labaBersih'] >= 0 ? 'var(--p-inv-accent)' : '#b91c1c' }}; font-variant-numeric:tabular-nums;">
            Rp {{ number_format($data['labaBersih'], 0, ',', '.') }}
        </div>
    </div>
</div>

{{-- Detail Penjualan --}}
<div class="card" style="overflow:hidden; margin-bottom:20px;">
    <div class="card-header">Detail Penjualan</div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Nota</th>
                    <th>Pembeli</th>
                    <th>Komoditas</th>
                    <th class="r">Berat (Kg)</th>
                    <th class="r">Harga/Kg</th>
                    <th class="r">HPP/Kg</th>
                    <th class="r">Laba Kotor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['detailPenjualan'] as $pj)
                    <tr>
                        <td style="white-space:nowrap;">{{ $pj->tanggal->format('d/m/Y') }}</td>
                        <td class="nota">{{ $pj->no_nota }}</td>
                        <td style="font-weight:500;">{{ $pj->nama_pembeli }}</td>
                        <td>{{ $pj->komoditas->nama_komoditas }}</td>
                        <td class="r num">{{ number_format($pj->berat_kg, 0, ',', '.') }}</td>
                        <td class="r num">{{ number_format($pj->harga_jual_satuan, 0, ',', '.') }}</td>
                        <td class="r num" style="color:var(--p-muted);">{{ number_format($pj->hpp_satuan, 0, ',', '.') }}</td>
                        <td class="r num" style="font-weight:600; color:{{ $pj->laba_kotor >= 0 ? '#15803d' : '#b91c1c' }};">
                            {{ number_format($pj->laba_kotor, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge {{ $pj->status_bayar === 'lunas' ? 'badge-green' : 'badge-orange' }}">
                                {{ $pj->label_status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center; padding:24px; color:var(--p-muted); font-size:13px;">Tidak ada data penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Detail Biaya --}}
<div class="card" style="overflow:hidden;">
    <div class="card-header">Detail Biaya Operasional</div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th class="r">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['detailBiaya'] as $b)
                    <tr>
                        <td style="white-space:nowrap;">{{ $b->tanggal->format('d/m/Y') }}</td>
                        <td><span class="badge badge-orange">{{ $b->label_kategori }}</span></td>
                        <td style="color:var(--p-muted);">{{ $b->keterangan }}</td>
                        <td class="r num" style="font-weight:600;">{{ number_format($b->nominal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center; padding:24px; color:var(--p-muted); font-size:13px;">Tidak ada data biaya.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
