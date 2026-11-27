@extends('layouts.app')
@section('title', 'Masuk — Simanis BUMDes')
@section('content')

<div style="min-height:100svh; display:grid; place-items:center; padding:40px var(--edge);">
    {{-- Decorative background strip --}}
    <div style="position:fixed; inset:0; pointer-events:none; z-index:0;">
        <div style="position:absolute; left:0; top:0; bottom:0; width:46%; background: var(--p-inv-bg);"></div>
    </div>

    <div style="position:relative; z-index:1; display:grid; grid-template-columns: 1fr 1fr; max-width:860px; width:100%; border-radius:20px; overflow:hidden; box-shadow: 0 20px 60px rgba(13,42,28,.22);">

        {{-- Left panel --}}
        <div style="background: var(--p-inv-bg); color: var(--p-inv-ink); padding: clamp(32px,6vw,56px); display:flex; flex-direction:column; justify-content:space-between;">
            <div>
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:48px;">
                    <svg width="32" height="32" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                        <rect width="28" height="28" rx="7" fill="rgba(255,255,255,.1)"/>
                        <path d="M14 6C14 6 8 10 8 15.5a6 6 0 0012 0C20 10 14 6 14 6Z" fill="#f6e016"/>
                        <path d="M11 17c0 1.657 1.343 3 3 3s3-1.343 3-3" stroke="#0d2a1c" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span style="font-weight:700; font-size:16px;">Simanis</span>
                </div>
                <h1 style="font-size:clamp(24px,3vw,32px); font-weight:700; letter-spacing:-0.03em; line-height:1.2; margin-bottom:12px;">
                    Sistem informasi<br>pangan desa.
                </h1>
                <p style="font-size:14px; color: var(--p-inv-muted); line-height:1.65;">
                    Kelola stok komoditas, catat transaksi petani, dan pantau laporan keuangan BUMDes dari satu dasbor.
                </p>
            </div>
            <div>
                <div style="font-size:11px; color: var(--p-inv-muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:8px;">Desa Payungsari</div>
                <div style="width:40px; height:3px; background: var(--p-inv-accent); border-radius:2px;"></div>
            </div>
        </div>

        {{-- Right panel --}}
        <div style="background:#fff; padding: clamp(32px,6vw,56px);">
            <h2 style="font-size:20px; font-weight:700; margin-bottom:6px; letter-spacing:-0.02em;">Masuk</h2>
            <p style="font-size:13.5px; color: var(--p-muted); margin-bottom:32px;">Gunakan akun yang diberikan admin BUMDes.</p>

            <form method="POST" action="{{ route('login') }}" style="display:flex; flex-direction:column; gap:18px;">
                @csrf

                @if($errors->any())
                    <div class="flash flash-error">
                        <div>
                            @foreach($errors->all() as $e)
                                <div>{{ $e }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="form-label" for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}"
                        class="form-control @error('username') border-red-400 @enderror"
                        placeholder="Masukkan username" required autofocus autocomplete="username">
                </div>

                <div>
                    <label class="form-label" for="password">Password</label>
                    <input id="password" type="password" name="password"
                        class="form-control"
                        placeholder="••••••••" required autocomplete="current-password">
                </div>

                <div style="display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" name="remember" id="remember" style="width:15px; height:15px; accent-color: var(--p-action); cursor:pointer;">
                    <label for="remember" style="font-size:13px; color: var(--p-muted); cursor:pointer;">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-primary" style="justify-content:center; padding:12px;">
                    Masuk
                </button>

                <a href="{{ route('home') }}" style="text-align:center; font-size:13px; color: var(--p-muted); text-decoration:none; transition: color var(--transition);"
                   onmouseover="this.style.color='var(--p-ink)'" onmouseout="this.style.color='var(--p-muted)'">
                    ← Lihat harga publik
                </a>
            </form>
        </div>
    </div>
</div>

<style>
    @media (max-width: 600px) {
        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
        div[style*="position:absolute; left:0"] { display: none; }
    }
</style>
@endsection
