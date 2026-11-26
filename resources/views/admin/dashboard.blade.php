@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')

{{-- 4 Kartu Metrik --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
        <div class="text-xs text-gray-500 mb-1">Laba Bersih Bulan Ini</div>
        <div class="text-xl font-bold {{ $labaBersihBulanIni >= 0 ? 'text-green-600' : 'text-red-600' }}">
            Rp {{ number_format(abs($labaBersihBulanIni), 0, ',', '.') }}
        </div>
        <div class="text-xs text-gray-400 mt-1">{{ $labaBersihBulanIni < 0 ? '(Rugi)' : '' }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
        <div class="text-xs text-gray-500 mb-1">Estimasi Nilai Stok</div>
        <div class="text-xl font-bold text-blue-600">Rp {{ number_format($nilaiAsetStok, 0, ',', '.') }}</div>
        <div class="text-xs text-gray-400 mt-1">Total aset gudang</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
        <div class="text-xs text-gray-500 mb-1">Hutang ke Petani</div>
        <div class="text-xl font-bold text-orange-600">Rp {{ number_format($hutangKePetani, 0, ',', '.') }}</div>
        <div class="text-xs text-gray-400 mt-1">Belum dibayar</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
        <div class="text-xs text-gray-500 mb-1">Piutang Pembeli</div>
        <div class="text-xl font-bold text-purple-600">Rp {{ number_format($piutangPembeli, 0, ',', '.') }}</div>
        <div class="text-xs text-gray-400 mt-1">Belum diterima</div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Grafik --}}
    <div class="lg:col-span-2 bg-white rounded-xl p-5 shadow-sm border border-gray-200">
        <h3 class="font-semibold text-gray-700 mb-4">Pembelian vs Penjualan (6 Bulan)</h3>
        <canvas id="grafikChart" height="100"></canvas>
    </div>

    {{-- Stok Menipis --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
        <h3 class="font-semibold text-gray-700 mb-3">⚠️ Stok Menipis</h3>
        @forelse($stokMenipis as $s)
            <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0 text-sm">
                <span class="text-gray-700">{{ $s->nama_komoditas }}</span>
                <span class="font-medium text-yellow-600">{{ number_format($s->stok_kg, 0, ',', '.') }} Kg</span>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">Semua stok aman ✅</p>
        @endforelse
    </div>
</div>

{{-- 5 Transaksi Terakhir --}}
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-700">📋 Transaksi Barang Masuk Terakhir</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">No. Nota</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Sumber</th>
                    <th class="px-5 py-3 text-left">Komoditas</th>
                    <th class="px-5 py-3 text-right">Berat (Kg)</th>
                    <th class="px-5 py-3 text-right">Total (Rp)</th>
                    <th class="px-5 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transaksiTerakhir as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-mono text-xs text-green-700">{{ $t->no_nota }}</td>
                        <td class="px-5 py-3">{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">{{ $t->label_sumber }}</td>
                        <td class="px-5 py-3">{{ $t->komoditas->nama_komoditas }}</td>
                        <td class="px-5 py-3 text-right">{{ number_format($t->berat_kg, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right">{{ number_format($t->total_nominal, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $t->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $t->label_status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-6 text-center text-gray-400">Belum ada transaksi.</td></tr>
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
                backgroundColor: 'rgba(234, 179, 8, 0.7)',
                borderRadius: 4,
            },
            {
                label: 'Penjualan (Rp)',
                data: @json($grafikData['penjualan']),
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: {
                ticks: {
                    callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                }
            }
        }
    }
});
</script>
@endsection
