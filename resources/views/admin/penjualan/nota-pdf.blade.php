<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #111; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 10px; }
        .header h1 { font-size: 14px; font-weight: bold; }
        .header p { font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; font-size: 10px; }
        th { background: #e8f5e9; font-weight: bold; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background: #f0f0f0; }
        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .lunas { background: #d4edda; color: #155724; }
        .belum_lunas { background: #fff3cd; color: #856404; }
        .footer { text-align: right; margin-top: 20px; font-size: 10px; }
        .footer .ttd { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>NOTA PENJUALAN</h1>
        <p>{{ $pengaturan->nama_bumdes }} — {{ $pengaturan->alamat_bumdes }}</p>
    </div>

    <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
        <div>
            <strong>No. Nota:</strong> {{ $penjualan->no_nota }}<br>
            <strong>Tanggal:</strong> {{ $penjualan->tanggal->format('d F Y') }}<br>
            <strong>Pembeli:</strong> {{ $penjualan->nama_pembeli }}<br>
            @if($penjualan->no_hp_pembeli)
                <strong>No. HP:</strong> {{ $penjualan->no_hp_pembeli }}
            @endif
        </div>
        <div>
            <span class="status-badge {{ $penjualan->status_bayar }}">{{ $penjualan->label_status }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Komoditas</th>
                <th class="text-right">Berat (Kg)</th>
                <th class="text-right">Harga/Kg (Rp)</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $penjualan->komoditas->nama_komoditas }}</td>
                <td class="text-right">{{ number_format($penjualan->berat_kg, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($penjualan->harga_jual_satuan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($penjualan->total_nominal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($penjualan->total_nominal, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($penjualan->catatan)
        <p style="font-size:10px; color:#555; margin-bottom:10px;"><em>Catatan: {{ $penjualan->catatan }}</em></p>
    @endif

    <div class="footer">
        <div>Payungsari, {{ $penjualan->tanggal->format('d F Y') }}</div>
        <div class="ttd">
            <strong>{{ $pengaturan->nama_ketua }}</strong><br>
            <small>Ketua {{ $pengaturan->nama_bumdes }}</small>
        </div>
    </div>
</body>
</html>
