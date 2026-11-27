@extends('layouts.admin')
@section('title', 'Barang Masuk')
@section('page-title', 'Transaksi Barang Masuk')
@section('content')

{{-- Form --}}
<div class="card card-body" style="margin-bottom:20px;" x-data="{
    sumber: 'beli_petani',
    berat: 0, harga: 0, total: 0,
    updateHarga(h) { this.harga = h; this.updateTotal(); },
    updateTotal() { this.total = this.berat * this.harga; }
}">
    <div style="font-weight:600; font-size:14px; margin-bottom:16px;">Input Transaksi Barang Masuk</div>
    <form method="POST" action="{{ route('admin.barang-masuk.store') }}" style="display:grid; grid-template-columns: repeat(auto-fill,minmax(220px,1fr)); gap:14px;">
        @csrf

        <div>
            <label class="form-label">Tanggal *</label>
            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control" required>
        </div>
        <div>
            <label class="form-label">Sumber *</label>
            <select name="sumber" x-model="sumber" class="form-control" required>
                <option value="beli_petani">Beli dari Petani</option>
                <option value="panen_bumdes">Panen Lahan BUMDes</option>
            </select>
        </div>
        <div>
            <label class="form-label">Komoditas *</label>
            <select name="komoditas_id" class="form-control" required
                    @change="updateHarga(parseFloat($event.target.selectedOptions[0].dataset.harga || 0))">
                <option value="">— Pilih Komoditas —</option>
                @foreach($komoditas as $k)
                    <option value="{{ $k->id }}" data-harga="{{ $k->harga_beli_aktif }}">
                        {{ $k->nama_komoditas }} (Stok: {{ number_format($k->stok_kg,0,',','.') }} Kg)
                    </option>
                @endforeach
            </select>
        </div>

        <div x-show="sumber === 'beli_petani'" x-cloak>
            <label class="form-label">Petani *</label>
            <select name="petani_id" class="form-control">
                <option value="">— Pilih Petani —</option>
                @foreach($petanis as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->no_hp }})</option>
                @endforeach
            </select>
        </div>
        <div x-show="sumber === 'panen_bumdes'" x-cloak>
            <label class="form-label">Lahan BUMDes</label>
            <select name="lahan_id" class="form-control">
                <option value="">— Pilih Lahan (opsional) —</option>
                @foreach($lahans as $l)
                    <option value="{{ $l->id }}">{{ $l->nama_blok }} ({{ $l->luas_hektar }} Ha)</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Berat Bersih (Kg) *</label>
            <input type="number" name="berat_kg" x-model.number="berat" @input="updateTotal()"
                   class="form-control" min="0.01" step="0.01" required>
        </div>

        <div x-show="sumber === 'beli_petani'" x-cloak>
            <label class="form-label">Harga Beli/Kg (Rp) *</label>
            <input type="number" name="harga_satuan" x-model.number="harga" @input="updateTotal()"
                   class="form-control" min="0" step="100">
        </div>
        <div x-show="sumber === 'panen_bumdes'" x-cloak>
            <label class="form-label">Harga Beli/Kg</label>
            <input type="text" value="Rp 0 (Panen Sendiri)" disabled class="form-control">
        </div>

        <div>
            <label class="form-label">Total Nominal</label>
            <div class="form-control" style="background: var(--p-bg); font-weight:600;">
                Rp <span x-text="new Intl.NumberFormat('id-ID').format(total)">0</span>
            </div>
        </div>

        <div x-show="sumber === 'beli_petani'" x-cloak>
            <label class="form-label">Status Bayar</label>
            <select name="status_bayar" class="form-control">
                <option value="lunas">Lunas</option>
                <option value="belum_lunas">Belum Lunas</option>
            </select>
        </div>

        <div style="grid-column: 1 / -1;">
            <label class="form-label">Catatan (opsional)</label>
            <input type="text" name="catatan" class="form-control" placeholder="Keterangan tambahan...">
        </div>

        <div style="grid-column: 1 / -1;">
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </div>
    </form>
</div>

{{-- Filter --}}
<div class="card card-body" style="margin-bottom:16px;">
    <form method="GET" action="{{ route('admin.barang-masuk.index') }}" class="filter-bar">
        <div>
            <label class="form-label">Dari</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="form-control form-control-sm">
        </div>
        <div>
            <label class="form-label">Sampai</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control form-control-sm">
        </div>
        <div>
            <label class="form-label">Sumber</label>
            <select name="sumber" class="form-control form-control-sm">
                <option value="">Semua</option>
                <option value="beli_petani" {{ request('sumber') === 'beli_petani' ? 'selected' : '' }}>Beli Petani</option>
                <option value="panen_bumdes" {{ request('sumber') === 'panen_bumdes' ? 'selected' : '' }}>Panen BUMDes</option>
            </select>
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
        <a href="{{ route('admin.barang-masuk.index') }}" class="btn btn-ghost btn-sm">Reset</a>
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
                    <th>Sumber</th>
                    <th>Petani/Lahan</th>
                    <th>Komoditas</th>
                    <th class="r">Berat (Kg)</th>
                    <th class="r">Harga/Kg</th>
                    <th class="r">Total (Rp)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangMasuks as $bm)
                    <tr>
                        <td class="nota">{{ $bm->no_nota }}</td>
                        <td style="white-space:nowrap;">{{ $bm->tanggal->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $bm->sumber === 'beli_petani' ? 'badge-blue' : 'badge-purple' }}">
                                {{ $bm->label_sumber }}
                            </span>
                        </td>
                        <td style="color: var(--p-muted);">{{ $bm->petani?->nama ?? $bm->lahan?->nama_blok ?? '—' }}</td>
                        <td>{{ $bm->komoditas->nama_komoditas }}</td>
                        <td class="r num">{{ number_format($bm->berat_kg, 0, ',', '.') }}</td>
                        <td class="r num">{{ $bm->harga_satuan > 0 ? 'Rp '.number_format($bm->harga_satuan,0,',','.') : 'Rp 0' }}</td>
                        <td class="r num" style="font-weight:600;">{{ number_format($bm->total_nominal,0,',','.') }}</td>
                        <td>
                            <span class="badge {{ $bm->status_bayar === 'lunas' ? 'badge-green' : 'badge-orange' }}">
                                <span class="dot"></span>{{ $bm->label_status }}
                            </span>
                        </td>
                        <td style="white-space:nowrap;">
                            <div style="display:flex; gap:6px; align-items:center;">
                                @if($bm->status_bayar === 'belum_lunas')
                                    <form method="POST" action="{{ route('admin.barang-masuk.lunas', $bm) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-xs">✓ Lunas</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.barang-masuk.pdf', $bm) }}" target="_blank" class="btn btn-info btn-xs">Nota PDF</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" style="text-align:center; padding:24px; color: var(--p-muted); font-size:13px;">Belum ada transaksi barang masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $barangMasuks->links() }}</div>
</div>
@endsection
