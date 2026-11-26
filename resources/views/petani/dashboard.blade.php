<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Petani — Simanis BUMDes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 font-sans">
    {{-- Header --}}
    <header class="bg-green-700 text-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold">🌾 Portal Petani</h1>
                <p class="text-green-200 text-xs">Halo, {{ $petani->nama }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition-colors">
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 py-6 space-y-6">
        {{-- Flash --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        {{-- Kartu Ringkasan --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">Total Panen Disetor</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($totalSetor, 0, ',', '.') }}</div>
                <div class="text-xs text-gray-400">Kilogram</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">Tagihan Belum Dibayar</div>
                <div class="text-2xl font-bold text-orange-600">Rp {{ number_format($totalTagihanBelumLunas, 0, ',', '.') }}</div>
                <div class="text-xs text-gray-400">Hutang BUMDes ke Anda</div>
            </div>
        </div>

        {{-- Lahan Terdaftar --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 font-semibold text-gray-700">🌱 Lahan Terdaftar Atas Nama Anda</div>
            @forelse($lahans as $l)
                <div class="px-5 py-3 border-b border-gray-100 last:border-0 flex justify-between items-center">
                    <div>
                        <div class="font-medium text-gray-800">{{ $l->nama_blok }}</div>
                        <div class="text-xs text-gray-500">{{ $l->luas_hektar }} Ha · Komoditas: {{ $l->komoditas_tanam ?? '—' }}</div>
                    </div>
                    @if($l->estimasi_panen)
                        <div class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">
                            Est. panen: {{ $l->estimasi_panen->format('d/m/Y') }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-5 py-6 text-center text-gray-400 text-sm">Belum ada lahan terdaftar.</div>
            @endforelse
        </div>

        {{-- Riwayat Setor Panen --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 font-semibold text-gray-700">📋 Riwayat Setoran Panen</div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs">
                        <tr>
                            <th class="px-5 py-3 text-left">Tanggal</th>
                            <th class="px-5 py-3 text-left">No. Nota</th>
                            <th class="px-5 py-3 text-left">Komoditas</th>
                            <th class="px-5 py-3 text-right">Berat (Kg)</th>
                            <th class="px-5 py-3 text-right">Harga/Kg</th>
                            <th class="px-5 py-3 text-right">Total (Rp)</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($riwayatSetor as $bm)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 whitespace-nowrap">{{ $bm->tanggal->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 font-mono text-xs text-green-700">{{ $bm->no_nota }}</td>
                                <td class="px-5 py-3">{{ $bm->komoditas->nama_komoditas }}</td>
                                <td class="px-5 py-3 text-right">{{ number_format($bm->berat_kg, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right">{{ number_format($bm->harga_satuan, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right font-medium">{{ number_format($bm->total_nominal, 0, ',', '.') }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $bm->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $bm->label_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <a href="{{ route('petani.nota.pdf', $bm) }}" target="_blank"
                                       class="text-xs text-blue-600 hover:underline">📄 Nota PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-5 py-6 text-center text-gray-400">Belum ada riwayat setoran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
