@extends('layouts.app')
@section('title', 'Stok & Harga Pangan — BUMDes Payungsari')
@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <header class="bg-green-700 text-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold">🌾 {{ $pengaturan->nama_bumdes }}</h1>
                <p class="text-green-200 text-xs mt-0.5">{{ $pengaturan->alamat_bumdes }}</p>
            </div>
            <a href="{{ route('login') }}"
               class="text-sm bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors">
                Login
            </a>
        </div>
    </header>

    {{-- Banner Statistik --}}
    <section class="bg-green-800 text-white py-6">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-2xl font-bold">{{ number_format($totalStok, 0, ',', '.') }}</div>
                <div class="text-green-300 text-xs mt-1">Total Stok Tersedia (Kg)</div>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ number_format($serapanBulanIni, 0, ',', '.') }}</div>
                <div class="text-green-300 text-xs mt-1">Serapan Panen Bulan Ini (Kg)</div>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ $totalPetani }}</div>
                <div class="text-green-300 text-xs mt-1">Petani Mitra Terdaftar</div>
            </div>
        </div>
    </section>

    {{-- Katalog Komoditas --}}
    <section class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-lg font-bold text-gray-800 mb-4">📦 Stok & Harga Harian</h2>
        <p class="text-sm text-gray-500 mb-6">Diperbarui: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>

        @if($komoditas->isEmpty())
            <div class="text-center text-gray-400 py-16">Belum ada data komoditas.</div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($komoditas as $k)
                    @php
                        $status = $k->status_stok;
                        $colors = [
                            'Tersedia' => ['badge' => 'bg-green-100 text-green-700', 'dot' => 'bg-green-500'],
                            'Menipis'  => ['badge' => 'bg-yellow-100 text-yellow-700', 'dot' => 'bg-yellow-500'],
                            'Habis'    => ['badge' => 'bg-red-100 text-red-700', 'dot' => 'bg-red-500'],
                        ];
                        $c = $colors[$status];
                        $noWa = $pengaturan->normalized_no_wa;
                        $pesan = urlencode("Halo BUMDes Payungsari, saya ingin menanyakan stok *{$k->nama_komoditas}* (Tersedia: " . number_format($k->stok_kg,0,',','.') . " Kg). Apakah masih tersedia?");
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="font-bold text-gray-800">{{ $k->nama_komoditas }}</h3>
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full {{ $c['badge'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                                {{ $status }}
                            </span>
                        </div>
                        <div class="text-3xl font-bold text-green-700 mb-1">
                            {{ number_format($k->stok_kg, 0, ',', '.') }} <span class="text-base font-normal text-gray-500">Kg</span>
                        </div>
                        <div class="border-t border-gray-100 mt-3 pt-3 space-y-1.5 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Harga Beli BUMDes</span>
                                <span class="font-medium">Rp {{ number_format($k->harga_beli_aktif, 0, ',', '.') }}/Kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Harga Jual BUMDes</span>
                                <span class="font-medium text-green-600">Rp {{ number_format($k->harga_jual_aktif, 0, ',', '.') }}/Kg</span>
                            </div>
                        </div>
                        @if($noWa)
                            <a href="https://wa.me/{{ $noWa }}?text={{ $pesan }}" target="_blank"
                               class="mt-4 w-full flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Pesan / Tanya Stok via WhatsApp
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <footer class="text-center text-xs text-gray-400 py-8">
        © {{ date('Y') }} {{ $pengaturan->nama_bumdes }} — Sistem Informasi Pangan
    </footer>
</div>
@endsection
