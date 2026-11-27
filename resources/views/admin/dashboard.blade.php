@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')

{{-- 4 Metric Cards --}}
<div class="grid-4" style="margin-bottom:20px;">
    <div class="metric-card">
        <div class="metric-label">Laba Bersih Bulan Ini</div>
        <div class="metric-value" style="color: {{ $labaBersihBulanIni >= 0 ? '#15803d' : '#b91c1c' }};">
            Rp {{ number_format(abs($labaBersihBulanIni), 0, ',', '.') }}
        </div>
        <div class="metric-sub">{{ $labaBersihBulanIni < 0 ? 'Rugi bersih' : 'Keuntungan bersih' }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Estimasi Nilai Stok</div>
        <div class="metric-value" style="color: #1d4ed8;">Rp {{ number_format($nilaiAsetStok, 0, ',', '.') }}</div>
        <div class="metric-sub">Total aset gudang</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Hutang ke Petani</div>
        <div class="metric-value" style="color: #c2410c;">Rp {{ number_format($hutangKePetani, 0, ',', '.') }}</div>
        <div class="metric-sub">Belum dibayar</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Piutang Pembeli</div>
        <div class="metric-value" style="color: #7e22ce;">Rp {{ number_format($piutangPembeli, 0, ',', '.') }}</div>
        <div class="metric-sub">Belum diterima</div>
    </div>
</div>

{{-- Chart + Stok Menipis --}}
<div style="display:grid; grid-template-columns: 2fr 1fr; gap:16px; margin-bottom:20px;">
    <div class="card">
        <div class="card-header">Pembelian vs Penjualan (6 Bulan)</div>
        <div class="card-body">
            <canvas id="grafikChart" height="90"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="display:flex; align-items:center; justify-content:space-between;">
            <span>Stok Menipis</span>
            @if($stokMenipis->isNotEmpty())
                <span class="badge badge-yellow">{{ $stokMenipis->count() }}</span>
            @endif
        </div>
        <div>
            @forelse($stokMenipis as $s)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 20px; border-bottom: 1px solid rgba(13,42,28,.05);">
                    <span style="font-size:13.5px;">{{ $s->nama_komoditas }}</span>
                    <span style="font-weight:600; font-size:13px; color:#c2410c; font-variant-numeric: tabular-nums;">{{ number_format($s->stok_kg, 0, ',', '.') }} Kg</span>
                </div>
            @empty
                <div style="padding:28px 20px; text-align:center; font-size:13px; color: var(--p-muted);">Semua stok aman</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent transactions --}}
<div class="card">
    <div class="card-header">Transaksi Barang Masuk Terakhir</div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No. Nota</th>
                    <th>Tanggal</th>
                    <th>Sumber</th>
                    <th>Komoditas</th>
                    <th class="r">Berat (Kg)</th>
                    <th class="r">Total (Rp)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiTerakhir as $t)
                    <tr>
                        <td class="nota">{{ $t->no_nota }}</td>
                        <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $t->label_sumber }}</td>
                        <td>{{ $t->komoditas->nama_komoditas }}</td>
                        <td class="r num">{{ number_format($t->berat_kg, 0, ',', '.') }}</td>
                        <td class="r num">{{ number_format($t->total_nominal, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $t->status_bayar === 'lunas' ? 'badge-green' : 'badge-orange' }}">
                                {{ $t->label_status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; padding:24px; color: var(--p-muted); font-size:13px;">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
const ctx = document.getElementById('grafikChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($grafikData['labels']),
        datasets: [
            {
                label: 'Pembelian (Rp)',
                data: @json($grafikData['pembelian']),
                backgroundColor: 'rgba(13,42,28,.75)',
                borderRadius: 5,
            },
            {
                label: 'Penjualan (Rp)',
                data: @json($grafikData['penjualan']),
                backgroundColor: 'rgba(246,224,22,.85)',
                borderRadius: 5,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top', labels: { font: { family: 'Figtree', size: 12 }, boxWidth: 10, usePointStyle: true } }
        },
        scales: {
            y: {
                ticks: {
                    callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v),
                    font: { family: 'Figtree', size: 11 }
                },
                grid: { color: 'rgba(13,42,28,.06)' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endsection
