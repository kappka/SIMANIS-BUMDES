<!DOCTYPE html>
<html lang="id" data-radius="soft">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Petani — Simanis BUMDes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --p-bg: #f7f7f7;
            --p-surface: #ebebeb;
            --p-ink: #0d2a1c;
            --p-muted: #5f6b64;
            --p-accent: #f6e016;
            --p-accent-ink: #0d2a1c;
            --p-inv-bg: #0d2a1c;
            --p-inv-surface: #1c3629;
            --p-inv-ink: #f7f7f7;
            --p-inv-muted: #97a39c;
            --p-inv-accent: #f6e016;
            --font: "Figtree", system-ui, sans-serif;
            --radius: 8px;
            --radius-lg: 14px;
            --transition: 220ms cubic-bezier(.4,0,.2,1);
        }

        body {
            font-family: var(--font);
            background: var(--p-bg);
            color: var(--p-ink);
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
            min-height: 100svh;
        }

        .container {
            max-width: 920px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ── Header ── */
        .portal-header {
            background: var(--p-inv-bg);
            color: var(--p-inv-ink);
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .portal-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            gap: 16px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: rgba(246,224,22,.14);
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }
        .brand-title {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }
        .brand-sub {
            font-size: 12.5px;
            color: var(--p-inv-muted);
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: var(--font);
            font-size: 13px;
            font-weight: 500;
            color: var(--p-inv-ink);
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.14);
            padding: 7px 14px;
            border-radius: var(--radius);
            cursor: pointer;
            transition: background var(--transition);
        }
        .btn-logout:hover { background: rgba(255,255,255,.18); }

        /* ── Main content ── */
        .portal-main {
            padding: 28px 0 48px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .flash-success {
            padding: 12px 16px;
            border-radius: var(--radius);
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 13.5px;
        }

        /* ── Metrics ── */
        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .metric-card {
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(13,42,28,.08);
            box-shadow: 0 1px 3px rgba(13,42,28,.04);
            padding: 22px;
        }
        .metric-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--p-muted);
            margin-bottom: 8px;
        }
        .metric-value {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }
        .metric-sub {
            font-size: 12px;
            color: var(--p-muted);
            margin-top: 6px;
        }

        /* ── Card & List ── */
        .card {
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(13,42,28,.08);
            box-shadow: 0 1px 3px rgba(13,42,28,.04);
            overflow: hidden;
        }
        .card-header {
            padding: 16px 20px 14px;
            border-bottom: 1px solid rgba(13,42,28,.06);
            font-size: 14px;
            font-weight: 600;
        }
        .lahan-item {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(13,42,28,.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .lahan-item:last-child { border-bottom: none; }
        .lahan-name { font-weight: 600; font-size: 14px; }
        .lahan-meta { font-size: 12.5px; color: var(--p-muted); margin-top: 2px; }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 999px;
            letter-spacing: .02em;
        }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-orange { background: #fff7ed; color: #c2410c; }

        /* ── Table ── */
        .data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .data-table thead th {
            padding: 10px 16px;
            text-align: left;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--p-muted);
            background: var(--p-bg);
            border-bottom: 1px solid var(--p-surface);
            white-space: nowrap;
        }
        .data-table thead th.r { text-align: right; }
        .data-table tbody td {
            padding: 11px 16px;
            border-bottom: 1px solid rgba(13,42,28,.045);
            vertical-align: middle;
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr { transition: background var(--transition); }
        .data-table tbody tr:hover td { background: rgba(13,42,28,.018); }
        .data-table td.r { text-align: right; }
        .data-table td.nota {
            font-family: "SF Mono", "Fira Code", ui-monospace, monospace;
            font-size: 11px;
            color: #15803d;
        }
        .data-table td.num { font-variant-numeric: tabular-nums; }

        .btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 500;
            color: #1d4ed8;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 4px 10px;
            border-radius: 6px;
            text-decoration: none;
            transition: background var(--transition);
        }
        .btn-pdf:hover { background: #dbeafe; }

        @media (max-width: 640px) {
            .container { padding: 0 16px; }
            .metrics-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <header class="portal-header">
        <div class="container portal-header-inner">
            <div class="brand">
                <div class="brand-logo">
                    <svg width="18" height="18" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                        <path d="M14 4C14 4 7 9 7 16a7 7 0 0014 0C21 9 14 4 14 4Z" fill="#f6e016"/>
                    </svg>
                </div>
                <div>
                    <h1 class="brand-title">Portal Petani</h1>
                    <p class="brand-sub">Halo, {{ $petani->nama }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <main class="container portal-main">
        {{-- Flash --}}
        @if(session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif

        {{-- Kartu Ringkasan --}}
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-label">Total Panen Disetor</div>
                <div class="metric-value" style="color:#15803d;">{{ number_format($totalSetor, 0, ',', '.') }}</div>
                <div class="metric-sub">Kilogram</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Tagihan Belum Dibayar</div>
                <div class="metric-value" style="color:#c2410c;">Rp {{ number_format($totalTagihanBelumLunas, 0, ',', '.') }}</div>
                <div class="metric-sub">Hutang BUMDes ke Anda</div>
            </div>
        </div>

        {{-- Lahan Terdaftar --}}
        <div class="card">
            <div class="card-header">Lahan Terdaftar Atas Nama Anda</div>
            @forelse($lahans as $l)
                <div class="lahan-item">
                    <div>
                        <div class="lahan-name">{{ $l->nama_blok }}</div>
                        <div class="lahan-meta">{{ $l->luas_hektar }} Ha · Komoditas: {{ $l->komoditas_tanam ?? '—' }}</div>
                    </div>
                    @if($l->estimasi_panen)
                        <span class="badge badge-green">
                            Est. panen: {{ $l->estimasi_panen->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
            @empty
                <div style="padding:24px; text-align:center; color:var(--p-muted); font-size:13px;">
                    Belum ada lahan terdaftar.
                </div>
            @endforelse
        </div>

        {{-- Riwayat Setor Panen --}}
        <div class="card">
            <div class="card-header">Riwayat Setoran Panen</div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Nota</th>
                            <th>Komoditas</th>
                            <th class="r">Berat (Kg)</th>
                            <th class="r">Harga/Kg</th>
                            <th class="r">Total (Rp)</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatSetor as $bm)
                            <tr>
                                <td style="white-space:nowrap;">{{ $bm->tanggal->format('d/m/Y') }}</td>
                                <td class="nota">{{ $bm->no_nota }}</td>
                                <td style="font-weight:500;">{{ $bm->komoditas->nama_komoditas }}</td>
                                <td class="r num">{{ number_format($bm->berat_kg, 0, ',', '.') }}</td>
                                <td class="r num">{{ number_format($bm->harga_satuan, 0, ',', '.') }}</td>
                                <td class="r num" style="font-weight:600;">{{ number_format($bm->total_nominal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $bm->status_bayar === 'lunas' ? 'badge-green' : 'badge-orange' }}">
                                        {{ $bm->label_status }}
                                    </span>
                                </td>
                                <td class="r">
                                    <a href="{{ route('petani.nota.pdf', $bm) }}" target="_blank" class="btn-pdf">
                                        Nota PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center; padding:24px; color:var(--p-muted); font-size:13px;">
                                    Belum ada riwayat setoran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
