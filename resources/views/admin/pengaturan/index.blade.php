@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan BUMDes')
@section('content')
<div class="card" style="max-width:540px;">
    <div class="card-header">Identitas &amp; Kontak BUMDes</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" style="display:flex; flex-direction:column; gap:16px;">
            @csrf @method('PUT')
            <div>
                <label class="form-label">Nama BUMDes <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="nama_bumdes" value="{{ old('nama_bumdes', $pengaturan->nama_bumdes) }}"
                       class="form-control" required>
            </div>
            <div>
                <label class="form-label">Alamat BUMDes <span style="color:#b91c1c;">*</span></label>
                <textarea name="alamat_bumdes" rows="2" class="form-control" required>{{ old('alamat_bumdes', $pengaturan->alamat_bumdes) }}</textarea>
            </div>
            <div>
                <label class="form-label">Nama Ketua / Penandatangan Nota <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="nama_ketua" value="{{ old('nama_ketua', $pengaturan->nama_ketua) }}"
                       class="form-control" required>
            </div>
            <div>
                <label class="form-label">Nomor WhatsApp Layanan Stok <span style="color:#b91c1c;">*</span></label>
                <input type="text" name="no_wa_stok" value="{{ old('no_wa_stok', $pengaturan->no_wa_stok) }}"
                       class="form-control" required>
                <p style="font-size:12px; color:var(--p-muted); margin-top:5px;">
                    Format: 081234567890 atau 6281234567890. Digunakan pada tombol WhatsApp halaman publik.
                </p>
            </div>
            <div class="flash flash-success" style="margin-bottom:0; font-size:12.5px;">
                <span>Link WA aktif: <a href="https://wa.me/{{ $pengaturan->normalized_no_wa }}" target="_blank" style="color:inherit; font-weight:600; text-decoration:underline;">https://wa.me/{{ $pengaturan->normalized_no_wa }}</a></span>
            </div>
            <div style="padding-top:4px;">
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
@endsection
