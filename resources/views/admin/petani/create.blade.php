@extends('layouts.admin')
@section('title', 'Tambah Petani')
@section('page-title', 'Tambah Data Petani')
@section('content')
<div class="card" style="max-width:520px;">
    <div class="card-header">Formulir Petani Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.petani.store') }}" style="display:flex; flex-direction:column; gap:16px;">
            @csrf
            <div>
                <label class="form-label">Nama Lengkap <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required>
            </div>
            <div>
                <label class="form-label">NIK (opsional)</label>
                <input type="text" name="nik" value="{{ old('nik') }}" class="form-control" maxlength="20">
            </div>
            <div>
                <label class="form-label">No. HP <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control" required>
                <p style="font-size:12px; color:var(--p-muted); margin-top:5px;">Akan dijadikan username &amp; password login awal petani.</p>
            </div>
            <div>
                <label class="form-label">Alamat Dusun <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="alamat_dusun" value="{{ old('alamat_dusun') }}" class="form-control" required>
            </div>
            <div style="display:flex; gap:10px; align-items:center; padding-top:4px;">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.petani.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
