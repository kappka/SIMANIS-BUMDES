<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #111; }
        .header { text-align: center; border-bottom: 2px solid #2d6a4f; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { font-size: 16px; font-weight: bold; color: #2d6a4f; }
        .header h2 { font-size: 13px; font-weight: bold; margin-top: 2px; }
        .header p { font-size: 9px; color: #555; }
        .period { text-align: center; margin-bottom: 15px; font-size: 10px; }
        .ringkasan { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .ringkasan-item { text-align: center; border: 1px solid #ddd; padding: 8px 12px; border-radius: 4px; flex: 1; margin: 0 3px; }
        .ringkasan-item .label { font-size: 8px; color: #666; }
        .ringkasan-item .value { font-size: 12px; font-weight: bold; color: #2d6a4f; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #ccc; padding: 5px 7px; font-size: 9px; }
        th { background: #d8f3dc; font-weight: bold; }
        .text-right { text-align: right; }
        .section-title { font-size: 11px; font-weight: bold; margin-bottom: 6px; border-left: 3px solid #2d6a4f; padding-left: 6px; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; }
        .ttd { text-align: center; }
        .ttd .space { height: 50px; }
        .laba-row { background: #d8f3dc; font-weight: bold; }
        .rugi-row { background: #ffe8e8; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PERTANGGUNGJAWABAN (LPJ)</h1>
        <h2>UNIT USAHA PANGAN — {{ strtoupper($pengaturan->nama_bumdes) }}</h2>
        <p>{{ $pengaturan->alamat_bumdes }}</p>
    </div>

    <div class="period">
        <strong>Periode: {{ \Carbon\Carbon::parse($dari)->format('d F Y') }} s.d. {{ \Carbon\Carbon::parse($sampai)->format('d F Y') }}</strong>
    </div>

    {{-- Ringkasan --}}
    <div class="ringkasan">
        <div class="ringkasan-item">
            <div class="label">Total Pembelian</div>
            <div class="value">Rp {{ number_format($data['totalPembelian'], 0, ',', '.') }}</div>
        </div>
        <div class="ringkasan-item">
            <div class="label">Total Penjualan</div>
            <div class="value">Rp {{ number_format($data['totalPenjualan'], 0, ',', '.') }}</div>
        </div>
        <div class="ringkasan-item">
            <div class="label">Laba Kotor</div>
            <div class="value">Rp {{ number_format($data['labaKotor'], 0, ',', '.') }}</div>
        </div>
        <div class="ringkasan-item">
            <div class="label">Biaya Operasional</div>
            <div class="value">Rp {{ number_format($data['totalBiaya'], 0, ',', '.') }}</div>
        </div>
        <div class="ringkasan-item" style="border: 2px solid #2d6a4f;">
            <div class="label">LABA BERSIH</div>
            <div class="value" style="color: {{ $data['labaBersih'] >= 0 ? '#2d6a4f' : '#c0392b' }}">
                Rp {{ number_format($data['labaBersih'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Detail Penjualan --}}
    <div class="section-title">Detail Transaksi Penjualan</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No. Nota</th>
                <th>Pembeli</th>
                <th>Komoditas</th>
                <th class="text-right">Berat (Kg)</th>
                <th class="text-right">Harga/Kg</th>
                <th class="text-right">HPP/Kg</th>
                <th class="text-right">Total (Rp)</th>
                <th class="text-right">Laba Kotor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['detailPenjualan'] as $pj)
            <tr>
                <td>{{ $pj->tanggal->format('d/m/Y') }}</td>
                <td>{{ $pj->no_nota }}</td>
                <td>{{ $pj->nama_pembeli }}</td>
                <td>{{ $pj->komoditas->nama_komoditas }}</td>
                <td class="text-right">{{ number_format($pj->berat_kg,2,',','.') }}</td>
                <td class="text-right">{{ number_format($pj->harga_jual_satuan,0,',','.') }}</td>
                <td class="text-right">{{ number_format($pj->hpp_satuan,0,',','.') }}</td>
                <td class="text-right">{{ number_format($pj->total_nominal,0,',','.') }}</td>
                <td class="text-right">{{ number_format($pj->laba_kotor,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Detail Biaya --}}
    <div class="section-title">Detail Biaya Operasional</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['detailBiaya'] as $b)
            <tr>
                <td>{{ $b->tanggal->format('d/m/Y') }}</td>
                <td>{{ $b->label_kategori }}</td>
                <td>{{ $b->keterangan }}</td>
                <td class="text-right">{{ number_format($b->nominal,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div></div>
        <div class="ttd">
            <div>Payungsari, {{ now()->format('d F Y') }}</div>
            <div>Ketua {{ $pengaturan->nama_bumdes }}</div>
            <div class="space"></div>
            <div><strong>{{ $pengaturan->nama_ketua }}</strong></div>
        </div>
    </div>
</body>
</html>
