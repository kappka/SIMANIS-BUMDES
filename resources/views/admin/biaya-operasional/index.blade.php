@extends('layouts.admin')
@section('title', 'Biaya Operasional')
@section('page-title', 'Biaya Operasional')
@section('content')

<style>
    .biaya-layout {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(0, 3fr);
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 1024px) {
        .biaya-layout { grid-template-columns: 1fr; }
    }
</style>

<div class="biaya-layout">
    {{-- Form Input --}}
    <div class="card">
        <div class="card-header">Catat Biaya Operasional</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.biaya-operasional.store') }}" style="display:flex; flex-direction:column; gap:14px;">
                @csrf
                <div>
                    <label class="form-label">Tanggal <span style="color:#b91c1c;">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="form-control" required>
                </div>
                <div>
                    <label class="form-label">Kategori <span style="color:#b91c1c;">*</span></label>
                    <select name="kategori" class="form-control" required>
                        <option value="lahan_bumdes" {{ old('kategori') === 'lahan_bumdes' ? 'selected' : '' }}>Lahan BUMDes (pupuk, benih, traktor, buruh)</option>
                        <option value="angkut_timbang" {{ old('kategori') === 'angkut_timbang' ? 'selected' : '' }}>Angkut &amp; Timbang</option>
                        <option value="kemasan_karung" {{ old('kategori') === 'kemasan_karung' ? 'selected' : '' }}>Kemasan &amp; Karung</option>
                        <option value="lainnya" {{ old('kategori') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Keterangan <span style="color:#b91c1c;">*</span></label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="form-control" placeholder="Contoh: Sewa traktor blok utara" required>
                </div>
                <div>
                    <label class="form-label">Nominal (Rp) <span style="color:#b91c1c;">*</span></label>
                    <input type="number" name="nominal" value="{{ old('nominal') }}" class="form-control" min="0" step="1000" placeholder="0" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:4px;">Simpan Biaya</button>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card" style="overflow:hidden;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
            <span>Riwayat Biaya Operasional</span>
            <span style="font-size:13px; font-weight:500; color:var(--p-muted);">
                Total: <strong style="color:var(--p-ink); font-variant-numeric:tabular-nums;">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</strong>
            </span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th class="r">Nominal (Rp)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($biayas as $b)
                        <tr>
                            <td style="white-space:nowrap;">{{ $b->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-orange">{{ $b->label_kategori }}</span>
                            </td>
                            <td style="color:var(--p-muted);">{{ $b->keterangan }}</td>
                            <td class="r num" style="font-weight:600;">{{ number_format($b->nominal, 0, ',', '.') }}</td>
                            <td class="r">
                                <form method="POST" action="{{ route('admin.biaya-operasional.destroy', $b) }}" class="inline"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:28px; color:var(--p-muted); font-size:13px;">
                                Belum ada data biaya.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($biayas->hasPages())
            <div class="card-footer">{{ $biayas->links() }}</div>
        @endif
    </div>
</div>
@endsection
