@extends('layouts.app')
@section('title', 'Stok & Harga Pangan — {{ $pengaturan->nama_bumdes }}')
@section('content')

{{-- ═══ NAVIGATION ═══ --}}
<nav style="
    position: sticky; top: 0; z-index: 100;
    background: rgba(247,247,247,.92); backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(13,42,28,.08);
">
    <div class="container" style="display:flex; align-items:center; justify-content:space-between; height:60px;">
        <div style="display:flex; align-items:center; gap:10px;">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                <rect width="28" height="28" rx="7" fill="#0d2a1c"/>
                <path d="M14 6C14 6 8 10 8 15.5a6 6 0 0012 0C20 10 14 6 14 6Z" fill="#f6e016"/>
                <path d="M11 17c0 1.657 1.343 3 3 3s3-1.343 3-3" stroke="#0d2a1c" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <div>
                <div style="font-weight:700; font-size:15px; line-height:1.2; color: var(--p-ink);">{{ $pengaturan->nama_bumdes }}</div>
                <div style="font-size:11px; color: var(--p-muted); line-height:1;">Sistem Informasi Pangan</div>
            </div>
        </div>
        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Masuk</a>
    </div>
</nav>

{{-- ═══ HERO ═══ --}}
<section style="padding: 80px 0 60px; overflow:hidden;">
    <div class="container">
        <div style="max-width:620px;">
            <div class="badge badge-green" style="margin-bottom:20px;">
                <span class="dot"></span>
                Harga diperbarui {{ now()->translatedFormat('d F Y') }}
            </div>
            <h1 style="font-size: clamp(32px,5vw,52px); font-weight:700; letter-spacing:-0.03em; line-height:1.1; margin-bottom:16px;">
                Stok pangan desa,<br>harga transparan.
            </h1>
            <p style="font-size:17px; color: var(--p-muted); line-height:1.65; max-width:480px;">
                {{ $pengaturan->nama_bumdes }} membeli langsung dari petani mitra dan menjual ke masyarakat dengan harga yang tercatat terbuka setiap hari.
            </p>
        </div>

        {{-- Stat strip --}}
        <div style="display:flex; flex-wrap:wrap; gap:0; margin-top:48px; border: 1px solid rgba(13,42,28,.1); border-radius: var(--radius-lg); overflow:hidden; background:#fff;">
            <div style="flex:1; min-width:160px; padding:24px 28px; border-right: 1px solid rgba(13,42,28,.08);">
                <div class="stat-num" style="color: var(--p-ink);">{{ number_format($totalStok, 0, ',', '.') }}</div>
                <div class="stat-label">Kg Stok Tersedia</div>
            </div>
            <div style="flex:1; min-width:160px; padding:24px 28px; border-right: 1px solid rgba(13,42,28,.08);">
                <div class="stat-num" style="color: var(--p-ink);">{{ number_format($serapanBulanIni, 0, ',', '.') }}</div>
                <div class="stat-label">Kg Serapan Bulan Ini</div>
            </div>
            <div style="flex:1; min-width:160px; padding:24px 28px;">
                <div class="stat-num" style="color: var(--p-ink);">{{ $totalPetani }}</div>
                <div class="stat-label">Petani Mitra</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ COMMODITY GRID ═══ --}}
<section style="padding: 20px 0 80px;">
    <div class="container">
        <div style="display:flex; align-items:baseline; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:8px;">
            <h2 style="font-size:19px; font-weight:700; letter-spacing:-0.02em;">Harga & Stok Hari Ini</h2>
            <span style="font-size:12px; color: var(--p-muted);">Diperbarui: {{ now()->translatedFormat('H:i') }} WIB</span>
        </div>

        @if($komoditas->isEmpty())
            <div style="text-align:center; padding:60px 0; color: var(--p-muted); font-size:14px;">
                Belum ada data komoditas.
            </div>
        @else
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:16px;">
                @foreach($komoditas as $k)
                    @php
                        $status = $k->status_stok;
                        $badgeClass = $status === 'Tersedia' ? 'badge-green' : ($status === 'Menipis' ? 'badge-yellow' : 'badge-red');
                        $noWa = $pengaturan->normalized_no_wa;
                        $pesan = urlencode("Halo BUMDes Payungsari, saya ingin menanyakan stok *{$k->nama_komoditas}* (Tersedia: " . number_format($k->stok_kg,0,',','.') . " Kg). Apakah masih tersedia?");
                    @endphp
                    <div class="card" style="padding:22px;">
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
                            <div style="font-weight:700; font-size:15px; color: var(--p-ink);">{{ $k->nama_komoditas }}</div>
                            <span class="badge {{ $badgeClass }}">
                                <span class="dot"></span>
                                {{ $status }}
                            </span>
                        </div>

                        <div style="font-size:30px; font-weight:800; letter-spacing:-0.03em; color: var(--p-ink); line-height:1;">
                            {{ number_format($k->stok_kg, 0, ',', '.') }}
                            <span style="font-size:14px; font-weight:400; color: var(--p-muted);">Kg</span>
                        </div>

                        <div style="margin-top:16px; padding-top:16px; border-top: 1px solid rgba(13,42,28,.07); display:flex; flex-direction:column; gap:7px;">
                            <div style="display:flex; justify-content:space-between; font-size:13px;">
                                <span style="color: var(--p-muted);">Harga Beli BUMDes</span>
                                <span style="font-weight:600; font-variant-numeric: tabular-nums;">Rp {{ number_format($k->harga_beli_aktif, 0, ',', '.') }}/Kg</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:13px;">
                                <span style="color: var(--p-muted);">Harga Jual BUMDes</span>
                                <span style="font-weight:700; color: #15803d; font-variant-numeric: tabular-nums;">Rp {{ number_format($k->harga_jual_aktif, 0, ',', '.') }}/Kg</span>
                            </div>
                        </div>

                        @if($noWa)
                            <a href="https://wa.me/{{ $noWa }}?text={{ $pesan }}" target="_blank" class="btn-wa" style="margin-top:16px; width:100%; justify-content:center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                Pesan via WhatsApp
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ═══ FOOTER ═══ --}}
<footer style="border-top: 1px solid rgba(13,42,28,.08); padding: 28px 0;">
    <div class="container" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <span style="font-size:12.5px; color: var(--p-muted);">© {{ date('Y') }} {{ $pengaturan->nama_bumdes }}</span>
        <span style="font-size:12px; color: var(--p-muted);">{{ $pengaturan->alamat_bumdes }}</span>
    </div>
</footer>

<style>
    @media (max-width: 600px) {
        .card:hover { transform: none; }
    }
</style>
@endsection
