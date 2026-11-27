@extends('layouts.admin')
@section('title', 'Tambah Lahan')
@section('page-title', 'Tambah Data Lahan')
@section('content')
<div class="card" style="max-width:520px;">
    <div class="card-header">Formulir Lahan Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.lahan.store') }}" style="display:flex; flex-direction:column; gap:16px;">
            @csrf
            <div>
                <label class="form-label">Kepemilikan Lahan</label>
                <select name="petani_id" class="form-control">
                    <option value="">Lahan Kelolaan BUMDes</option>
                    @foreach($petanis as $p)
                        <option value="{{ $p->id }}" {{ old('petani_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} ({{ $p->no_hp }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Nama Blok Lahan <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="nama_blok" value="{{ old('nama_blok') }}" class="form-control" required>
            </div>
            <div>
                <label class="form-label">Luas (Hektar) <span style="color:#b91c1c;">*</span></label>
                <input type="number" name="luas_hektar" value="{{ old('luas_hektar') }}" class="form-control" min="0.01" step="0.01" required>
            </div>
            <div>
                <label class="form-label">Komoditas Tanam</label>
                <input type="text" name="komoditas_tanam" value="{{ old('komoditas_tanam') }}" class="form-control">
            </div>
            <div>
                <label class="form-label">Estimasi Panen</label>
                <input type="date" name="estimasi_panen" value="{{ old('estimasi_panen') }}" class="form-control">
            </div>
            <div style="display:flex; gap:10px; align-items:center; padding-top:4px;">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.lahan.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
