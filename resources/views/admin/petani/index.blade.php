@extends('layouts.admin')
@section('title', 'Data Petani')
@section('page-title', 'Petani & Lahan')
@section('content')

<div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
    <a href="{{ route('admin.petani.index') }}" class="btn {{ request()->routeIs('admin.petani.*') ? 'btn-primary' : 'btn-ghost' }} btn-sm">Data Petani</a>
    <a href="{{ route('admin.lahan.index') }}" class="btn btn-ghost btn-sm">Data Lahan</a>
    <a href="{{ route('admin.petani.create') }}" class="btn btn-accent btn-sm" style="margin-left:auto;">+ Tambah Petani</a>
</div>

<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Petani</th>
                    <th>NIK</th>
                    <th>No. HP</th>
                    <th>Dusun</th>
                    <th>Akun Login</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($petanis as $p)
                    <tr>
                        <td style="font-weight:600;">{{ $p->nama }}</td>
                        <td style="color: var(--p-muted);">{{ $p->nik ?? '—' }}</td>
                        <td>{{ $p->no_hp }}</td>
                        <td>{{ $p->alamat_dusun }}</td>
                        <td>
                            @if($p->user)
                                <span class="badge badge-green">{{ $p->user->username }}</span>
                            @else
                                <span style="font-size:12px; color: var(--p-muted);">Belum ada akun</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                <a href="{{ route('admin.petani.edit', $p) }}" class="btn btn-info btn-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.petani.reset-akun', $p) }}" class="inline"
                                      onsubmit="return confirm('Reset akun login petani ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-xs">Reset Akun</button>
                                </form>
                                <form method="POST" action="{{ route('admin.petani.destroy', $p) }}" class="inline"
                                      onsubmit="return confirm('Hapus data petani ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center; padding:24px; color: var(--p-muted); font-size:13px;">Belum ada data petani.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
