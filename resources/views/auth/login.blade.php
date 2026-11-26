@extends('layouts.app')
@section('title', 'Login — Simanis BUMDes Payungsari')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-700 to-green-900 flex items-center justify-center p-4">
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            <div class="text-4xl mb-2">🌾</div>
            <h1 class="text-xl font-bold text-gray-800">Simanis BUMDes</h1>
            <p class="text-sm text-gray-500 mt-1">Desa Payungsari</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username / No. HP</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('username') border-red-500 @enderror"
                    placeholder="Masukkan username atau no HP">
                @error('username')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Masukkan password">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded">
                <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
            </div>
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition-colors">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-green-600 hover:underline">← Kembali ke halaman publik</a>
        </div>
    </div>
</div>
@endsection
