@extends('layouts.admin')
@section('title', 'Edit Petani')
@section('page-title', 'Edit Data Petani')
@section('content')
<div class="card" style="max-width:520px;">
    <div class="card-header">Perbarui Data Petani</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.petani.update', $petani) }}" style="display:flex; flex-direction:column; gap:16px;">
            @csrf @method('PUT')
            <div>
                <label class="form-label">Nama Lengkap <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $petani->nama) }}" class="form-control" required>
            </div>
            <div>
                <label class="form-label">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $petani->nik) }}" class="form-control" maxlength="20">
            </div>
            <div>
                <label class="form-label">No. HP <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $petani->no_hp) }}" class="form-control" required>
            </div>
            <div>
                <label class="form-label">Alamat Dusun <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="alamat_dusun" value="{{ old('alamat_dusun', $petani->alamat_dusun) }}" class="form-control" required>
            </div>
            <div style="display:flex; gap:10px; align-items:center; padding-top:4px;">
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('admin.petani.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
