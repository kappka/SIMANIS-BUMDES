@extends('layouts.admin')
@section('title', 'Penjualan')
@section('page-title', 'Transaksi Penjualan')
@section('content')

{{-- Form --}}
<div class="card card-body" style="margin-bottom:20px;" x-data="{
    berat: 0, harga: 0, total: 0,
    updateHarga(h) { this.harga = h; this.updateTotal(); },
    updateTotal() { this.total = this.berat * this.harga; }
}">
    <div style="font-weight:600; font-size:14px; margin-bottom:16px;">Input Transaksi Penjualan</div>
    <form method="POST" action="{{ route('admin.penjualan.store') }}" style="display:grid; grid-template-columns: repeat(auto-fill,minmax(220px,1fr)); gap:14px;">
        @csrf
        <div>
            <label class="form-label">Tanggal *</label>
            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control" required>
        </div>
        <div>
            <label class="form-label">Nama Pembeli/Tengkulak *</label>
            <input type="text" name="nama_pembeli" class="form-control" required>
        </div>
        <div>
            <label class="form-label">No. HP Pembeli</label>
            <input type="text" name="no_hp_pembeli" class="form-control">
        </div>
        <div>
            <label class="form-label">Komoditas *</label>
            <select name="komoditas_id" class="form-control" required
                    @change="updateHarga(parseFloat($event.target.selectedOptions[0].dataset.harga || 0))">
                <option value="">— Pilih Komoditas —</option>
                @foreach($komoditas as $k)
                    <option value="{{ $k->id }}" data-harga="{{ $k->harga_jual_aktif }}">
                        {{ $k->nama_komoditas }} (Stok: {{ number_format($k->stok_kg,0,',','.') }} Kg)
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Berat Terjual (Kg) *</label>
            <input type="number" name="berat_kg" x-model.number="berat" @input="updateTotal()"
                   class="form-control" min="0.01" step="0.01" required>
        </div>
        <div>
            <label class="form-label">Harga Jual/Kg (Rp) *</label>
            <input type="number" name="harga_jual_satuan" x-model.number="harga" @input="updateTotal()"
                   class="form-control" min="0" step="100" required>
        </div>
        <div>
            <label class="form-label">Total Nominal</label>
            <div class="form-control" style="background: var(--p-bg); font-weight:600;">
                Rp <span x-text="new Intl.NumberFormat('id-ID').format(total)">0</span>
            </div>
        </div>
        <div>
            <label class="form-label">Status Bayar</label>
            <select name="status_bayar" class="form-control">
                <option value="lunas">Lunas</option>
                <option value="belum_lunas">Belum Lunas</option>
            </select>
        </div>
        <div>
            <label class="form-label">Catatan</label>
            <input type="text" name="catatan" class="form-control">
        </div>
        <div style="grid-column: 1 / -1;">
            <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
        </div>
    </form>
</div>

{{-- Filter --}}
<div class="card card-body" style="margin-bottom:16px;">
    <form method="GET" action="{{ route('admin.penjualan.index') }}" class="filter-bar">
        <div>
            <label class="form-label">Dari</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="form-control form-control-sm">
        </div>
        <div>
            <label class="form-label">Sampai</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control form-control-sm">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-control form-control-sm">
                <option value="">Semua</option>
                <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="belum_lunas" {{ request('status') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
        </div>
        <button type="submit" class="btn btn-ghost btn-sm">Terapkan</button>
        <a href="{{ route('admin.penjualan.index') }}" class="btn btn-ghost btn-sm">Reset</a>
    </form>
</div>

{{-- Table --}}
<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No. Nota</th>
                    <th>Tanggal</th>
                    <th>Pembeli</th>
                    <th>Komoditas</th>
                    <th class="r">Berat (Kg)</th>
                    <th class="r">Harga/Kg</th>
                    <th class="r">HPP/Kg</th>
                    <th class="r">Total (Rp)</th>
                    <th class="r">Laba Kotor</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualans as $pj)
                    <tr>
                        <td class="nota">{{ $pj->no_nota }}</td>
                        <td style="white-space:nowrap;">{{ $pj->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $pj->nama_pembeli }}</td>
                        <td>{{ $pj->komoditas->nama_komoditas }}</td>
                        <td class="r num">{{ number_format($pj->berat_kg,0,',','.') }}</td>
                        <td class="r num">{{ number_format($pj->harga_jual_satuan,0,',','.') }}</td>
                        <td class="r num" style="color: var(--p-muted);">{{ number_format($pj->hpp_satuan,0,',','.') }}</td>
                        <td class="r num" style="font-weight:600;">{{ number_format($pj->total_nominal,0,',','.') }}</td>
                        <td class="r num" style="font-weight:600; color: {{ $pj->laba_kotor >= 0 ? '#15803d' : '#b91c1c' }};">
                            {{ number_format($pj->laba_kotor,0,',','.') }}
                        </td>
                        <td>
                            <span class="badge {{ $pj->status_bayar === 'lunas' ? 'badge-green' : 'badge-orange' }}">
                                <span class="dot"></span>{{ $pj->label_status }}
                            </span>
                        </td>
                        <td style="white-space:nowrap;">
                            <div style="display:flex; gap:6px;">
                                @if($pj->status_bayar === 'belum_lunas')
                                    <form method="POST" action="{{ route('admin.penjualan.lunas', $pj) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-xs">✓ Lunas</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.penjualan.pdf', $pj) }}" target="_blank" class="btn btn-info btn-xs">Nota PDF</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" style="text-align:center; padding:24px; color: var(--p-muted); font-size:13px;">Belum ada transaksi penjualan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $penjualans->links() }}</div>
</div>
@endsection
