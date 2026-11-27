@extends('layouts.admin')
@section('title', 'Stok & Harga')
@section('page-title', 'Stok & Harga Komoditas')
@section('content')

<div x-data="{ tab: 'stok', editId: null, editHargaBeli: '', editHargaJual: '' }">

    {{-- Tab bar --}}
    <div class="tab-bar">
        <button @click="tab='stok'" :class="tab==='stok' ? 'active' : ''" class="tab-btn">Master Komoditas</button>
        <button @click="tab='opname'" :class="tab==='opname' ? 'active' : ''" class="tab-btn">Stock Opname</button>
        <button @click="tab='tambah'" :class="tab==='tambah' ? 'active' : ''" class="tab-btn">+ Tambah Komoditas</button>
    </div>

    {{-- Tab: Master Komoditas --}}
    <div x-show="tab==='stok'" class="card" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Komoditas</th>
                        <th class="r">Stok (Kg)</th>
                        <th class="r">HPP Rata2</th>
                        <th class="r">Harga Beli Aktif</th>
                        <th class="r">Harga Jual Aktif</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($komoditas as $k)
                        <tr>
                            <td style="font-weight:600;">{{ $k->nama_komoditas }}</td>
                            <td class="r num">{{ number_format($k->stok_kg, 0, ',', '.') }}</td>
                            <td class="r num" style="color: var(--p-muted);">Rp {{ number_format($k->harga_modal_rata2, 0, ',', '.') }}</td>
                            <td class="r num">Rp {{ number_format($k->harga_beli_aktif, 0, ',', '.') }}</td>
                            <td class="r num" style="color:#15803d; font-weight:600;">Rp {{ number_format($k->harga_jual_aktif, 0, ',', '.') }}</td>
                            <td>
                                @php $s = $k->status_stok; @endphp
                                <span class="badge {{ $s === 'Tersedia' ? 'badge-green' : ($s === 'Menipis' ? 'badge-yellow' : 'badge-red') }}">
                                    <span class="dot"></span>{{ $s }}
                                </span>
                            </td>
                            <td style="display:flex; gap:8px; align-items:center;">
                                <button @click="editId={{ $k->id }}; editHargaBeli={{ $k->harga_beli_aktif }}; editHargaJual={{ $k->harga_jual_aktif }}"
                                    class="btn btn-info btn-xs">Edit Harga</button>
                                <form method="POST" action="{{ route('admin.komoditas.destroy', $k) }}" class="inline"
                                      onsubmit="return confirm('Hapus komoditas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Inline edit row --}}
                        <tr x-show="editId === {{ $k->id }}" x-cloak style="background: rgba(246,224,22,.06);">
                            <td colspan="7" style="padding: 14px 16px;">
                                <form method="POST" action="{{ route('admin.komoditas.update', $k) }}" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                                    @csrf @method('PUT')
                                    <div>
                                        <label class="form-label">Harga Beli (Rp/Kg)</label>
                                        <input type="number" name="harga_beli_aktif" x-model="editHargaBeli" class="form-control" style="width:150px;" min="0" step="100">
                                    </div>
                                    <div>
                                        <label class="form-label">Harga Jual (Rp/Kg)</label>
                                        <input type="number" name="harga_jual_aktif" x-model="editHargaJual" class="form-control" style="width:150px;" min="0" step="100">
                                    </div>
                                    <div style="display:flex; gap:8px;">
                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                        <button type="button" @click="editId=null" class="btn btn-ghost btn-sm">Batal</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center; padding:24px; color: var(--p-muted); font-size:13px;">Belum ada komoditas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab: Stock Opname --}}
    <div x-show="tab==='opname'" x-cloak style="display:grid; grid-template-columns: 2fr 3fr; gap:16px;">
        <div class="card card-body">
            <div style="font-weight:600; font-size:14px; margin-bottom:16px;">Penyesuaian Stok</div>
            <form method="POST" action="{{ route('admin.penyesuaian-stok.store') }}" style="display:flex; flex-direction:column; gap:14px;">
                @csrf
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control" required>
                </div>
                <div>
                    <label class="form-label">Komoditas</label>
                    <select name="komoditas_id" class="form-control" required>
                        <option value="">— Pilih —</option>
                        @foreach($komoditas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_komoditas }} ({{ number_format($k->stok_kg,0,',','.') }} Kg)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-control" required>
                        <option value="susut">Penyusutan (Kurangi Stok)</option>
                        <option value="koreksi_tambah">Koreksi Tambah</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Berat (Kg)</label>
                    <input type="number" name="berat_kg" class="form-control" min="0.01" step="0.01" required>
                </div>
                <div>
                    <label class="form-label">Alasan</label>
                    <input type="text" name="alasan" class="form-control" placeholder="Misal: Gabah mengering" required>
                </div>
                <button type="submit" class="btn btn-warning" style="justify-content:center;">Simpan Penyesuaian</button>
            </form>
        </div>

        <div class="card" style="overflow:hidden;">
            <div class="card-header">Riwayat Penyesuaian</div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Komoditas</th>
                            <th>Jenis</th>
                            <th class="r">Berat (Kg)</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penyesuaians as $p)
                            <tr>
                                <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $p->komoditas->nama_komoditas }}</td>
                                <td><span class="badge {{ $p->jenis === 'susut' ? 'badge-red' : 'badge-green' }}">{{ $p->label_jenis }}</span></td>
                                <td class="r num">{{ number_format($p->berat_kg, 0, ',', '.') }}</td>
                                <td style="color: var(--p-muted);">{{ $p->alasan }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center; padding:20px; color: var(--p-muted); font-size:13px;">Belum ada riwayat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tab: Tambah Komoditas --}}
    <div x-show="tab==='tambah'" x-cloak class="card" style="max-width:420px;">
        <div class="card-header">Tambah Komoditas Baru</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.komoditas.store') }}" style="display:flex; flex-direction:column; gap:14px;">
                @csrf
                <div>
                    <label class="form-label">Nama Komoditas</label>
                    <input type="text" name="nama_komoditas" class="form-control" required>
                </div>
                <div>
                    <label class="form-label">Harga Beli Awal (Rp/Kg)</label>
                    <input type="number" name="harga_beli_aktif" class="form-control" min="0" step="100" required>
                </div>
                <div>
                    <label class="form-label">Harga Jual Awal (Rp/Kg)</label>
                    <input type="number" name="harga_jual_aktif" class="form-control" min="0" step="100" required>
                </div>
                <button type="submit" class="btn btn-primary" style="justify-content:center;">Tambah Komoditas</button>
            </form>
        </div>
    </div>

</div>
@endsection
