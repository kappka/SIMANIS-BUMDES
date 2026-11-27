@extends('layouts.admin')
@section('title', 'Data Lahan')
@section('page-title', 'Data Lahan')
@section('content')

<div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
    <a href="{{ route('admin.petani.index') }}" class="btn btn-ghost btn-sm">Data Petani</a>
    <span class="btn btn-primary btn-sm">Data Lahan</span>
    <a href="{{ route('admin.lahan.create') }}" class="btn btn-accent btn-sm" style="margin-left:auto;">+ Tambah Lahan</a>
</div>

<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Blok</th>
                    <th>Kepemilikan</th>
                    <th class="r">Luas (Ha)</th>
                    <th>Komoditas Tanam</th>
                    <th>Est. Panen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lahans as $l)
                    <tr>
                        <td style="font-weight:600;">{{ $l->nama_blok }}</td>
                        <td>
                            @if($l->petani_id)
                                <span class="badge badge-blue">{{ $l->petani->nama }}</span>
                            @else
                                <span class="badge badge-green">Lahan BUMDes</span>
                            @endif
                        </td>
                        <td class="r num">{{ $l->luas_hektar }}</td>
                        <td>{{ $l->komoditas_tanam ?? '—' }}</td>
                        <td>{{ $l->estimasi_panen ? $l->estimasi_panen->format('d/m/Y') : '—' }}</td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="{{ route('admin.lahan.edit', $l) }}" class="btn btn-info btn-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.lahan.destroy', $l) }}" class="inline"
                                      onsubmit="return confirm('Hapus lahan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center; padding:24px; color: var(--p-muted); font-size:13px;">Belum ada data lahan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
